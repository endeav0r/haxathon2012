<?php

include 'includes/include.php';

if (! isset($_REQUEST['secrettoken']))
    die();

if (! isset($_REQUEST['a'])) {
?>
<p>
    <form action="hahaha.php" method="post">
        <input type="hidden" name="secrettoken" value="1" />
        <input type="hidden" name="a" value="newplayer" />
        <input name="name" />
        <input type="submit" value="new player" />
    </form>
</p>
<p>
    <form action="hahaha.php" method="post">
        <input type="hidden" name="secrettoken" value="1" />
        <input type="hidden" name="a" value="resetpass" />
        <input name="name" />
        <input type="submit" value="reset pass" />
    </form>
</p>
<?php
}
elseif ($_REQUEST['a'] == 'newplayer') {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $pass = '';
    for ($i = 0; $i < 16; $i++)
        $pass .= $chars[rand(0, strlen($chars) - 1)];

    $sql  = 'INSERT INTO players (name, password) VALUES ';
    $sql .= "('" . addslashes($_REQUEST['name']) . "', ";
    $sql .= "'" . hashpass($pass) . "')";

    $db = new PDO('sqlite:' . DB_NAME);
    $db->exec($sql);

    echo 'user ' . $_POST['name'] . ' registers. pass: ' . $pass;
}
?>
