<?php

define('DB_NAME', 'Oer3ajaPiz2IuCei.db');

function hashpass ($plaintext) {
    $rolling = '';
    for ($i = 0; $i < 16; $i++)
        $rolling = md5($rolling . $plaintext);
    return $rolling;
}

?>
