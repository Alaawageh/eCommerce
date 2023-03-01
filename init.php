<?php
//Error Report
ini_set('display_errors','On');
error_reporting(E_ALL);

include 'admin/conect.php';
$sessionUser = '';
if(isset($_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
}

$lang   = 'includes/languages';
$tpl    = 'includes/templates';
$func   = 'includes/functions';

include $func."/functions.php";
include $lang.'/english.php';
include $tpl."/header.php" ;


?>