<?php
require_once "TP/class.TemplatePower.inc.php";
require_once "class/model/order/payment/esun.php"; 
require_once "conf/config.inc.php";
require_once "conf/creditcard.php";
require_once "conf/database.php";
include_once("libs/libs-mysql.php");
$db = new DB($cms_cfg['db_host'],$cms_cfg['db_user'],$cms_cfg['db_password'],$cms_cfg['db_name'],$cms_cfg['tb_prefix']);
$tpl = new TemplatePower("test3.html");
$tpl->prepare();
$card = new Model_Order_Payment_Esun($cms_cfg['creditcard'], $cms_cfg['esunkey'],$cms_cfg['exe_mode']);
$sql = $card->update_order($db,$_GET);
$tpl->gotoBlock("_ROOT");
$tpl->assign("UPDATE_ORDER_SQL",$sql);
foreach($_GET as $k=>$v){
    $tpl->assign("MSG_".strtoupper($k),$v);
    if($k=="RC"){
        $tpl->assign("MSG_".strtoupper($k)."_STR",  Model_Order_Payment_Returncode_Esun::$code[$_GET[$k]]);
    }
}
$tpl->printToScreen();


