<?php

if (! isset($_POST['a'])) {
    $template->setvar('content', $template->parse('submit'));
    echo $template->parse('global');
}
elseif ($_POST['a'] == 'submit') {
    $db = new PDO("sqlite:" . DB_NAME);

    $password = addslashes($_POST['password']);
    $flag = addslashes($_POST['flag']);
    
    $sql =  "SELECT * FROM players WHERE ";
    $sql .= "password='" . hashpass($_POST['password']) ."'";
    $result = $db->query($sql);
    $player = $result->fetch();
    
    if (! $player)
        message('bad password', 'You entered a bad password', true, false);
        
    $sql  = "SELECT * FROM challenges WHERE ";
    $sql .= "flag='".$flag."'";
    $result = $db->query($sql);
    $challenge = $result->fetch();
    
    if (! $challenge)
        message('bad flag', 'You entered a bad flag', true, false);
    
    $sql  = "SELECT COUNT(id) FROM submissions WHERE ";
    $sql .= "player_id='".$player['id']."' ";
    $sql .= "AND challenge_id='".$challenge['id']."'";
    $result = $db->query($sql);
    $flagcheck = $result->fetch();
    if ($flagcheck['COUNT(id)'] > 0)
        message('already submitted', 'You have already submitted this flag',
                true, false);
    
    if (isset($_SERVER['REMOTE_HOST']))
        $host = addslashes($_SERVER['REMOTE_HOST']);
    else
        $host = addslashes($_SERVER['REMOTE_ADDR']);
    $sql  = "INSERT INTO submissions (player_id, challenge_id, time, host) ";
    $sql .= "VALUES ('".$player['id']."', '".$challenge['id']."', ";
    $sql .= "'".time()."', '".$host."')";
    $db->exec($sql);
    
    message('flag submitted',
            'Flag for ' . $challenge['name'] . ' submitted for ' .
            $challenge['points'] . ' points', false, './?scoreboard');
}
?>
