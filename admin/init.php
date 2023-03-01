<?php

include 'conect.php';

$lang   = 'includes/languages';
$tpl    = 'includes/templates';
$func   = 'includes/functions';

include $func."/functions.php";
include $lang.'/english.php';
include $tpl."/header.php" ;

if(empty($noNavbar)){
include $tpl.'/navbar.php';
}
?>