<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('ta.php');
function echo_paginate_link($text, $new_start) {
    echo "<a href=\"talist-tuesdaynight.php?start=$new_start\">".htmlentities($text)."</a>";
}
function echo_paginate() {
    $start = isset($_GET['start']) ? $_GET['start'] : 0;

    if (!is_numeric($start)) {
        $start = 0;
    }

    $per_page = 20;
    $count = TA::getCount();
    if ($start >= $count + 20) {
        $start = $count - 20;
    }
    $prev = max(0, $start - $per_page);
    $next = min($count - $per_page, $start + $per_page);

    return array('start'=>$start + 0,'count'=>$count + 0,'per_page'=>$per_page,'prev'=>$prev,'next'=>$next);
}

?>
<!DOCTYPE html>
<html>
<head><title>TA Lister - TuesdayNight Project (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>TA Lister</h2>

<?php 

# Pagination...
$page_result = echo_paginate();
$start = $page_result['start'];
$count = $page_result['count'];
$per_page = $page_result['per_page'];
$prev = $page_result['prev'];
$next = $page_result['next'];

$start_h = $start + 1;

if ($start > 0) {
    echo_paginate_link('|<-- First', 0);
    echo ' | ';
    echo_paginate_link('<-- Previous', $prev);
    if ($start < $count - $per_page) { echo ' | '; }
}

if ($start < $count - $per_page) {
    echo_paginate_link('Next -->', $next);
    echo ' | ';
    echo_paginate_link('Last -->|', $count - $per_page);
}

echo " Showing $per_page TAs starting from $start_h (total: $count):<br />";

//print_r($page_result);

$tas = TA::getByRange($start, $per_page);

if (count($tas) == 0) {
    echo '<p>There are no TAs to display.</p>';
} else {
    echo '<table cellspacing="1"><thead><th width="150">Net ID</th><th width="150">Name</th><th width="250">E-Mail</th><th>Class Year</th></thead><tbody>';
    foreach ($tas as $ta) {
        echo "<tr><td>{$ta->getNetID()}</td><td>{$ta->getName()}</td><td>{$ta->getEmail()}</td><td>{$ta->getClassYear()}</td></tr>";
    }
    echo '</tbody></table>';
}

//var_dump($data);

//TA::getByRange($start, 20);

?>

</body>
</html>

