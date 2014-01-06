<?php
require_once "TP/class.TemplatePower.inc.php";
require_once "class/model/order/payment/esun.php"; 
require_once "conf/creditcard.php";
if($_POST){
    $card = new Model_Order_Payment_Esun($cms_cfg['creditcard'], $cms_cfg['esunkey'],$cms_cfg['exe_mode']);
    $card->checkout($_POST['orderid'], $_POST['price']);
}

