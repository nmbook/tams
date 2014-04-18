<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../dbsetup.php');

function echo_paginate_link($text, $new_start) {
    echo "<a href=\"talist-tuesdaynight.php?start=$new_start\">$text</a>";
}

function echo_paginate() {
    $total = TA::getTACount();
    if ($start > 0) {
        echo_paginate_link('<-- Previous', min(0, $start - 20));
        if ($start < $total - 20) { echo ' | '; }
    }
    if ($start < $total - 20) {
        echo_paginate_link('Next -->', max($total - 20, $start + 20));
    }
}

?>
<!DOCTYPE html>
<html>
<head><title>TA Lister - TuesdayNight Project (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>TA Lister</h2>

<?php 

$start = isset($_GET['start']) : $_GET['start'] : 0;

if (!is_numeric($start)) {
    $start = 0;
}

echo "START = $start\n";

//TA::getByRange($start, 20);

echo_paginate();

?>


</body>
</html>

