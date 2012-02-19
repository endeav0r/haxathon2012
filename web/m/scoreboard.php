<?php

$db = new PDO("sqlite:" . DB_NAME);

$players = '';
foreach ($db->query('SELECT * FROM players') as $row) {
    $players .= $row['name'] . '<br />';
}

$template->setvar('content', $players);
echo $template->parse('global');

?>
