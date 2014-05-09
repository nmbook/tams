<?php
/**
 *  * This code will benchmark your server to determine how high of a cost you can
 *   * afford. You want to set the highest cost that you can without slowing down
 *    * you server too much. 10 is a good baseline, and more is good if your servers
 *     * are fast enough.
 *      */
$timeTarget = 0.2; 

$cost = 10;
do {
    $cost++;
    $start = microtime(true);
    crypt("test", "$2a$$cost\$i0IQRzjUTmjUfyGZMbW8V$");
    $end = microtime(true);
} while (($end - $start) < $timeTarget);

echo "Appropriate Cost Found: " . $cost . "\n";

