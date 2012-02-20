<?php

$db = new PDO("sqlite:" . DB_NAME);

// get total score
$result = $db->query('SELECT SUM(points) FROM challenges');
$total_points = $result->fetch();
$total_points = $total_points['SUM(points)'];

$template->setvar('total_points', $total_points);

$players = '';
$sql  = 'SELECT players.name, SUM(challenges.points) FROM players ';
$sql .= 'LEFT JOIN submissions ON submissions.player_id = players.id ';
$sql .= 'LEFT JOIN challenges ON submissions.challenge_id = challenges.id ';
$sql .= 'GROUP BY players.name ';
$sql .= 'ORDER BY SUM(challenges.points) DESC';
foreach ($db->query($sql) as $row) {
    $points = $row['SUM(challenges.points)'];
    if ($points == '') $points = 0;

    $template->setvar('player.name', stripslashes($row['name']));
    $template->setvar('player.score', $points);
    $green = round($points / $total_points * 100);
    $template->setvar('player.green', $green);
    $template->setvar('player.red', 100 - $green);
    $players .= $template->parse('scoreboard_item');
}
$template->setvar('scoreboard_items', $players);

$challenges = '';
$sql = 'SELECT * FROM challenges ORDER BY name ASC';
foreach ($db->query($sql) as $challenge) {
    $sql  = 'SELECT * FROM players JOIN submissions WHERE ';
    $sql .= 'players.id = submissions.player_id AND ';
    $sql .= 'submissions.challenge_id = \''.$challenge['id'].'\' ';
    $sql .= 'ORDER BY submissions.time ASC';
    $players = array();
    foreach ($db->query($sql) as $player)
        $players[] = stripslashes($player['name']);
    if (count($players) == 0)
        $players_string = '&nbsp;';
    else
        $players_string = implode(', ', $players);
    $template->setvar('challenge.completed_by', $players_string);
    $template->setvar('challenge.name', stripslashes($challenge['name']));
    $template->setvar('challenge.points', $challenge['points']);
    $challenges .= $template->parse('scoreboard_item2');
}
$template->setvar('scoreboard_items2', $challenges);

$template->setvar('content', $template->parse('scoreboard'));
echo $template->parse('global');

?>
