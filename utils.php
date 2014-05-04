<?php

require_once('../dbsetup.php');

$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

class TamsException extends Exception {
    const E_GENERAL = 0;
    const E_SQL_ENGINE = 1;
    const E_SQL_EMPTYRESULT = 2;
    const E_SQL_MULTIRESULT = 3;
    const E_SQL_PREPARE = 4;
    const E_SQL_FETCH = 5;

    public function __construct($code, $message = null) {
        switch ($code) {
        case self::E_GENERAL:
            $message = 'Generic error';
            break;
        case self::E_SQL_ENGINE:
            $message = "SQL engine error: $message";
            break;
        case self::E_SQL_EMPTYRESULT:
            $message = 'Query returned empty result when one was expected';
            break;
        case self::E_SQL_MULTIRESULT:
            $message = 'Query returned multiple results when only one was expected';
            break;
        case self::E_SQL_PREPARE:
            $message = "SQL prepare error: $message";
            break;
        case self::E_SQL_FETCH:
            $message = "SQL fetch error: $message";
            break;
        }
        parent::__construct($message, $code, null);
    }

    public function __toString() {
        return $this->getMessage().
            ' (on line <b>'.$this->getLine().'</b> of <b>'.$this->getFile().'</b>)';
    }
}

class Utils {
    private static function executeStatement($stmt,$arr,$ignoreError=false) {
        if (empty($arr)) {
            $guard = $stmt->execute();	
        } else {
            $guard = $stmt->execute($arr);
        }
        if (!$guard) {
            if ($ignoreError) {
                return false;
            } else {
                $errorInfo = $stmt->errorInfo();
                throw new TamsException(TamsException::E_SQL_ENGINE, $errorInfo[2]);
            }
        } else {
            return true;
        }
    }

    public static function prepareArray($row) {
        $row2 = array();
        foreach ($row as $key => $value) {
            $row2[':' . $key] = $value;
        }
        return $row2;
    }

    public static function getMapping($sql,$arr,$callback,$limit_start = null,$limit_len = null) {
        global $db;
        $stmt = $db->prepare($sql);
        if ($stmt === false) {
            $errorInfo = $db->errorInfo();
            throw new TamsException(TamsException::E_SQL_PREPARE, $errorInfo[2]);
        }
        if ($limit_start !== null && $limit_len !== null) {
            $stmt->bindParam(':start',intval($limit_start),PDO::PARAM_INT);
            $stmt->bindParam(':len',intval($limit_len),PDO::PARAM_INT);
        }
        Utils::executeStatement($stmt,$arr);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        if ($rows === false) {
            $errorInfo = $db->errorInfo();
            throw new TamsException(TamsException::E_SQL_FETCH, $errorInfo[2]);
        }
        return array_map($callback,$rows);//$stmt->fetchAll());
    }

    public static function getSingle($sql,$arr,$callback=null,$scalarResult=false) {
        global $db;
        if ($callback == null) { $callback = function($x) { return $x; }; }
        $stmt = $db->prepare($sql);
        if ($stmt === false) {
            $errorInfo = $db->errorInfo();
            throw new TamsException(TamsException::E_SQL_PREPARE, $errorInfo[2]);
        }
        Utils::executeStatement($stmt,$arr);
        $stmt->setFetchMode($scalarResult ? PDO::FETCH_NUM : PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        if ($result == null) {
            throw new TamsException(TamsException::E_SQL_EMPTYRESULT);
        }
        if ($scalarResult) {
            if (!isset($result[0])) {
                throw new TamsException(TamsException::E_SQL_EMPTYRESULT);
            } else {
                return $callback($result[0]);
            }
        } else {
            return $callback($result);
        }
    }

    public static function getVoid($sql,$arr,$ignoreError=false) {
        global $db;
        $stmt = $db->prepare($sql);
        if ($stmt === false) {
            $errorInfo = $db->errorInfo();
            throw new TamsException(TamsException::E_SQL_PREPARE, $errorInfo[2]);
        }
        return Utils::executeStatement($stmt,$arr,$ignoreError);
    }

    public static function beginTransaction() {
        global $db;
        $db->beginTransaction();
    }

    public static function cancelTransaction() {
        global $db;
        $db->rollBack();
    }

    public static function commitTransaction() {
        global $db;
        $db->commit();
    }
}

?>
