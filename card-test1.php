<?php
require_once "TP/class.TemplatePower.inc.php";
$tpl = new TemplatePower("test1.html");
$tpl->prepare();
$tpl->printToScreen();
