<?php

define('DB_NAME', 'Oer3ajaPiz2IuCei.db');

function hashpass ($plaintext)
{
    $rolling = '';
    for ($i = 0; $i < 16; $i++)
        $rolling = md5($rolling . $plaintext);
    return $rolling;
}

function message ($title, $message, $back, $redirect)
{
    global $template;
    
    $template->setvar('message.title', $title);
    $template->setvar('message.message', $message);
    if ($back)
        $template->setvar('message.back', '<a href="javascript:history.go(-1)">Back</a>');
    else
        $template->setvar('message.back', '');
    if ($redirect)
        $template->setvar('message.redirect',
            'Click <a href="'.$redirect.'">here</a> to continue');
    else
        $template->setvar('message.redirect', '');
    echo $template->parse('message');
    die();
}

?>
