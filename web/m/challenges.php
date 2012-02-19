<?php

if (! isset($_REQUEST['password'])) {
    $template->setvar('content', $template->parse('challenges'));
    echo $template->parse('global');
}

?>
