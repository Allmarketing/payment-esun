<?php
require_once "returncode/esun.php";
class Model_Order_Payment_Esun {
    //put your code here
    protected $config;
    protected $mackey;
    protected $mode;
    protected $codedata = array();
    protected $url = array(
        'testing' => "https://acqtest.esunbank.com.tw/acq_online/online/sale42.htm",
        'running' => "https://acq.esunbank.com.tw/acq_online/online/sale42.htm",
    );
    protected $template = "templates/ws-cart-card-transmit-tpl.html";
    function __construct($config,$mackey,$mode="testing") {
        $this->config = $config;
        $this->mackey = $mackey;
        $this->mode = $mode;
        $this->codedata = array_merge($this->codedata,$this->config);
    }
    //結帳
    function checkout($o_id,$total_price,$extra_info=array()){
        $this->codedata['ono'] = strtoupper($o_id);
        $this->codedata['ta'] = $total_price;
        $this->codedata['u'] = "http://localhost/payment_esun/card-test3.php";//授權結果回傳接收頁
        if(!empty($extra_info)){
            foreach($extra_info as $k => $v){
                if(!isset($this->codedata[$k])){
                    $this->codedata[$k] = $v;
                }
            }
        }
        $tpl = new TemplatePower($this->template);
        $tpl->prepare();
        foreach($this->codedata as $k => $v){
            $tpl->newBlock("CARD_FIELD_LIST");
            $tpl->assign(array(
                "TAG_KEY"   => strtoupper($k),
                "TAG_VALUE" => $v,
            ));
        }
        $code = $this->make_code($this->codedata);
        $tpl->assignGlobal("TAG_INPUT_STR",$code[0]);
        $tpl->newBlock("CARD_FIELD_LIST");
        $tpl->assign(array(
            "TAG_KEY"   => "M",
            "TAG_VALUE" => $code[1]
        ));
        $tpl->assignGlobal("ESUN_AUTHORIZED_URL",$this->url[$this->mode]);
        $tpl->printToScreen();
    }
    //製作押碼
    function make_code($codedata){
        $input_str = implode("&",$codedata) . "&" . $this->mackey;
        return array($input_str,md5($input_str));
    }
    //更新訂單
    function update_order($db,$result){
        $oid = $result['ONO'];
        if($result['RC']=='00'){ //交易成功
            if($this->validate($result)){
                //更新訂單資料
                $sql = "update ".$db->prefix("order")." set some_col = 'somevalue' .... where o_id='".$oid."'";
            }else{
                throw new Exception("return result doesn't valiated!");
            }
        }else{
            //更新訂單狀態
            if($result['RC']!='G6'){ //錯誤原因非訂單編號重複
                $sql = "update ".$db->prefix("order")." set o_status='10' where o_id='".$oid."'";
            }
        }
         return $sql;
    }
    //驗證回傳結果
    function validate($result){
        $m = array_pop($result);
        $code = $this->make_code($result);
        return ($m==$code[1]);
    }
    
}
