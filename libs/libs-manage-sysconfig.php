<?php
ob_start("ob_gzhandler");
include_once("../libs/libs-mysql.php");
include_once("../libs/libs-main.php");
include_once("../TP/class.TemplatePower.inc.php");
include_once("../lang/cht-utf8.php");
include_once("../conf/default-items.php");
include_once("../ali/ali_config.php");

$db = new DB($cms_cfg['db_host'],$cms_cfg['db_user'],$cms_cfg['db_password'],$cms_cfg['db_name']);
$mainfunc_class = class_exists("MAINFUNC_NEW")?"MAINFUNC_NEW":"MAINFUNC";
$main = new $mainfunc_class;

//取得網站的設定
$sql="select * from ".$cms_cfg['tb_prefix']."_system_config where sc_id='1'";
$selectrs = $db->query($sql);
$row = $db->fetch_array($selectrs,1);
$rsnum = $db->numRows($selectrs);
if($rsnum >0 ){
	foreach($row as $key => $value){
		$_SESSION[$cms_cfg['sess_cookie_name']][$key]=$value;
	}
}
//autoload class
require "../class/autoloader.php";
$autoloader = new autoloader();
spl_autoload_register(array($autoloader,"load"));
?>
