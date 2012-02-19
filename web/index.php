<?php

include 'includes/include.php';

include 'includes/template.php';
$template = new template();

ini_set('display_errors', 1);

if (isset($_REQUEST['challenges']))
    include 'm/challenges.php';
elseif (isset($_REQUEST['scoreboard']))
    include 'm/scoreboard.php';
else
    include 'm/info.php';

?>
