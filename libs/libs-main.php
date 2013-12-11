<?php
class MAINFUNC{
    //分頁
    function pagination($op_limit=10,$jp_limit=10,$nowp=1,$jp=0,$func_str,$total,$sql,$showNoData=true){
        $Page["total_records"]=$total;
        $Page["page_limit"] = $op_limit;
        //Total Pages
        $Page["total_pages"]=ceil($total/$op_limit);
        //New Sql
        $start_pages=($nowp>=1)?$nowp-1:0;
        $Page["start_serial"]=$start_pages*$op_limit;
        $ppages=ceil($Page["total_pages"]/$jp_limit);
        if($jp<$ppages-1){//非最後一頁跳頁
            $page_start=$jp*$jp_limit+1;
            $page_end=$page_start+$jp_limit;
        }else{//最後一頁跳頁
            $page_start=$jp*$jp_limit+1;
            $page_end=$page_start+($Page["total_pages"]-$page_start)+1;
        }
        //沒有上跳頁也沒有下跳頁
        if($ppages <= 1 && $Page["total_pages"]<$jp_limit+1){
            $Page["bj_page"]="";
            $Page["nj_page"]="";
        }else{
            //有上跳頁沒有下跳頁
            if($jp>= $ppages-1){ //最後下跳頁
                $bp=$jp-1;
                $prev=$page_start-1;
                $Page["bj_page"]=$func_str."&nowp=".$prev."&jp=".$bp;
                $Page["nj_page"]="";
            }elseif($jp < $ppages-1 && $jp!=0){//有上跳頁也有下跳頁           
                $bp=$jp-1;
                $np=$jp+1;
                $prev=$page_start-1;
                $Page["bj_page"]=$func_str."&nowp=".$prev."&jp=".$bp;
                $Page["nj_page"]=$func_str."&nowp=".$page_end."&jp=".$np;
            }elseif($jp ==0){////沒有上跳頁有下跳頁，第1頁
                $np=$jp+1;
                $Page["bj_page"]="";
                $Page["nj_page"]=$func_str."&nowp=".$page_end."&jp=".$np;
            }
        }
        //分頁選單PAGE_OPTION
        $nowp_option=array();
        for($i=$page_start;$i<$page_end;$i++){
            //$line1=($i==floor($page_end))?"":" | ";
            $nowp_option[] = ($i==$nowp || ($i==$page_start && $nowp==0))?"<span class='current'>".$i."</span>" : "<a href=\"".$func_str."&nowp=".$i."&jp=".$jp."\"> ".$i." </a>";
        }
        if($Page["total_pages"]>=2){
            $page_option=implode("&nbsp;|&nbsp;",$nowp_option);
        }else{
            $page_option="";
        }
        $Page["pages_str"]=$page_option;
        $Page['current_page_id'] = $nowp;
        if($ppages>1){
            if($jp>0)$Page["first_page"] = $func_str."&nowp=1&jp=0";
            if($jp<$ppages-1){
                $Page["last_page"] = $func_str."&nowp=".$Page["total_pages"]."&jp=".(ceil($Page["total_pages"]/$jp_limit)-1);
            }
        }
        $this->showPagination($Page,$showNoData);       
        $sql=$this->sqlstr_add_limit($op_limit,$nowp,$sql);
//        $sql = $this->sqlstr_add_limit($op_limit,$nowp,$sql);
        return $sql;
    }
    //SEO rewrite分頁
    function pagination_rewrite($op_limit=10,$jp_limit=10,$nowp=1,$jp=0,$func_str,$total,$sql,$showNoData=true){
        $nowp = $nowp?$nowp:1;
        $jp = $jp?$jp:0;
        $Page["total_records"]=$total;
        $Page["page_limit"] = $op_limit;
        //Total Pages
        $Page["total_pages"]=ceil($total/$op_limit);
        //New Sql
        $start_pages=($nowp>=1)?$nowp-1:0;
        $Page["start_serial"]=$start_pages*$op_limit;
        $ppages=ceil($Page["total_pages"]/$jp_limit);
        if($jp<$ppages-1){//非最後一頁跳頁
            $page_start=$jp*$jp_limit+1;
            $page_end=$page_start+$jp_limit;
        }else{//最後一頁跳頁
            $page_start=$jp*$jp_limit+1;
            $page_end=$page_start+($Page["total_pages"]-$page_start)+1;
        }
        //沒有上跳頁也沒有下跳頁
        if($ppages <= 1 && $Page["total_pages"]<$jp_limit+1){
            $Page["bj_page"]="";
            $Page["nj_page"]="";
        }else{
            //有上跳頁沒有下跳頁
            if($jp>= $ppages){ //最後下跳頁
                $bp=$jp-1;
                $prev=$page_start-1;
                $Page["bj_page"]=$func_str."-pages-".$prev."-".$bp.".htm";
                $Page["nj_page"]="";
            }
            //有上跳頁也有下跳頁
            if($jp < $ppages && $jp!=0){
                $bp=$jp-1;
                $np=$jp+1;
                $prev=$page_start-1;
                $Page["bj_page"]=$func_str."-pages-".$prev."-".$bp.".htm";
                $Page["nj_page"]=$func_str."-pages-".$page_end."-".$np.".htm";
            }
            //沒有上跳頁有下跳頁
            if($jp ==0){//第1頁
                $np=$jp+1;
                $Page["bj_page"]="";
                $Page["nj_page"]=$func_str."-pages-".$page_end."-".$np.".htm";
            }
        }
        //分頁選單PAGE_OPTION
        $nowp_option=array();
        for($i=$page_start;$i<$page_end;$i++){
            //$line1=($i==floor($page_end))?"":" | ";
            if($i==$nowp || ($i==$page_start && $nowp==0)){
                $nowp_option[] = "<span class='current'>".$i."</span>";
            }else{
                if($i==1){
                    $nowp_option[] = "<a href=\"".$func_str.".htm\"> ".$i." </a>";
                }else{
                    $nowp_option[] = "<a href=\"".$func_str."-pages-".$i."-".$jp.".htm\"> ".$i." </a>";
                }
            }
        }
        if($Page["total_pages"]>=2){
            $page_option=implode("&nbsp;|&nbsp;",$nowp_option);
        }else{
            $page_option="";
        }
        $Page["pages_str"]=$page_option;
        $Page['current_page_id'] = $nowp;
        $Page["total_pages"]=floor($Page["total_pages"]);
        if($ppages>1){
            if($jp>0)$Page["first_page"] = $func_str."&nowp=1&jp=0";
            if($jp<$ppages-1){
                $Page["last_page"] = $func_str."&nowp=".$Page["total_pages"]."&jp=".(ceil($Page["total_pages"]/$jp_limit)-1);
            }
        }        
        $this->showPagination($Page,$showNoData);      
        $sql=$this->sqlstr_add_limit($op_limit,$nowp,$sql);
//        $sql = $this->sqlstr_add_limit($op_limit,$nowp,$sql);
        return $sql;
    }
    function sqlstr_add_limit($op_limit=10,$nowp=1,$sql){
        $p=($nowp>=1)?$nowp-1:0;
        $start=$p*$op_limit;
        if(!empty($sql)){
            $sql .= " limit ".$start.",".$op_limit;
        }
        return $sql;
    }

    function count_total_records($sql){
        global $db;
        if(!empty($sql)){
            $sql = str_replace("*","count(*) as total_records",$sql);
            $selectrs = $db->query($sql);
            $row = $db->fetch_array($selectrs,1);
        }
        return $row["total_records"];
    }
    function load_js_msg(){
        global $tpl,$TPLMSG;
        $tpl->assignGlobal(array("JSMSG_PLEASE_INPUT" => $TPLMSG['JSMSG_PLEASE_INPUT'],
                                 "JSMSG_SUBJECT" => $TPLMSG['SUBJECT'],
                                 "JSMSG_ACCOUNT" => $TPLMSG['LOGIN_ACCOUNT'],
                                 "JSMSG_PASSWORD" => $TPLMSG['LOGIN_PASSWORD'],
                                 "JSMSG_NAME" => $TPLMSG['MEMBER_NAME'],
                                 "JSMSG_ADDRESS" => $TPLMSG['ADDRRESS'],
                                 "JSMSG_BIRTHDAY" => $TPLMSG['BIRTHDAY'],
                                 "JSMSG_TEL" => $TPLMSG['TEL'],
                                 "JSMSG_PASSWORD_ERROR" => $TPLMSG['JSMSG_VALID_PASSWORD_ERROR'],

        ));
    }
    //登入專區
    function login_zone(){
        global $tpl,$cms_cfg,$TPLMSG;
        if(empty($_SESSION[$cms_cfg['sess_cookie_name']]['MEMBER_ID'])){
            $tpl->newBlock( "LOGIN_ZONE" );
            $tpl->assignGlobal( "MSG_ERROR_MESSAGE",$_SESSION[$cms_cfg['sess_cookie_name']]["ERROR_MSG"]);
//            $_SESSION[$cms_cfg['sess_cookie_name']]["ERROR_MSG"]=""; //清空錯誤訊息
            $tpl->assignGlobal( "MSG_LOGIN_ACCOUNT",$TPLMSG["LOGIN_ACCOUNT"]);
            $tpl->assignGlobal( "MSG_LOGIN_PASSWORD",$TPLMSG["LOGIN_PASSWORD"]);
            $tpl->assignGlobal( "MSG_LOGIN_BUTTON",$TPLMSG["LOGIN_BUTTON"]);
            $tpl->assignGlobal( "MSG_LOGIN_FORGOT_PASSWORD",$TPLMSG["LOGIN_FORGOT_PASSWORD"]);
            $tpl->assignGlobal( "MSG_LOGIN_REGISTER",$TPLMSG["LOGIN_REGISTER"]);
            //載入驗証碼
            $this->security_zone($cms_cfg['security_image_width'],$cms_cfg['security_image_height']);
        }else{
            $tpl->newBlock( "MEMBER_INFO" );
            $tpl->assign("TAG_LOGIN_MEMBER_CATE",$_SESSION[$cms_cfg['sess_cookie_name']]['MEMBER_CATE']);
            $tpl->assign("TAG_LOGIN_MEMBER_NAME",$_SESSION[$cms_cfg['sess_cookie_name']]['MEMBER_NAME']);
            $tpl->assign("TAG_LOGIN_MEMBER_DATA",$TPLMSG['MEMBER_ZONE_DATA']);
            switch($_SESSION[$cms_cfg['sess_cookie_name']]['sc_cart_type']){
                case "0":
                    $tpl->newBlock("CART_TYPE_INQUIRY");
                    $tpl->assign("TAG_LOGIN_MEMBER_INQUIRY",$TPLMSG['MEMBER_ZONE_INQUIRY']);
                    $tpl->gotoBlock( "MEMBER_INFO" );
                    break;
                case "1":
                    $tpl->newBlock("CART_TYPE_ORDER");
                    $tpl->assign("TAG_LOGIN_MEMBER_ORDER",$TPLMSG['MEMBER_ZONE_ORDER']);
                    $tpl->gotoBlock( "MEMBER_INFO" );
                    break;
            }
            if($cms_cfg['ws_module']['ws_contactus']){
                $tpl->newBlock("MEMBER_CONTACTUS");
                $tpl->assign("TAG_LOGIN_MEMBER_CONTACTUS",$TPLMSG['MEMBER_ZONE_CONTACTUS']);
                $tpl->gotoBlock( "MEMBER_INFO" );
            }
            if($cms_cfg['ws_module']['ws_member_download']){
                $tpl->newBlock("MEMBER_DOWNLOAD");
                $tpl->assign("TAG_LOGIN_MEMBER_DOWNLOAD",$TPLMSG['DOWNLOAD']);
            }
        }
    }
    function security_zone($si_w="90", $si_h="25"){
        global $tpl,$cms_cfg,$TPLMSG;
        if($cms_cfg["ws_module"]["ws_security"]==1){
            //驗証碼
            require_once("libs-security-image.php");
            $si = new securityImage();
            $si->setImageSize($si_w, $si_h);
            $tpl->assignGlobal( "MSG_LOGIN_SECURITY",$TPLMSG["LOGIN_SECURITY"]);
            $tpl->assignGlobal( "TAG_INPUT_SECURITY",$si->showFormInput());
            $tpl->assignGlobal( "TAG_IMAGE_SECURITY_IMAGE",$si->showFormImage());
        }
    }
    //頭尾檔設定
    function header_footer($meta_array,$seo_h1=""){
        global $db,$tpl,$cms_cfg,$ws_array,$TPLMSG;
        static $e =0;//本方法的執行次數
        $e++;        
        if($cms_cfg["ws_module"]["ws_seo"] ==0 ){
            unset($meta_array);
            // IPB META SETUP
            $sql ="select sc_meta_title,sc_meta_keyword,sc_meta_description from ".$cms_cfg['tb_prefix']."_system_config where sc_status='1' and sc_id='1'";
            $selectrs = $db->query($sql);
            $rsnum = $db->numRows($selectrs);
            if($rsnum > 0) {
                $row = $db->fetch_array($selectrs,1);
                $tpl->assignGlobal(array(
                        "HEADER_META_TITLE" => $row["sc_meta_title"],
                        "HEADER_META_KEYWORD" => $row["sc_meta_keyword"],
                        "HEADER_META_DESCRIPTION" => $row["sc_meta_description"],
                        "TAG_MAIN_FUNC" => $seo_h1,
                ));
            }
        }else{
            //各項功能主頁專屬的seo 設定
            if(!is_array($meta_array)){
                //頭檔
                $meta_array=$this->func_metatitle($meta_array);
            }
            $tpl->assignGlobal(array("TAG_BASE_CSS" => $cms_cfg['base_css'],
                                     "HEADER_META_TITLE" => ($meta_array["meta_title"])?$meta_array["meta_title"]:$_SESSION[$cms_cfg['sess_cookie_name']]["sc_meta_title"],
                                     "HEADER_META_KEYWORD" => ($meta_array["meta_keyword"])?$meta_array["meta_keyword"]:$_SESSION[$cms_cfg['sess_cookie_name']]["sc_meta_keyword"],
                                     "HEADER_META_DESCRIPTION" => ($meta_array["meta_description"])?$meta_array["meta_description"]:$_SESSION[$cms_cfg['sess_cookie_name']]["sc_meta_description"],
                                     "HEADER_SHORT_DESC" => ($meta_array["seo_short_desc"])?$meta_array["seo_short_desc"]:"",
                                     "TAG_MAIN_FUNC" => ($meta_array["seo_h1"])?$meta_array["seo_h1"]:$seo_h1,
            ));
            if($meta_array["seo_short_desc"]){
            $tpl->newBlock("SEO_SHORT_DESC");
            $tpl->assign("VALUE_SEO_SHORT_DESC",$meta_array["seo_short_desc"]);
        }
        }
        if($e==1){  //第一次執行才做
            if($_SESSION[$cms_cfg['sess_cookie_name']]["sc_im_status"]==1 && $_SESSION[$cms_cfg['sess_cookie_name']]["sc_im_starttime"] < date("H:i:s") && $_SESSION[$cms_cfg['sess_cookie_name']]["sc_im_endtime"] > date("H:i:s")){
                $tpl->newBlock( "IM_ZONE" );
                $tpl->assign(array("VALUE_SC_IM_SKYPE" =>"skype:<a href=\"callto:".$_SESSION[$cms_cfg['sess_cookie_name']]["sc_im_skype"]."\"><img src=\"".$cms_cfg['base_images']."skype_call_me.png\" alt=\"Skype Me™!\" border='0' width='70' height='23'/></a>",
                                   "VALUE_SC_IM_MSN" =>"msn:".$_SESSION[$cms_cfg['sess_cookie_name']]["sc_im_msn"],
                ));
            }
            $tpl->assignGlobal("MSG_HOME",$TPLMSG['HOME']);
            $tpl->assignGlobal("TAG_THEME_PATH" , $cms_cfg['default_theme']);
            $tpl->assignGlobal("TAG_ROOT_PATH" , $cms_cfg['base_root']);
            $tpl->assignGlobal("TAG_FILE_ROOT" , $cms_cfg['file_root']);
            $tpl->assignGlobal("TAG_BASE_URL" ,$cms_cfg["base_url"]);
            $tpl->assignGlobal("TAG_LANG",$cms_cfg['language']);
            $tpl->assignGlobal("MSG_SITEMAP",$TPLMSG["SITEMAP"]);
            $tpl->assignGlobal("MSG_PRODUCT_SEARCH",$TPLMSG['PRODUCTS_SEARCH']);
            $tpl->assignGlobal("MSG_PRODUCT_SEARCH_KEYWORD",$TPLMSG['ENTER_KEYWORD']);
            //設定主選單變數
            if(!empty($ws_array["main"])){
                foreach($ws_array["main"] as $item => $itemName){
                    $tpl->assignGlobal("TAG_MENU_".  strtoupper($item),  $itemName);
                }
            }
            //設定頁腳變數
            $tpl->assignGlobal("TAG_FOOTER_ADDRESS",$TPLMSG['COMPANY_ADDRESS']);
            $tpl->assignGlobal("TAG_FOOTER_FAX",$TPLMSG['FAX']);
            $tpl->assignGlobal("TAG_FOOTER_TEL",$TPLMSG['TEL']);
            $tpl->assignGlobal("TAG_FOOTER_EMAIL",$TPLMSG['EMAIL']);
            //有會員即顯示會員登入區
            if($cms_cfg["ws_module"]["ws_member"]==1){
                $this->login_zone();
            }
            $this->mouse_disable(); //鎖滑鼠右鍵功能
            $this->clearfield(); //搜尋區塊, 投入true值啟用autocomplete 
            //下拉式選單
            /*參數說明
             * 第一個參數型態是字串，可輸入aboutus,products,news，輸入多個項目時用半型逗號區隔
             * 第二個參數型態是陣列，設定於config.inc.php裡，為自訂下拉式選單項目，格式於config.inc.php有範例
             * $cms_cfg['extra_dd_menu']陣列索引是下拉選單div的id名稱，完整的div id名稱是dd_[div名稱]，不包含[]
             */            
            $this->dropdown_menu(null,$cms_cfg['extra_dd_menu']);
            //$this->float_menu();
            //$this->goodlink_select();
            //尾檔
            //$tpl->assignGlobal("VALUE_SC_FOOTER" ,$_SESSION[$cms_cfg['sess_cookie_name']]["sc_footer"]);
        }
    }
    function func_metatitle($func){
        global $db,$cms_cfg;
        $sql="select * from ".$cms_cfg['tb_prefix']."_metatitle where mt_name='".$func."'";
        $selectrs = $db->query($sql);
        $rsnum    = $db->numRows($selectrs);
        $meta_array=array();
        if($rsnum >0){
            $row = $db->fetch_array($selectrs,1);
            $meta_array["meta_title"]=$row["mt_seo_title"];
            $meta_array["meta_keyword"]=$row["mt_seo_keyword"];
            $meta_array["meta_description"]=$row["mt_seo_description"];
            $meta_array["seo_short_desc"]=$row["mt_seo_short_desc"];
            $meta_array["seo_h1"]=$row["mt_seo_h1"];
        }
        return $meta_array;
    }
    //固定顯示主分類及次分類的左方menu
    function left_fix_cate_list(){
        global $tpl,$db,$main,$cms_cfg,$TPLMSG;
        $tpl->assignGlobal("LEFT_CATE_TITLE_IMG",$cms_cfg['base_images']."left-title-products.png");
        //判斷是否顯示主分類
        if($cms_cfg["ws_module"]["ws_left_main_pc"]==1) {
            $sql="select * from ".$cms_cfg['tb_prefix']."_products_cate where pc_parent='0' and pc_status='1' order by pc_up_sort desc,pc_sort ".$cms_cfg['sort_pos']." ";
            $selectrs = $db->query($sql);
            $rsnum    = $db->numRows($selectrs);
        }else{
            $rsnum = 0;
        }
        if($rsnum > 0 ){ //有主分類
            //有次分類或主分類產品
            if($cms_cfg["ws_module"]["ws_left_menu_effects"]==1) {
                $tpl->newBlock("JS_LEFT_MENU");
                switch($cms_cfg["ws_module"]['ws_left_menu_type']){
                    case 1:
                        $tpl->newBlock("CLICK_MODE");
                        break;
                    case 0:
                        $tpl->newBlock("OVER_MODE");
                        break;
                }
            }
            $i=0;
            while($row = $db->fetch_array($selectrs,1)){
                if($cms_cfg['ws_module']['ws_seo']==1){
                    if(trim($row["pc_seo_filename"]) !=""){
                        $dirname1=$row["pc_seo_filename"];
                        $pc_link=$cms_cfg["base_root"].$row["pc_seo_filename"].".htm";
                    }else{
                        $dirname1=$row["pc_id"];
                        $pc_link=$cms_cfg["base_root"]."category-".$row["pc_id"].".htm";
                    }
                }else{
                    $pc_link=$cms_cfg["base_root"]."products.php?func=p_list&pc_parent=".$row["pc_id"];
                }
                $tpl->newBlock( "LEFT_CATE_LIST" );
                $tpl->assign( array( "VALUE_CATE_NAME"       => $row["pc_name"],
                                     "VALUE_CATE_LINK"       => $pc_link,
                                     "VALUE_CATE_LINK_CLASS" => (($_REQUEST['pc_parent']==$row['pc_id'] || ($cms_cfg['ws_module']['ws_seo']==1 && $_REQUEST['f']==$row['pc_seo_filename']))?"current":""),
                                     "TAG_CURRENT_CLASS"     => ($_REQUEST['pc_parent']==$row['pc_id'] || ($cms_cfg['ws_module']['ws_seo']==1 && $_REQUEST['f']==$row['pc_seo_filename']))?"class='current'":"",
                ));
                //左方產品次分類為click menu
                if($cms_cfg['ws_module']['ws_seo']==1){
                    if($_REQUEST["d"] || $_REQUEST["f"]) {
                        if($row["pc_seo_filename"]==$_REQUEST["d"] || $row["pc_seo_filename"]==$_REQUEST["f"]){
                            if($cms_cfg["ws_module"]["ws_left_menu_type"]==1  ) {
                                $tpl->assignGlobal("CLICK_NUM1", $i);
                            }else{
                                $tpl->assignGlobal("OVER_NUM1", $i);
                            }
                        }
                    }else{
                        if($cms_cfg["ws_module"]["ws_left_menu_effects"]==1 && $row["pc_id"]==$_REQUEST["pc_parent"]) {
                            if($cms_cfg["ws_module"]["ws_left_menu_type"]==1  ) {
                                $tpl->assignGlobal("CLICK_NUM1", $i);
                            }else{
                                $tpl->assignGlobal("OVER_NUM1", $i);
                            }
                        }
                    }
                }else{
                    if($cms_cfg["ws_module"]["ws_left_menu_effects"]==1 && $row["pc_id"]==$_REQUEST["pc_parent"]) {
                        if($cms_cfg["ws_module"]["ws_left_menu_type"]==1  ) {
                            $tpl->assignGlobal("CLICK_NUM1", $i);
                        }else{
                            $tpl->assignGlobal("OVER_NUM1", $i);
                        }
                    }
                }
                //判斷是否顯示次分類
                if($cms_cfg["ws_module"]["ws_left_sub_pc"]==1){
                    $sql1="select * from ".$cms_cfg['tb_prefix']."_products_cate where pc_parent='".$row["pc_id"]."' and pc_status='1' order by pc_up_sort desc,pc_sort ".$cms_cfg['sort_pos']." ";
                    $selectrs1 = $db->query($sql1);
                    $rsnum1    = $db->numRows($selectrs1);
                }else{
                    $rsnum1 = 0;
                }
                if($rsnum1 > 0 ){ //有次分類
                    if($cms_cfg["ws_module"]["ws_left_menu_type"]==1) {
                        $tpl->assignGlobal("TAG_LEFT_MENU_TYPE", "id=\"firstpane\""); //click menu
                    }else{
                        $tpl->assignGlobal("TAG_LEFT_MENU_TYPE", "id=\"secondpane\""); //over menu
                    }
                    while($row1 = $db->fetch_array($selectrs1,1)){
                        if($cms_cfg['ws_module']['ws_seo']==1){
                            if(trim($row1["pc_seo_filename"]) !=""){
                                $dirname1=$row1["pc_seo_filename"];
                                $pc_link1=$cms_cfg["base_root"].$row1["pc_seo_filename"].".htm";
                            }else{
                                $dirname1=$row1["pc_id"];
                                $pc_link1=$cms_cfg["base_root"]."category-".$row1["pc_id"].".htm";
                            }
                        }else{
                            $pc_link1=$cms_cfg["base_root"]."products.php?func=p_list&pc_parent=".$row1["pc_id"];
                        }
                        $tpl->newBlock("LEFT_SUBCATE_LIST");
                        $tpl->assign( array( "VALUE_SUBCATE_NAME" => $row1["pc_name"],
                                             "VALUE_SUBCATE_LINK" => $pc_link1,
                                             "TAG_CURRENT_CLASS"  => ($row1["pc_seo_filename"]==$_REQUEST["d"] || $row1["pc_seo_filename"]==$_REQUEST["f"] || $_REQUEST['pc_parent']==$row1['pc_id'])?"class='current'":"",
                        ));
                        //左方產品次分類為click menu
                        if($cms_cfg['ws_module']['ws_seo']==1){
                            if($_REQUEST["d"] || $_REQUEST["f"]) {
                                if($row1["pc_seo_filename"]==$_REQUEST["d"] || $row1["pc_seo_filename"]==$_REQUEST["f"]){
                                    if($cms_cfg["ws_module"]["ws_left_menu_type"]==1 ) {
                                        $tpl->assignGlobal("CLICK_NUM1", $i);
                                    }else{
                                        $tpl->assignGlobal("OVER_NUM1", $i);
                                    }
                                }
                            }else{
                                if($cms_cfg["ws_module"]["ws_left_menu_type"]==1 && $row1["pc_id"]==$_REQUEST["pc_parent"]) {
                                    $tpl->assignGlobal("CLICK_NUM1", $i);
                                }
                            }
                        }else{
                            if($cms_cfg["ws_module"]["ws_left_menu_effects"]==1 && $row1["pc_id"]==$_REQUEST["pc_parent"]){
                                if($cms_cfg["ws_module"]["ws_left_menu_type"]==1) {
                                    $tpl->assignGlobal("CLICK_NUM1", $i);
                                }else{
                                    $tpl->assignGlobal("OVER_NUM1", $i);
                                }
                            }
                        }
                    }
                    $tpl->gotoBlock("LEFT_CATE_LIST");
                    $tpl->assign("TAG_SUB_UL1","<div class=\"menu_body\"><ul>");
                    $tpl->assign("TAG_SUB_UL2","</ul></div>");
                    $tpl->assign("VALUE_CATE_LINK_CLASS" ,$_REQUEST['pc_parent']==$row['pc_id']?"current":"");
                }else{ //無次分類
                    //判斷是否顯示次分類的產品
                    if($cms_cfg["ws_module"]["ws_left_products"]==1){
                        $sql2="select * from ".$cms_cfg['tb_prefix']."_products where pc_id='".$row["pc_id"]."' and p_status='1' order by p_up_sort desc,p_sort ".$cms_cfg['sort_pos']." ";
                        $selectrs2 = $db->query($sql2);
                        $rsnum2    = $db->numRows($selectrs2);
                    }else{
                        $rsnum2 = 0;
                    }
                    if($rsnum2 > 0 ){ //有次分類產品
                        if($cms_cfg["ws_module"]["ws_left_menu_type"]==1) {
                            $tpl->assignGlobal("TAG_LEFT_MENU_TYPE", "id=\"firstpane\""); //click menu
                        }else{
                            $tpl->assignGlobal("TAG_LEFT_MENU_TYPE", "id=\"secondpane\""); //over menu
                        }
                        while($row2 = $db->fetch_array($selectrs2,1)){
                            if($cms_cfg['ws_module']['ws_seo']==1){
                                if(trim($row2["p_seo_filename"]) !=""){
                                    $p_link=$cms_cfg["base_root"].$dirname1."/".$row2["p_seo_filename"].".html";
                                }else{
                                    $p_link=$cms_cfg["base_root"].$dirname1."/products-".$row2["p_id"]."-".$row2["pc_id"].".html";
                                }
                            }else{
                                $p_link=$cms_cfg["base_root"]."products.php?func=p_detail&p_id=".$row2["p_id"]."&pc_parent=".$row2["pc_id"];
                            }
                            $tpl->newBlock( "LEFT_SUBCATE_LIST" );
                            $tpl->assign( array( "VALUE_SUBCATE_NAME" => $row2["p_name"],
                                                 "VALUE_SUBCATE_LINK"  => $p_link,
                            ));
                            //左方產品次分類為click menu
                            if($cms_cfg['ws_module']['ws_seo']==1){
                                if($_REQUEST["f"]!="") {
                                    if($cms_cfg["ws_module"]["ws_left_menu_type"]==1 && $row2["p_seo_filename"]==$_REQUEST["f"]) {
                                        $tpl->assignGlobal("CLICK_NUM1", $i);
                                    }
                                }else{
                                    if($cms_cfg["ws_module"]["ws_left_menu_type"]==1 && $row2["pc_id"]==$_REQUEST["pc_parent"]) {
                                        $tpl->assignGlobal("CLICK_NUM1", $i);
                                    }
                                }
                            }else{
                                if($cms_cfg["ws_module"]["ws_left_menu_type"]==1 && $row2["pc_id"]==$_REQUEST["pc_parent"]) {
                                    $tpl->assignGlobal("CLICK_NUM1", $i);
                                }
                            }
                        }
                        $tpl->gotoBlock("LEFT_CATE_LIST");
                        $tpl->assign("TAG_SUB_UL1","<div class=\"menu_body\"><ul>");
                        $tpl->assign("TAG_SUB_UL2","</ul></div>");
                        $tpl->assign("VALUE_CATE_LINK_CLASS" ,$_REQUEST['pc_parent']==$row2['pc_id']?"current":"");
                    }
                }
                $i++;
            }
        }else{//無主分類,顯示未分類產品
            if($cms_cfg["ws_module"]["ws_left_products"]==1){
                $sql3="select * from ".$cms_cfg['tb_prefix']."_products where pc_id='0' and p_status='1' order by p_up_sort desc,p_sort ".$cms_cfg['sort_pos']." ";
                $selectrs3 = $db->query($sql3);
                $rsnum3    = $db->numRows($selectrs3);
            }else{
                $rsnum3 = 0;
            }
            if($rsnum3 > 0 ){
                //顯示左方次分類
                while($row3 = $db->fetch_array($selectrs3,1)){
                    if($cms_cfg['ws_module']['ws_seo']==1){
                        if(trim($row3["p_seo_filename"]) !=""){
                            $p_link=$cms_cfg["base_root"]."products/".$row3["p_seo_filename"].".html"; //未分類產品資料夾預設為products
                        }else{
                            $p_link=$cms_cfg["base_root"]."products/products-".$row3["p_id"]."-".$row3["pc_id"].".html";//未分類產品資料夾預設為products
                        }
                    }else{
                        $p_link=$cms_cfg["base_root"]."products.php?func=p_detail&p_id=".$row3["p_id"]."&pc_parent=".$row3["pc_id"];
                    }
                    $tpl->newBlock("LEFT_PRODUCTS_LIST");
                    $tpl->assign( array( "VALUE_PRODUCTS_NAME" => $row3["p_name"],
                                         "VALUE_PRODUCTS_LINK"  => $p_link,
                                         "VALUE_CATE_LINK_CLASS" => (($_REQUEST['p_id']==$row3['p_id'] || $_GET['f']==$row3['p_seo_filename'])?"class='current'":""),
                    ));
                }
            }
        }
    }
    //後台管理權限
    function mamage_authority(){
        global $tpl,$ws_array,$cms_cfg;
        $tpl->assignGlobal(array("TAG_LANG_VERSION" => $ws_array["lang_version"][$cms_cfg['language']],
                                 "TAG_USER_NAME"   => $_SESSION[$cms_cfg['sess_cookie_name']]["USER_NAME"],
                                 "TAG_USER_ACCOUNT"   => $_SESSION[$cms_cfg['sess_cookie_name']]["USER_ACCOUNT"]
                          ));
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_aboutus"] && $cms_cfg["ws_module"]["ws_aboutus"])?$tpl->newBlock( "AUTHORITY_ABOUTUS" ):"";
        ($cms_cfg["ws_module"]["ws_video"])?$tpl->newBlock( "AUTHORITY_VIDEO" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_ad"]  && $cms_cfg["ws_module"]["ws_ad"])?$tpl->newBlock( "AUTHORITY_AD" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_admin"])?$tpl->newBlock( "AUTHORITY_ADMIN" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_blog"] && $cms_cfg["ws_module"]["ws_blog"])?$tpl->newBlock( "AUTHORITY_BLOG" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_bonus"] && $cms_cfg["ws_module"]["ws_bonus"])?$tpl->newBlock( "AUTHORITY_BONUS" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_contactus"] && $cms_cfg["ws_module"]["ws_contactus"])?$tpl->newBlock( "AUTHORITY_CONTACTUS" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_download"] && $cms_cfg["ws_module"]["ws_download"])?$tpl->newBlock( "AUTHORITY_DOWNLOAD" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_ebook"] && $cms_cfg["ws_module"]["ws_ebook"])?$tpl->newBlock( "AUTHORITY_EBOOK" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_epaper"] && $cms_cfg["ws_module"]["ws_epaper"])?$tpl->newBlock( "AUTHORITY_EPAPER" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_faq"] && $cms_cfg["ws_module"]["ws_faq"])?$tpl->newBlock( "AUTHORITY_FAQ" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_forum"] && $cms_cfg["ws_module"]["ws_forum"])?$tpl->newBlock( "AUTHORITY_FORUM" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_gallery"] && $cms_cfg["ws_module"]["ws_gallery"])?$tpl->newBlock( "AUTHORITY_GALLERY" ):"";
        if(!$cms_cfg['ws_module']['ws_gallery_scan_dir'])$tpl->newBlock("GALLERY_ITEM"); //非批次上傳圖片才顯示活動花絮項目管理
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_goodlink"] && $cms_cfg["ws_module"]["ws_goodlink"])?$tpl->newBlock( "AUTHORITY_GOODLINK" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_guestbook"] && $cms_cfg["ws_module"]["ws_guestbook"])?$tpl->newBlock( "AUTHORITY_GUESTBOOK" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_inquiry"] && $cms_cfg["ws_module"]["ws_inquiry"])?$tpl->newBlock( "AUTHORITY_INQUIRY" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_member"] && $cms_cfg["ws_module"]["ws_member"])?$tpl->newBlock( "AUTHORITY_MEMBER" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_member"] && $cms_cfg["ws_module"]["ws_member_manipulate"])?$tpl->newBlock( "AUTHORITY_MEMBER_MANIUPULATE" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_news"] && $cms_cfg["ws_module"]["ws_news"])?$tpl->newBlock( "AUTHORITY_NEWS" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_order"] && $cms_cfg["ws_module"]["ws_order"])?$tpl->newBlock( "AUTHORITY_ORDER" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_products"] && $cms_cfg["ws_module"]["ws_products"])?$tpl->newBlock( "AUTHORITY_PRODUCTS" ):"";
        if($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_products_cate"] && $cms_cfg["ws_module"]["ws_products"]){
            $tpl->newBlock("AUTHORITY_PRODUCTS_CATE");
            $tpl->gotoBlock( "AUTHORITY_PRODUCTS" );
        }
        if($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_products"] && $cms_cfg["ws_module"]["ws_new_product"]){
            $tpl->newBlock("AUTHORITY_NEW_PRODUCTS");
            $tpl->gotoBlock( "AUTHORITY_PRODUCTS" );
        }
        if($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_products"] && $cms_cfg["ws_module"]["ws_products_ca"]){
            $tpl->newBlock("AUTHORITY_CERTIFICATE_AUTHEN");
        }        
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_systool"] && $cms_cfg["ws_module"]["ws_systool"])?$tpl->newBlock( "AUTHORITY_SYSTOOL" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_sysconfig"]  && $cms_cfg["ws_module"]["ws_sysconfig"])?$tpl->newBlock( "AUTHORITY_SYSCONFIG" ):"";
        ($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_seo"] && $cms_cfg["ws_module"]["ws_seo"])?$tpl->newBlock( "AUTHORITY_SEO" ):"";
        if($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_seo"] && $_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_google_sitemap"] && $cms_cfg["ws_module"]["ws_seo"]){
            $tpl->newBlock( "AUTHORITY_GOOGLE_SITEMAP" );
            $tpl->gotoBlock( "AUTHORITY_SEO" );
        }
        if($_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_seo"] && $_SESSION[$cms_cfg['sess_cookie_name']]["AUTHORITY"]["aa_google_analytics"] && $cms_cfg["ws_module"]["ws_seo"]){
            $tpl->newBlock( "AUTHORITY_GOOGLE_ANALYTICS" );
            $tpl->gotoBlock( "AUTHORITY_SEO" );
        }
        ($cms_cfg["ws_module"]["ws_statistic"])?$tpl->newBlock( "AUTHORITY_STATISTIC" ):"";
        ($cms_cfg["ws_module"]["ws_service"])?$tpl->newBlock( "AUTHORITY_SERVICE" ):"";
        ($cms_cfg["ws_module"]["ws_stores"])?$tpl->newBlock( "AUTHORITY_STORES" ):"";
        ($cms_cfg["ws_module"]["ws_index_banner"])?$tpl->newBlock( "INDEX_BANNER" ):"";//自訂首頁banner管理
        ($cms_cfg["ws_module"]["ws_products_application"])?$tpl->newBlock( "AUTHORITY_PRODUCTS_APPLICATION" ):"";//產品應用領域
        $tpl->assignGlobal("TAG_ROOT_PATH" , $cms_cfg['base_root']);
        $tpl->assignGlobal("TAG_FILE_ROOT" , $cms_cfg['file_root']);
    }
    //取得分類層次列====================================================================
    function get_layer($tablename,$show_fieldname,$id_str,$id,$func_str,$last_link=0){
        global $db;
        $id_parent=$id_str."_parent";
        $id_fieldname=$id_str."_id";
        $k=1;
        $j=0;
        $Layer=array();
        while($k==1){
            $sql="select ".$show_fieldname." , ".$id_parent." , ".$id_fieldname." from ".$tablename." where ".$id_fieldname."='".$id."'";
            $selrs = $db->query($sql);
            $row = $db->fetch_array($selrs,1);
            $id =  $row[$id_parent];
            if($row[$id_fieldname]==""){
                $k=0;
            }else{
                if($j==0 && $last_link==0){
                    $Layer[$j] =$row[$show_fieldname];
                }elseif($last_link==2){
                    $Layer[$j] =$row[$show_fieldname];
                }else{
                    $Layer[$j] = $this->mk_link($row[$show_fieldname], $func_str."&".$id_parent."=".$row[$id_fieldname]);;
                }
            }
            unset($row);
            $j++;
        }
        //陣列反轉
        if(!empty($Layer)){
            $Layer=array_reverse($Layer);
        }
        //$Layer=$this->replace_for_mod_rewrite($Layer);
        return $Layer;
    }
    //取得分類層次列====================================================================
    function get_layer_rewrite($tablename,$show_fieldname,$id_str,$id,$func_str,$last_link=0){
        global $cms_cfg,$db;
        $id_parent=$id_str."_parent";
        $id_fieldname=$id_str."_id";
        $k=1;
        $j=0;
        $Layer=array();
        while($k==1){
            $sql="select ".$show_fieldname." ,pc_seo_filename   , ".$id_parent." , ".$id_fieldname." from ".$tablename." where ".$id_fieldname."='".$id."'";
            $selrs = $db->query($sql);
            $row = $db->fetch_array($selrs,1);
            $id =  $row[$id_parent];
            if($row[$id_fieldname]==""){
                $k=0;
            }else{
                if($j==0 && $last_link==0){
                    $Layer[$j] = $this->mk_link($row[$show_fieldname], "javascript:avoid(0)");
                }elseif($last_link==2){
                    $Layer[$j] = $this->mk_link($row[$show_fieldname],"javascript:avoid(0)");
                }else{
                    if(trim($row["pc_seo_filename"]) !=""){
                        $pc_link=$cms_cfg["base_root"].$row["pc_seo_filename"].".htm";
                    }else{
                        $pc_link=$cms_cfg["base_root"]."category-".$row["pc_id"].".htm";
                    }
                    $Layer[$j] = $this->mk_link($row[$show_fieldname],$pc_link);
                }
            }
            unset($row);
            $j++;
        }
        //陣列反轉
        if(!empty($Layer)){
            $Layer=array_reverse($Layer);
        }
        //$Layer=$this->replace_for_mod_rewrite($Layer);
        return $Layer;
    }
    //寄送確認信,電子報
    function ws_mail_send($from,$to,$mail_content,$mail_subject,$mail_type,$goto_url,$admin_subject=null,$none_header=0){
        global $TPLMSG,$cms_cfg;
        if($mail_type =="epaper"){
            set_time_limit(0);
        }
        $from_email=explode(",",$from);
        $from_name=(trim($_SESSION[$cms_cfg['sess_cookie_name']]["sc_company"]))?$_SESSION[$cms_cfg['sess_cookie_name']]["sc_company"]:$from_email[0];
        $mail_subject = "=?UTF-8?B?".base64_encode($mail_subject)."?=";
        //寄給送信者
        $MAIL_HEADER   = "MIME-Version: 1.0\n";
        $MAIL_HEADER  .= "Content-Type: text/html; charset=\"utf-8\"\n";
        $MAIL_HEADER  .= "From: =?UTF-8?B?".base64_encode($from_name)."?= <".$from_email[0].">"."\n";
        $MAIL_HEADER  .= "Reply-To: ".$from_email[0]."\n";
        $MAIL_HEADER  .= "Return-Path: ".$from_email[0]."\n";    // these two to set reply address
        $MAIL_HEADER  .= "X-Priority: 1\n";
        $MAIL_HEADER  .= "Message-ID: <".time()."-".$from_email[0].">\n";
        $MAIL_HEADER  .= "X-Mailer: PHP v".phpversion()."\n";          // These two to help avoid spam-filters
        $to_email = explode(",",$to);
        for($i=0;$i<count($to_email);$i++){
            if($i!=0 && $i%2==0){
                sleep(2);
            }
            if($i!=0 && $i%5==0){
                sleep(10);
            }
            if($i!=0 && $i%60==0){
                sleep(300);
            }
            if($i!=0 && $i%600==0){
                sleep(2000);
            }
            if($i!=0 && $i%1000==0){
                sleep(10000);
            }
            @mail($to_email[$i], $mail_subject, $mail_content,$MAIL_HEADER);
        }
        //除了電子報、忘記密碼外寄給管理者
        if($mail_type !="epaper" && $mail_type!="pw"){
            $MAIL_HEADER   = "MIME-Version: 1.0\n";
            $MAIL_HEADER  .= "Content-Type: text/html; charset=\"utf-8\"\n";
            $MAIL_HEADER  .= "From: =?UTF-8?B?".base64_encode($to_email[0])."?= <".$to_email[0].">"."\n";
            $MAIL_HEADER  .= "Reply-To: ".$to_email[0]."\n";
            $MAIL_HEADER  .= "Return-Path: ".$to_email[0]."\n";    // these two to set reply address
            $MAIL_HEADER  .= "X-Priority: 1\n";
            $MAIL_HEADER  .= "Message-ID: <".time()."-".$to_email[0].">\n";
            $MAIL_HEADER  .= "X-Mailer: PHP v".phpversion()."\n";          // These two to help avoid spam-filters
            if($admin_subject){
                $mail_subject = $admin_subject;
            }else{
                $mail_subject .= " from ".$_SERVER["HTTP_HOST"]."--[For Administrator]";
            }
            $mail_content = preg_replace("#<span class=\"not_for_admin\">.+</span>#", "******", $mail_content);
            for($i=0;$i<count($from_email);$i++){
                @mail($from_email[$i], $mail_subject, $mail_content,$MAIL_HEADER);
            }
        }

        if(empty($none_header)){
            $goto_url=(empty($goto_url))?$cms_cfg["base_url"]:$goto_url;
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
            echo "<script language=javascript>";
            echo "Javascript:alert('".$TPLMSG['ACTION_TERM_JS']."')";
            echo "</script>";
            echo "<script language=javascript>";
            echo "document.location='".$goto_url."'";
            echo "</script>";
        }
    }
    function ws_mail_send_simple($from,$to,$mail_content,$mail_subject,$from_name=""){
        global $TPLMSG,$cms_cfg;
        $from_email=explode(",",$from);
        $from_name=(trim($from_name))?$from_name:$from_email[0];
        $mail_subject = "=?UTF-8?B?".base64_encode($mail_subject)."?=";
        //寄給送信者
        $MAIL_HEADER   = "MIME-Version: 1.0\n";
        $MAIL_HEADER  .= "Content-Type: text/html; charset=\"utf-8\"\n";
        $MAIL_HEADER  .= "From: =?UTF-8?B?".base64_encode($from_name)."?= <".$from_email[0].">"."\n";
        $MAIL_HEADER  .= "Reply-To: ".$from_email[0]."\n";
        $MAIL_HEADER  .= "Return-Path: ".$from_email[0]."\n";    // these two to set reply address
        $MAIL_HEADER  .= "X-Priority: 1\n";
        $MAIL_HEADER  .= "Message-ID: <".time()."-".$from_email[0].">\n";
        $MAIL_HEADER  .= "X-Mailer: PHP v".phpversion()."\n";          // These two to help avoid spam-filters
        $to_email = explode(",",$to);
        for($i=0;$i<count($to_email);$i++){
            if($i!=0 && $i%2==0){
                sleep(2);
            }
            if($i!=0 && $i%5==0){
                sleep(10);
            }
            if($i!=0 && $i%60==0){
                sleep(300);
            }
            if($i!=0 && $i%600==0){
                sleep(2000);
            }
            if($i!=0 && $i%1000==0){
                sleep(10000);
            }
            @mail($to_email[$i], $mail_subject, $mail_content,$MAIL_HEADER);
        }
    }    
    function CreateLayer($id,$relative,$top,$left,$width,$height,$css,$bgColor,$bgImage,$visible,$zIndex,$html,$events){
        $src = "";
        $src.="<div ";
        $src.=$this->Param("id",$id,"=","\""," ");
        $src.=$this->Param("class",$css,"=","\""," ");
        $style="";
        $style.=$this->Param("position",$relative?"relative":"absolute",":","",";");
        $style.=$this->Param("overflow","hidden",":","",";");
        $style.=$this->Param("visibility",$visible==true?"visible":"hidden",":","",";");
        $style.=$this->Param("display",$visible==true?"block":"none",":","",";");
        $style.=$this->Param("top","0",":","",";");
        $style.=$this->Param("left","0",":","",";");
        $style.=$this->Param("width",$width,":","",";");
        $style.=$this->Param("height",$height,":","",";");
        $style.=$this->Param("z-index",$zIndex,":","",";");
        $style.=$this->Param("background-color",$bgColor,":","",";");
        $style.=$this->Param("background-image",$bgImage,":","",";");


        // Do we need clip?
        //$style.=$this->Param("clip","rect(0,".$width.",".$height.",0)",":","",";");

        // Add events

        $src.=$this->Param("style",$style,"=","\"","");
        $src.=">";
        $src.=$html;
        $src.="</div>\n";

        return($src);
    }
    function CreateItem($id,$text,$url,$target,$css,$subitems,$level,$pc_parent,$tree_type="normal",$id_array){
        $img_item = "images/ws-text-file.gif";
        $img_dir_close = "images/fc.gif";
        $image = "\"".($subitems==""?$img_item:$img_dir_close)."\"";
        $imgtag = "<img border=0 id=\"".$id."_codethat_image\" src=".$image.">";
        if($tree_type=="checkbox"){
            if (in_array($id, $id_array)){
                $checked_str="checked";
            }
            $imgtag = "<input type='checkbox' name=related_id[] value='".$id."' ".$checked_str.">".$imgtag;
        }
        $td="";
        for($i=0;$i<$level;$i++)
            $td.="<td width=20px></td>";
        $atag = "href=\"".$url."\" ".($target==""?"":("target=\"".$target."\""));
        if($subitems=="")
            $html="<table cellpadding=0 cellspacing=0 border=0><tr>".$td."<td><a ".$atag.">".$imgtag."</a></td><td align=left><a ".$atag." class=".$css."><p class=".$css.">".$text."</p></a></td></tr></table></a>";
        else
            $html="<table cellpadding=0 cellspacing=0 border=0><tr>".$td."<td><a href=\"javascript:toggleNode('".$id."');\">".$imgtag."</a></td><td align=left><a ".$atag." onClick=\"toggleNode('".$id."');\" class=".$css."><p class=".$css.">".$text."</p></a></td></tr></table></a>";
        // We create item as one main layer
        $src=$this->CreateLayer(
                    $id,  // Id
                    true, // Relative
                    "",   // Top
                    "",   // Left
                    "",   // Width
                    "",   // Height
                    "",   // Css class
                    "",   // Background color
                    "",   // URL of background image
                    true, // Is it visible?
                    1,    // Z index
                    $html,// HTML text
                    ""    // Events
                    );
        if($subitems!="")
            $TF=($id==$pc_parent)?false:true;
            $src.=$this->CreateLayer($id."_codethat_subitems",true,"","","","","","","",$TF,1,$subitems,"");
        return($src);
    }
    function Preface(){
        $img_dir_open = "images/fe.gif";
        $img_dir_close = "images/fc.gif";
        $img_item = "images/ws-text-icon.gif";
            $str ="<script language='javascript'>\r\n";
        $str.="var ct_image_dir = new Image();ct_image_dir.src=\"".$img_dir_open."\";\r\n";
        $str.="var ct_image_diropen = new Image();ct_image_dir.src=\"".$img_dir_close."\";\r\n";
        $str.="var ct_image_item = new Image();ct_image_dir.src=\"".$img_item."\";\r\n";
        $str.="function setExpandedIco(id){var i=document.getElementById(id+'_codethat_image');i.src='".$img_dir_open."';}\r\n" ;
            $str.="function setCollapsedIco(id){var i=document.getElementById(id+'_codethat_image');i.src='".$img_dir_close."';}\r\n" ;
            $str.="function toggleNode(id){if(toggleLayer(id+'_codethat_subitems'))setExpandedIco(id);else setCollapsedIco(id);}\r\n" ;
            $str.="function toggleLayer(id){var l=document.getElementById(id);var s=l.style||l;if(s.visibility=='hidden'){s.visibility='visible';s.display='block';return true;}else{s.visibility='hidden';s.display='none';return false;}}\r\n";
        $str.="</script>\r\n";
        return ($str);
    }
    //Js 樹狀選單
    function Param($name,$value,$equal,$brackets,$post){
        $str="";
        if($value!="")
        {
            $str=$name.$equal.$brackets.$value.$brackets.$post;
        }
        return($str);
    }
    //pageview history
    function pageview_history($ph_type,$ph_type_id=0,$m_id=0){
        global $db,$cms_cfg;
        $ip=$_SERVER["REMOTE_ADDR"];
        //$ip="59.126.50.204"; //taiwan
        //$ip="137.153.10.110";
        if($ip!="127.0.0.1"){
            $ph_ip_number = sprintf("%u", ip2long($ip));
            //get ip country
            $sql="SELECT country_name FROM ".$cms_cfg['tb_prefix']."_ip_country WHERE ip_from <= inet_aton('".$ip."') AND ip_to >= inet_aton('".$ip."') ";
            $selectrs = $db->query($sql);
            $row = $db->fetch_array($selectrs,1);
            if(empty($row["country_name"])){
                $row["country_name"]="UNKNOWN";
            }
            $sql="
                insert into ".$cms_cfg['tb_prefix']."_pageview_history (
                    m_id,
                    ph_ip_number,
                    ph_country,
                    ph_type,
                    ph_type_id,
                    ph_modifydate,
                    ph_dateY,
                    ph_dateM,
                    ph_dateD
                ) values (
                    '".$m_id."',
                    '".$ph_ip_number."',
                    '".$row["country_name"]."',
                    '".$ph_type."',
                    '".$ph_type_id."',
                    '".date("Y-m-d H:i:s")."',
                    '".date("Y")."',
                    '".date("m")."',
                    '".date("d")."'
                )";
            $db->query($sql);
        }
    }
    function js_notice($msg,$goto_url){
        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
        echo "<script language=javascript>";
        echo "Javascript:alert('".$msg."')";
        echo "</script>";
        echo "<script language=javascript>";
        echo "document.location='".$goto_url."'";
        echo "</script>";
    }
    function replace_html_tags($str,$sw="10"){
//        $str=str_replace(" ","",strip_tags($str));
        $pattern = array("10","01","00");
        foreach($pattern as $p){
            if(($p & $sw)==$p){
                switch($p & $sw){
                    case "10":
                        $str=strip_tags($str);
                        break;
                    case "01":
                        $str=nl2br($str);
                        break;
                    case "00":
                    default:
                        $str=str_replace("\r\n","",$str);
                        break;
                }
            }
        }
        return $str;
    }
    //counter
    /**
     *  $num : 計數器位數
     *  $session_on : 使用session控制重新整理是否計數
     */
    function counter($num=10, $session_on=0) {
        global $tpl,$cms_cfg;
        $fh = fopen("conf/counter.txt", "r+");
        $count = fgets($fh, 4096);
        if($session_on) {
            if(empty($_SESSION["visited"])) {
                $_SESSION["visited"] = 1;
                $count++;
                fseek($fh, 0);
                if(fputs($fh, $count)===false) return "Counter update error!";
            }
        }else{
            $count++;
            fseek($fh, 0);
            if(fputs($fh, $count)===false) return "Counter update error!";
        }
        fclose($fh);
        $count_dig = str_pad($count, $num, "0", STR_PAD_LEFT);
        $c_arr = str_split($count_dig);
        $c_str ="";
        foreach($c_arr as $key => $data) {
            $c_str .= "<img border=\"0\" src=".$cms_cfg["base_root"]."images/".$c_arr["$key"].".gif />&nbsp;";
        }
        $tpl->assignGlobal("TAG_COUNTER_PIC", $c_str);
        return true;
    }
    //Mathematics security code
    function math_security() {
        global $tpl,$ws_array,$cms_cfg,$TPLMSG;
        $digital1 = rand(1,10);
        $digital2 = rand(1,10);
        $_SESSION["securityCode"] = $digital1+$digital2;
        $_SESSION["keyCode"] = time();
        $ems_code= $this->encode_math_security($_SESSION["keyCode"]);
        $math_str = $digital1." + ".$digital2." =";
        $tpl->assignGlobal( array("MSG_LOGIN_SECURITY" => $TPLMSG["LOGIN_SECURITY"],
                                  "TAG_MATH_SECURITY" => $math_str,
                                  "TAG_MATH_INPUT" => "<input type=\"text\" name=\"security".$ems_code."\" size=\"4\" />"
        ));
    }
    //Mathematics security code is value
    function math_security_isvalue() {
        global $tpl,$ws_array,$cms_cfg,$TPLMSG;
        $ems_code= $this->encode_math_security($_SESSION["keyCode"]);
        return (!empty($_SESSION["securityCode"]) && $_REQUEST["security".$ems_code] == $_SESSION["securityCode"]);
    }
    function encode_math_security($keyCode) {
        global $tpl,$ws_array,$cms_cfg,$TPLMSG;
        $code=substr(md5($keyCode),15,2);
        return $code;
    }
    //國家下拉選單
    function country_select($country="") {
        global $tpl,$ws_array,$TPLMSG;
        $tpl->newBlock("SELECT_COUNTRY");
        $tpl->assign("MSG_COUNTRY", $TPLMSG['COUNTRY']);
        $str = "<option value=\"\">-- ".$TPLMSG['SELECT_COUNTRY']." --</option>\n";
        foreach($ws_array["country_array"] as $key => $value) {
            $sel = ($value==$country) ? "selected":"";
            $str .= "<option value=\"".$value."\" ".$sel.">".$value."</option>\n";
        }
        $tpl->assignGlobal("TAG_SELECT_OPTION_COUNTRY", $str);
    }
    function google_code(){
        global $tpl,$db,$cms_cfg;
        $sql="select sc_ga_code,sc_gs_code,sc_gs_datetime,sc_gs_filename from ".$cms_cfg['tb_prefix']."_system_config";
        $selectrs = $db->query($sql);
        $row = $db->fetch_array($selectrs,1);
        if(trim($row["sc_ga_code"])!=""){
            $tpl->newBlock("GOOGLE_ANALYTICS");
            $tpl->assign("VALUE_GA_CODE",$row["sc_ga_code"]);
        }
        if(trim($row["sc_gs_code"])!=""){
            $tpl->newBlock("GOOGLE_SITEMAP_METATAG");
            $tpl->assign("VALUE_GS_CODE",$row["sc_gs_code"]);
        }
    }
    //圖檔檔案路徑替換避免破圖
    function file_str_replace($input_path,$pattern="#(.*/)(upload_files/.+)$#i",$replacement="$2"){
        global $cms_cfg;
        $input_path=preg_replace( $pattern, $replacement, $input_path);
        return $input_path;
    }
    function content_file_str_replace($content){
        global $cms_cfg;
        $content=preg_replace('%([-\w\.:]*/)*(upload_files/([-\w\.]+/)*[-\w\.]+)%i', $cms_cfg['file_root']."$2", $content);
        $content=preg_replace('%([-\w\.:]*/)*(images/([-\w\.]+/)*[-\w\.]+)%i', $cms_cfg['base_root']."$2", $content);
        return $content;
    }    
    //鎖滑鼠右鍵功能
    function mouse_disable() {
        global $tpl,$cms_cfg;
        $str = "";
        if($cms_cfg["ws_module"]["ws_on_contextmenu"]==1) $str .="onContextMenu=\"return false\" ";  //禁滑鼠右鍵
        if($cms_cfg["ws_module"]["ws_on_copy"]==1) $str .="onCopy=\"return false\" "; //禁複製
        if($cms_cfg["ws_module"]["ws_on_selectstart"]==1) $str .="onSelectStart=\"return false\"";  //禁選擇
        $tpl->assignGlobal("TAG_MOUSE_DISABLE", $str);
    }
    //取得最大排序值
    function get_max_sort_value($table_name,$table_prefix,$field=null,$id=null,$cate=false){
        global $db;
        if($cate){ //是否有上層分類
            $sql="select MAX(".$table_prefix."_sort) as max_value from ".$table_name." where ".$field."='".$id."'";
        }else{
            $sql="select MAX(".$table_prefix."_sort) as max_value from ".$table_name;
        }
        $selectrs = $db->query($sql);
        $row = $db->fetch_array($selectrs,1);
        $sort_value=$row["max_value"]+1;
        return $sort_value;
    }
    
    function ad_list($id){
        global $db,$tpl,$cms_cfg;
        //篩選條件
        $ex_where_clause = "  and (ad_status='1' or (ad_status='2' and ad_startdate <= '".date("Y-m-d")."' and ad_enddate >= '".date("Y-m-d")."') ) ";
        $ex_where_clause .= "  and (ad_show_type='0' or (ad_show_type='1' and find_in_set('".$id."',ad_show_zone)>0 )) ";
        //排序方式
        switch($_SESSION[$cms_cfg['sess_cookie_name']]["sc_ad_sort_type"]){
            case 2 :
                $orderby=" order by ad_sort ".$cms_cfg['sort_pos']." ";
                break;
            case 1 :
                $orderby=" order by ad_modifydate desc ";
                break;
            case 0 :
            default :
                $orderby=" order by rand() ";
        }
        //上方橫幅廣告 寬580 X 高120
        $ad_up_banner_limit=($cms_cfg['ad_up_banner_limit'])?$cms_cfg['ad_up_banner_limit']:1;
        $sql="select * from ".$cms_cfg['tb_prefix']."_ad where ad_cate='1' ".$ex_where_clause.$orderby." limit 0,".$ad_up_banner_limit;
        $selectrs = $db->query($sql);
        $rsnum    = $db->numRows($selectrs);
        if($rsnum >0){
            while($row = $db->fetch_array($selectrs,1)){
                $tpl->newBlock("AD_ZONE_580_120");
                switch($row["ad_file_type"]){
                    case "image" :
                        $tpl->newBlock("AD_TYPE_IMAGE_580_120");
                        if($row["ad_link"]){
                            $tpl->newBlock("AD_TYPE_IMAGE_580_120_LINK");
                        }else{
                            $tpl->newBlock("AD_TYPE_IMAGE_580_120_NOLINK");
                        }
                        $tpl->assign("VALUE_AD_SUBJECT",$row["ad_subject"]);
                        $tpl->assign("VALUE_AD_LINK",$row["ad_link"]);
                        $tpl->assign("VALUE_AD_FILE",$cms_cfg["file_root"].$row["ad_file"]);
                        break;
                    case "flash" :
                        $tpl->newBlock("AD_TYPE_FLASH_580_120");
                        $tpl->assign("VALUE_AD_LINK",$row["ad_link"]);
                        if(!empty($row["ad_file"])){
                            $piece=explode(".swf",$row["ad_file"]);
                            $tpl->assign("VALUE_AD_FILE",$piece[0]);
                        }
                        break;
                    case "txt" :
                        $tpl->newBlock("AD_TYPE_TXT_580_120");
                        if($row["ad_link"]){
                            $tpl->newBlock("AD_TYPE_TXT_580_120_LINK");
                        }else{
                            $tpl->newBlock("AD_TYPE_TXT_580_120_NOLINK");
                        }
                        $tpl->assign("VALUE_AD_SUBJECT",$row["ad_subject"]);
                        $tpl->assign("VALUE_AD_LINK",$row["ad_link"]);
                        $tpl->assign("VALUE_AD_FILE",$row["ad_file"]);
                        break;
                }
                $tpl->gotoBlock("AD_ZONE_580_120");
            }
        }
        //側邊廣告 寬150 X 高150
        $ad_left_button_limit=($cms_cfg['ad_left_button_limit'])?$cms_cfg['ad_left_button_limit']:1;
        $sql="select * from ".$cms_cfg['tb_prefix']."_ad where ad_cate='2' ".$ex_where_clause.$orderby." limit 0,4";
        $selectrs = $db->query($sql);
        $rsnum    = $db->numRows($selectrs);
        if($rsnum >0){
            while($row = $db->fetch_array($selectrs,1)){
                $tpl->newBlock("AD_ZONE_150_150");
                switch($row["ad_file_type"]){
                    case "image" :
                        $tpl->newBlock("AD_TYPE_IMAGE_150_150");
                        if($row["ad_link"]){
                            $tpl->newBlock("AD_TYPE_IMAGE_150_150_LINK");
                        }else{
                            $tpl->newBlock("AD_TYPE_IMAGE_150_150_NOLINK");
                        }
                        $tpl->assign("VALUE_AD_SUBJECT",$row["ad_subject"]);
                        $tpl->assign("VALUE_AD_LINK",$row["ad_link"]);
                        $tpl->assign("VALUE_AD_FILE",$cms_cfg["file_root"].$row["ad_file"]);
                        break;
                    case "flash" :
                        $tpl->newBlock("AD_TYPE_FLASH_150_150");
                        $tpl->assign("VALUE_AD_LINK",$row["ad_link"]);
                        if(!empty($row["ad_file"])){
                            $piece=explode(".swf",$row["ad_file"]);
                            $tpl->assign("VALUE_AD_FILE",$cms_cfg["file_root"].$piece[0]);
                        }
                        break;
                    case "txt" :
                        $tpl->newBlock("AD_TYPE_TXT_150_150");
                        if($row["ad_link"]){
                            $tpl->newBlock("AD_TYPE_TXT_150_150_LINK");
                        }else{
                            $tpl->newBlock("AD_TYPE_TXT_150_150_NOLINK");
                        }
                        $tpl->assign("VALUE_AD_SUBJECT",$row["ad_subject"]);
                        $tpl->assign("VALUE_AD_LINK",$row["ad_link"]);
                        $tpl->assign("VALUE_AD_FILE",$row["ad_file"]);
                        break;
                }
                $tpl->gotoBlock("AD_ZONE_150_150");
            }
        }
        //側邊廣告 寬150 X 高50
        $ad_left_button_limit=($cms_cfg['ad_left_button_limit'])?$cms_cfg['ad_left_button_limit']:1;
        $sql="select * from ".$cms_cfg['tb_prefix']."_ad where ad_cate='3' ".$ex_where_clause.$orderby." limit 0,1";
        $selectrs = $db->query($sql);
        $rsnum    = $db->numRows($selectrs);
        if($rsnum >0){
            while($row = $db->fetch_array($selectrs,1)){
                $tpl->newBlock("AD_ZONE_150_50");
                switch($row["ad_file_type"]){
                    case "image" :
                        $tpl->newBlock("AD_TYPE_IMAGE_150_50");
                        if($row["ad_link"]){
                            $tpl->newBlock("AD_TYPE_IMAGE_150_50_LINK");
                        }else{
                            $tpl->newBlock("AD_TYPE_IMAGE_150_50_NOLINK");
                        }
                        $tpl->assign("VALUE_AD_SUBJECT",$row["ad_subject"]);
                        $tpl->assign("VALUE_AD_LINK",$row["ad_link"]);
                        $tpl->assign("VALUE_AD_FILE",$cms_cfg["file_root"].$row["ad_file"]);
                        break;
                    case "flash" :
                        $tpl->newBlock("AD_TYPE_FLASH_150_50");
                        $tpl->assign("VALUE_AD_LINK",$row["ad_link"]);
                        if(!empty($row["ad_file"])){
                            $piece=explode(".swf",$row["ad_file"]);
                            $tpl->assign("VALUE_AD_FILE",$cms_cfg["file_root"].$piece[0]);
                        }
                        break;
                    case "txt" :
                        $tpl->newBlock("AD_TYPE_TXT_150_50");
                        if($row["ad_link"]){
                            $tpl->newBlock("AD_TYPE_TXT_150_50_LINK");
                        }else{
                            $tpl->newBlock("AD_TYPE_TXT_150_50_NOLINK");
                        }
                        $tpl->assign("VALUE_AD_SUBJECT",$row["ad_subject"]);
                        $tpl->assign("VALUE_AD_LINK",$row["ad_link"]);
                        $tpl->assign("VALUE_AD_FILE",$row["ad_file"]);
                        break;
                }
                $tpl->gotoBlock("AD_ZONE_150_50");
            }
        }
        //首頁跑馬燈
        $ad_left_button_limit=($cms_cfg['ad_marquee_limit'])?$cms_cfg['ad_marquee_limit']:1;
        $sql="select * from ".$cms_cfg['tb_prefix']."_ad where ad_cate='4' ".$ex_where_clause.$orderby." limit 0,".$ad_left_button_limit;
        $selectrs = $db->query($sql);
        $rsnum    = $db->numRows($selectrs);
        if($rsnum >0){
            $tpl->newBlock("AD_ZONE_MARQUEE");
            while($row = $db->fetch_array($selectrs,1)){
                switch($row["ad_file_type"]){
                    case "image":
                        $tpl->newBlock("AD_TYPE_IMAGE_MARQUEE");
                        if($row["ad_link"]){
                            $tpl->newBlock("AD_TYPE_IMAGE_MARQUEE_LINK");
                        }else{
                            $tpl->newBlock("AD_TYPE_IMAGE_MARQUEE_NOLINK");
                        }
                        $tpl->assign("VALUE_AD_SUBJECT",$row["ad_subject"]);
                        $tpl->assign("VALUE_AD_LINK",$row["ad_link"]);
                        $tpl->assign("VALUE_AD_FILE",$cms_cfg["file_root"].$row["ad_file"]);                        
                        break;
                    case "flash":
                        $tpl->newBlock("AD_TYPE_FLASH_MARQUEE");
                        if(!empty($row["ad_file"])){
                            $piece=explode(".swf",$row["ad_file"]);
                            $tpl->assign("VALUE_AD_FILE",$cms_cfg["file_root"].$piece[0]);
                        }                        
                        break;
                    case "txt" :
                        $tpl->newBlock("AD_TYPE_TXT_MARQUEE");
                        $tpl->assign("VALUE_AD_SUBJECT",$row["ad_file"]);
                        $tpl->assign("VALUE_AD_LINK",trim($row["ad_link"])?$row["ad_link"]:"#");
                        break;
                }
                $tpl->gotoBlock("AD_ZONE_MARQUEE");
            }
        }
        //內頁跑馬燈
        $ad_left_button_limit=($cms_cfg['ad_marquee_limit'])?$cms_cfg['ad_marquee_limit']:1;
        $sql="select * from ".$cms_cfg['tb_prefix']."_ad where ad_cate='5' ".$ex_where_clause.$orderby." limit 0,".$ad_left_button_limit;
        $selectrs = $db->query($sql);
        $rsnum    = $db->numRows($selectrs);
        if($rsnum >0){
            $tpl->newBlock("AD_ZONE_INSIDE_MARQUEE");
            while($row = $db->fetch_array($selectrs,1)){
                switch($row["ad_file_type"]){
                    case "image":
                        $tpl->newBlock("AD_TYPE_IMAGE_INSIDE_MARQUEE");
                        if($row["ad_link"]){
                            $tpl->newBlock("AD_TYPE_IMAGE_INSIDE_MARQUEE_LINK");
                        }else{
                            $tpl->newBlock("AD_TYPE_IMAGE_INSIDE_MARQUEE_NOLINK");
                        }
                        $tpl->assign("VALUE_AD_SUBJECT",$row["ad_subject"]);
                        $tpl->assign("VALUE_AD_LINK",$row["ad_link"]);
                        $tpl->assign("VALUE_AD_FILE",$cms_cfg["file_root"].$row["ad_file"]);                        
                        break;
                    case "flash":
                        $tpl->newBlock("AD_TYPE_FLASH_INSIDE_MARQUEE");
                        if(!empty($row["ad_file"])){
                            $piece=explode(".swf",$row["ad_file"]);
                            $tpl->assign("VALUE_AD_FILE",$cms_cfg["file_root"].$piece[0]);
                        }                        
                        break;
                    case "txt" :
                        $tpl->newBlock("AD_TYPE_TXT_INSIDE_MARQUEE");
                        $tpl->assign("VALUE_AD_SUBJECT",$row["ad_file"]);
                        $tpl->assign("VALUE_AD_LINK",trim($row["ad_link"])?$row["ad_link"]:"#");
                        break;
                }
                $tpl->gotoBlock("AD_ZONE_INSIDE_MARQUEE");
            }
        }
    }
    //取得主功能類別，如：abouts,service,products,news,faq,case,contactus
    function get_main_fun(){     
        global $cms_cfg;
        return  preg_replace("#^".preg_quote($cms_cfg['base_root'],"/")."#","",preg_replace("/\.php$/","", $_SERVER['SCRIPT_NAME']));
    }
    function dropdown_menu($sw=null,$extra=null){
        global $cms_cfg,$tpl,$db,$TPLMSG;
        $tpl->newBlock("DROPDOWN_MENU_SCRIPT");//載入下拉式功能的JS
        $menu_arr = array();
        if($sw){
            $sw = explode(',',$sw);
            //撈取下拉式功能表項目
            ////關於我們 & SHOWROOM
            if(in_array('aboutus',$sw)){
                $sql = "select * from ".$cms_cfg['tb_prefix']."_aboutus where au_status='1' order by au_cate,au_sort ".$cms_cfg['sort_pos'];
                $res = $db->query($sql);
                $had_au_cate = array();
                while($row=$db->fetch_array($res,1)){
                    if(!in_array($row['au_cate'],$had_au_cate)){
                        $menu_arr[$row['au_cate']]=array();
                        array_push($had_au_cate, $row['au_cate']);
                    }
                    $menu_arr[$row['au_cate']][]=array(
                        'name'=>$row['au_subject'],
                        'link'=> $cms_cfg['base_root'].$row['au_cate'].'/'.$row['au_seo_filename'].".html",
                    );
                }
            }
            ////產品
            if(in_array('products',$sw)){
                /////主分類
                $sql = "select * from ".$cms_cfg['tb_prefix']."_products_cate where pc_parent='0' and pc_status='1' order by pc_sort ".$cms_cfg['sort_pos'];
                $res = $db->query($sql);
                if($db->numRows($res)){
                    $menu_arr['products']=array();
                    while($row=$db->fetch_array($res,1)){
                        //次分類
                        $sql = "select * from ".$cms_cfg['tb_prefix']."_products_cate where pc_parent='".$row['pc_id']."' and pc_status='1' order by pc_sort ".$cms_cfg['sort_pos'];
                        $sub_res = $db->query($sql);
                        if($db->numRows($sub_res)){
                            $submenu="<ul>";
                            while($row2=$db->fetch_array($sub_res,1)){
                                //產品
                                $sql = "select * from ".$cms_cfg['tb_prefix']."_products where pc_id='".$row2['pc_id']."' and p_status='1' order by p_sort ".$cms_cfg['sort_pos'];
                                $p_res = $db->query($sql);
                                $p_n = $db->numRows($p_res);
                                $submenu.="<li><span>".$row2['pc_name']."</span>\n ";
                                if($p_n){
                                    $submenu.="<ul>\n";
                                    while($row3=$db->fetch_array($p_res,1)){
                                        $p_link=$cms_cfg['base_root'].$row2['pc_seo_filename']."/".$row3['p_seo_filename'].".html";
                                        $p_name=$row3['p_name'];
                                        $submenu.="<li><span rel='".$p_link."'>".$p_name."</span></li>\n";
                                    }
                                    $submenu.="<div class=\"dd_background\"></div></ul>\n";
                                }
                                $submenu.="</li>\n";
                            }
                            $submenu.="<div class=\"dd_background\"></div></ul>\n";
                        }else{
                            $submenu = "";
                        }
                        $menu_arr['products'][] = array(
                            'name'    => "#",
                            'link'    => $row['pc_name'],
                            'submenu' => $submenu,
                        );
                    }
                }
            }
            ////最新消息
            if(in_array('news',$sw)){
                $sql = "select * from ".$cms_cfg['tb_prefix']."_news_cate where nc_status='1' and nc_indep='0' order  by nc_sort ".$cms_cfg['sort_pos'];
                $res = $db->query($sql);
                if($db->numRows($res)){
                    $menu_arr['news']=array();
                    while($row=$db->fetch_array($res,1)){
                        $menu_arr['news'][] = array(
                            "name" => $row['nc_subject'],
                            "link" => $cms_cfg['base_root']."news/".$row['nc_seo_filename'].".htm",
                        );
                    }
                }  
            }
        }
        /*加入自訂的區塊*/
        if(is_array($extra)){
            $menu_arr = array_merge($menu_arr, $extra);
        }
        //輸出到樣版
        if(!empty($menu_arr)){
            foreach($menu_arr as $divname => $menuinfo){
                $tpl->newBlock("DROPDOWN_MENU_DIV");
                $tpl->assign("MENU_NAME",  strtolower($divname));
                foreach($menuinfo as $menu){
                    $tpl->newBlock("DD_MENU_LIST");
                    $tpl->assign(array(
                        "LIST_LINK"     => $menu['link'],
                        "LIST_NAME"     => $menu['name'],
                        "SUB_MENU_LIST" => $menu['submenu'],
                    ));
                }
            }
        }
    }    
    /*相關網站
    **將 tempaltes/ws-fn-goodlink-select-tpl 引入為區塊
    */
    function goodlink_select(){
        global $db,$tpl,$cms_cfg;
        $sql = "select * from ".$cms_cfg['tb_prefix']."_goodlink where l_status='1' order by l_sort ".$cms_cfg['sort_pos']." ";
        $res = $db->query($sql);
        if($db->numRows($res)){
            while($row = $db->fetch_array($res,1)){
                $tpl->newBlock("GOODLINK_SELECT_OPTION");
                $tpl->assign(array(
                    'GOODLINK_URL'  => $row['l_url'],
                    "GOODLINK_NAME" => $row['l_subject'],
                    "GOODLINK_POP"  => $row['l_pop'],
                ));
            }
        }
    }   
    
    function float_menu(){
        global $tpl;
        $tpl->newBlock("SCROLL_FLOAT_SCRIPT");
    }     
    
    function get_short_str($str,$len){
        return (mb_strlen($str,"utf-8")<=$len)?$str:mb_substr($str, 0, $len-3, "utf-8")."...";
    }    
    //等比圖輸出
    function resize_dimensions($goal_width,$goal_height,$width,$height) {
        //長寬在範圍內的維持原尺寸
        $resize_img = array('width' => $width, 'height' => $height);
        // If the ratio > goal ratio and the width > goal width resize down to goal width
        if ($width/$height > $goal_width/$goal_height && $width > $goal_width) {
            $resize_img['width'] = $goal_width;
            $resize_img['height'] = round($goal_width/$width * $height);
        } elseif ($height > $goal_height) { // Otherwise, if the height > goal, resize down to goal height
            $resize_img['width'] = round($goal_height/$height * $width);
            $resize_img['height'] = $goal_height;
        }
        return $resize_img;
    }    
	/*將$_GET,$_POST,$_COOKIE等資料去除magic_quotes_gpc = on時的影響(加\)
	*/
    function magic_gpc(&$data_arr){
        if(get_magic_quotes_gpc()){
            foreach($data_arr as $k => $value){
                if(is_string($value)){
                    $data_arr[$k] = stripslashes($value);
                }elseif(is_array($value)){
                    $this->magic_gpc($value);
                }
            }
        }
    }	
    //類似light box的效果，顯示區域下方有顯示圖片清單
    function SLIDE_BOX(){
        global $tpl,$cms_cfg,$TPLMSG;
        $tpl->newBlock("SLIDE_BOX");
        $tpl->assign(array(
           "SBOX_IMAGE" => $TPLMSG['SLIDE_BOX_IMAGE'], 
           "SBOX_OF" => $TPLMSG['SLIDE_BOX_OF'], 
           "SBOX_BACK" => $TPLMSG['SLIDE_BOX_BTN_BACK'], 
           "SBOX_NEXT" => $TPLMSG['SLIDE_BOX_BTN_NEXT'], 
           "SBOX_PREV" => $TPLMSG['SLIDE_BOX_BTN_PREV'], 
           "SBOX_CLICK_VIEW_LIST" => $TPLMSG['SLIDE_BOX_CLICK_VIEW_LIST'], 
           "SBOX_CLICK_CLOSE_LIST" => $TPLMSG['SLIDE_BOX_CLICK_CLOSE_LIST'], 
        ));
    }
    //不知為何還有一個au_box，和上面的slide_box好像差不多
    function AU_BOX(){
        global $tpl,$cms_cfg,$TPLMSG;
        $tpl->newBlock("AU_BOX");
        $tpl->assign(array(
           "SBOX_IMAGE" => $TPLMSG['SLIDE_BOX_IMAGE'], 
           "SBOX_OF" => $TPLMSG['SLIDE_BOX_OF'], 
           "SBOX_BACK" => $TPLMSG['SLIDE_BOX_BTN_BACK'], 
           "SBOX_NEXT" => $TPLMSG['SLIDE_BOX_BTN_NEXT'], 
           "SBOX_PREV" => $TPLMSG['SLIDE_BOX_BTN_PREV'], 
           "SBOX_CLICK_VIEW_LIST" => $TPLMSG['SLIDE_BOX_CLICK_VIEW_LIST'], 
           "SBOX_CLICK_CLOSE_LIST" => $TPLMSG['SLIDE_BOX_CLICK_CLOSE_LIST'], 
        ));
    }
    //等比圖輸出的精簡版
    function resizeto($img,$to_w,$to_h){
        $dimensions["width"]=$to_w;
        $dimensions["height"]=$to_h;
        if(is_file($_SERVER['DOCUMENT_ROOT'].$img)){
            list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'].$img);
            $dimensions = $this->resize_dimensions($dimensions["width"],$dimensions["height"],$width,$height);
        }        
        return $dimensions;
    }
    //取得來源裡的youtube影片識別碼
    function get_mv_code($url){
        if(strlen($url)==11){
            return $url;
            //http://www.youtube.com/embed/S3f-riH1Q_A
        }elseif(preg_match("#(http:)*(//www.youtube.com/|//youtu.be/)(embed/|watch?.*v=)*([^&\s\"?]+)#i",$url,$match)){
            return $match[4];
        }        
    }      
    //由ip取得國家
    function get_ip_country($ip){
        global $cms_cfg;
        require_once $_SERVER['DOCUMENT_ROOT'] .$cms_cfg['base_root']."class/dbip/dbip-client.class.php";
        $api_key = "0a1739288ac4c4bfd287242a24db992d46ce98b5";
        $dbip = new DBIP_Client($api_key);
        $data = array();
        foreach ($dbip->Get_Address_Info($ip) as $k => $v) {
            $data[$k] = $v;
	}
        return $data;
    }    
    //啟用搜尋欄位的提示文字功能，及自動完成
    function clearfield($autocomplete=false){
        global $tpl;
        $tpl->newBlock("JQUERY_CLEARFIELD_SCRIPT");    
        if($autocomplete){
            $tpl->newBlock('JQUERY_UI_SCRIPT');
            $tpl->newBlock("SEARCH_FIELD_AUTOCOMPLETE");    
        }
    }    
    //增加左側主選單
    function new_left_menu(array $menu_items,$blockname="CATE",$sub=false,$deep=""){
        global $tpl;
        $subul1=array(
            "CATE"    => "<div class=\"menu_body\">",
            "SUBCATE" => "<ul id=\"\" class=\"menu_prod_body\">",
        );
        $subul2=array(
            "CATE"    => "</div>",
            "SUBCATE" => "</ul>",
        );
        $deep = $deep?"S".$deep:"SUB";
        $sub_cate_name = $deep."CATE";        
        foreach($menu_items as $itme){
            $tpl->newBlock( "LEFT_".$blockname."_LIST" );
            $tpl->assign( array( 
                "VALUE_".$blockname."_NAME" => $itme['name'],
                "VALUE_".$blockname."_LINK" => $itme['link'],
                "WRAPPER_CLASS"             => $itme['class'],
                "TAG_CURRENT_CLASS"         => $itme['tag_cur'],
            ));        
            if($itme['sub']){
                $tpl->assign("TAG_".$deep."_UL1",$subul1[$blockname]);
                $tpl->assign("TAG_".$deep."_UL2",$subul2[$blockname]);
                $this->new_left_menu($itme['sub'],$sub_cate_name,true,$deep);
            }
        }
    }   
    //建立html a標籤
    function mk_link($text,$link,$extra=null){
        if(is_array($extra)){
            $attr_arr = array();
            $attr_str = '';
            foreach($extra as $k=>$v){
               $attr_arr[] = sprintf("%s=\"%s\"",$k,$v); 
            }
            $attr_str = implode(' ',$attr_arr);
        }
        return "<a href=\"{$link}\" {$attr_str}>{$text}</a>";
    }
    //麵包屑的路徑
    /* 參數數量說明
     * 數量0:直接指定給樣版變數TAG_LAYER
     * 數量1:陣列或字串，如是陣列必須包括名稱索引為name及link的項目以建立連結，字串則直接加入項目
     * 數量2:第1個參數是連結的文字，第二個是是連結的url
     */
    function layer_link(){
        global $tpl,$cms_cfg,$TPLMSG;
        static $_layers = array();
        static $call = 0;
        if($call==0){
            $_layers[$call] = $this->mk_link($TPLMSG['HOME'] , $cms_cfg['base_root']);
        }                
        switch(func_num_args()){
            case 0: //輸出layer
                if(empty($cms_cfg['path_separator'])){
                    foreach($_layers as $k=>$v){
                        $_layers[$k] = sprintf($cms_cfg['path_wraper'],$v);
                    }                    
                }                
                $tpl->assignGlobal("TAG_LAYER",implode($cms_cfg['path_separator'] ,$_layers));
                break;
            case 1:
                $tmp = func_get_arg(0);
                if(is_array($tmp)){
                    $_layers[] = $this->mk_link($tmp['name'],$tmp['link']);                        
                }else{
                    $_layers[] = $tmp;                        
                }
                break;
            case 2:
                $txt = func_get_arg(0);
                $link = func_get_arg(1);
                $_layers[] = $this->mk_link($txt,$link);
                break;
            default:
                throw new Exception('argument type or argument nums error!');
        }
        $call++;
        return $this;
    }       
    //multiple checkbox
    /*參數說明
     * $blockname，樣版區塊名稱
     * $datas，所有內容的集合陣列
     * $values，指定內容的陣列，值是$datas的索引值
     */
    function multiple_checkbox($blockname,$datas,$values){
        global $tpl;
        if(is_string($values)){
            $values = explode(',',$values);
        }
        foreach($datas as $k => $v){
            $tpl->newBlock(strtoupper($blockname)."_CHECKBOX");
            $tpl->assign(array(
                "VALUE_".strtoupper($blockname)."_KEY"  => $k, 
                "VALUE_".strtoupper($blockname)."_NAME" => $v, 
                "CHECKED"                   => (@in_array($k,$values))?"checked":"",
            ));
        }
    }
    //multiple radio
    /*參數說明
     * $blockname，樣版區塊名稱
     * $datas，所有內容的集合陣列
     * $values，指定內容的值，值是$datas的索引值，若是陣列，則取第一個值
     */
    function multiple_radio($blockname,$datas,$values){
        global $tpl;
        if(is_array($values)){
            $values = $values[0];
        }
        foreach($datas as $k => $v){
            $tpl->newBlock(strtoupper($blockname)."_RADIO");
            $tpl->assign(array(
                "VALUE_".strtoupper($blockname)."_KEY"  => $k, 
                "VALUE_".strtoupper($blockname)."_NAME" => $v, 
                "CHECKED"                   => ($k==$values)?"checked":"",
            ));
        }
    }        
    //參數說明
    /*$datas，所有值的陣列
     *$values，指定值的陣列，內容是上述陣列的索引值範圍
     *$sp，預設使用的分隔字元 
     */
    function multi_map_value($datas,$values,$sp=','){
        $tmp = array();
        if(is_string($values)){
            $values = explode($sp,$values);
        }
        foreach($values as $k){
            $tmp[] = $datas[$k];
        }
        return implode(',',$tmp);
    }    
    //anythingSlider switch
    function anything_slider(){
        global $tpl;
        $tpl->newBlock("ANYTHING_SLIDER_SCRIPT");
    }
    //contact_s select
    function contact_s_select($sid,$zone="CONTACT"){
        global $ws_array,$tpl;
        $tpl->newBlock($zone."_S_ZONE");
        foreach($ws_array["contactus_s"] as $id=>$sname){
            $tpl->newBlock($zone."_S_LIST");
            $tpl->assign(array(
                "VALUE_CONTACT_S_ID"   => $id,
                "VALUE_CONTACT_S_NAME" => $sname,
                "TAG_SELECTED"         => ($sid==$id)?"selected":"",
            ));
        }
    }
    //自設的fgetcsv
    function fgetcsv($fp,$limit=',',$enc='"'){
        $str_mode = false;
        $arr = array();
        $k=0;  //陣列索引
        $ik=0; //陣列元素長度
        $tmp_str="";
        while(($c=fgetc($fp))!==FALSE){
            if($c=="\n"){
                if(!$str_mode){
                    if(substr($arr[$k],-1)=="\r"){ //如果是windwos，把非字串模式下的內容拿掉\r
                        $arr[$k] = substr($arr[$k],0,strlen($arr[$k])-1); //移除字串後的\r
                    }
                    break;
                }else{
                    if(substr($tmp_str,-1).$c==($enc."\n")){ //如果當前字串結尾取得"\n，當作是字串及行的結束，重設所有相關變數，適用Linux
                        $arr[$k] = substr($tmp_str,0,strlen($tmp_str)-1); //移除字串後的"，取得當前累積字串
                        $str_mode=false; //切換回非字串模式
                        break;
                    }elseif(substr($tmp_str,-2).$c==($enc."\r\n")){ //如果當前字串結尾取得"\r\n，當作是字串及行的結束，重設所有相關變數，適用Windows
                        $arr[$k] = substr($tmp_str,0,strlen($tmp_str)-2); //移除字串後的"\r，取得當前累積字串
                        $str_mode=false; //切換回非字串模式
                        break;
                    }                    
                }
            }   
            if($c==$enc && !$str_mode && $ik==0){
                $str_mode = true;
                continue;
            }
            //非字串模式，且逗號的話，索引值加1，然後繼續找下一個字元
            if(!$str_mode && $c==$limit){
                $k++;         //索引值加1
                $ik=0;        //陣列元素長度重計
                $arr[$k] =""; //建立新陣列
                continue; 
            }
            if($str_mode){//在字串模式下就集中處理字串
                if(substr($tmp_str,-1).$c==($enc.$limit)){ //如果當前字串結尾取得",，當作是字串的結束，重設所有相關變數
                    $arr[$k] = substr($tmp_str,0,strlen($tmp_str)-1); //移除字串後的"，取得當前累積字串
                    $str_mode=false; //切換回非字串模式
                    $k++;  //陣列索引加1
                    $ik=0; //重設陣列元素長度
                    $arr[$k] = ""; //新增陣列元素
                    continue;
                }else{
                    $tmp_str .= $c;
                }
            }else{ //非字串模式就逐一累積字元
                $arr[$k] .= $c;
            }
            $ik++;
        }
        return $arr;
    }        
    //meta_refresh
    function meta_refresh($url,$secs){
        global $tpl;
        $tpl->assignGlobal("TAG_META_REFRESH","<meta http-equiv=\"Refresh\" content=\"".$secs.";url=".$url."\">");
    }
    function showPagination($Page,$showNoData){
        global $tpl,$TPLMSG;
        if($Page["total_records"]){ //直接指定分頁內容
            $tpl->newBlock( "PAGE_DATA_SHOW" );
            $tpl->assign( array("VALUE_TOTAL_RECORDS"  => $Page["total_records"],
                                "VALUE_TOTAL_PAGES"  => $Page["total_pages"],
                                "VALUE_PAGES_STR"  => $Page["pages_str"],
                                "VALUE_PAGES_LIMIT"=> $Page["page_limit"],
                                "VALUE_CUR_PAGE_ID"=> $Page["current_page_id"],
            ));
            if($Page["bj_page"]){
                $tpl->newBlock( "PAGE_BACK_SHOW" );
                $tpl->assign( "VALUE_PAGES_BACK"  , $Page["bj_page"]);
                $tpl->gotoBlock("PAGE_DATA_SHOW");
            }
            if($Page["nj_page"]){
                $tpl->newBlock( "PAGE_NEXT_SHOW" );
                $tpl->assign( "VALUE_PAGES_NEXT"  , $Page["nj_page"]);
                $tpl->gotoBlock("PAGE_DATA_SHOW");
            }
            if($Page["first_page"]){
                $tpl->newBlock( "PAGE_FIRST_SHOW" );
                $tpl->assign( "VALUE_PAGES_FIRST"  , $Page["first_page"]);
                $tpl->gotoBlock("PAGE_DATA_SHOW");
            }
            if($Page["last_page"]){
                $tpl->newBlock( "PAGE_LAST_SHOW" );
                $tpl->assign( "VALUE_PAGES_LAST"  , $Page["last_page"]);
                $tpl->gotoBlock("PAGE_DATA_SHOW");
            }            
        }else{
            if($showNoData){
                $tpl->assignGlobal("MSG_NO_DATA",$TPLMSG['NO_DATA']);            
            }
        }        
    }    
    function contactus_product_list($value){
        global $db,$tpl,$cms_cfg;
        if(is_array($value)){
            $value = implode(",",$value);
        }
        $sql = "select p_name from ".$cms_cfg['tb_prefix']."_products where p_status='1' and p_id in (".$value.") order by p_sort ".$cms_cfg['sort_pos'];
        $res = $db->query($sql,1);    
        while($row = $db->fetch_array($res,1)){
            $prod_arr[] = $row['p_name'];
        }
        return $prod_arr;
    }
    function contactus_product_list_checkbox($value){
        global $db,$tpl,$cms_cfg;
        $tpl->newBlock("PRODUCTS_LIST");
        $sql = "select p_id,p_name from ".$cms_cfg['tb_prefix']."_products where p_status='1' order by p_sort ".$cms_cfg['sort_pos'];
        $res = $db->query($sql,1);
        if($db->numRows($res)){
            while($row = $db->fetch_array($res,1)){
                $prod_arr[$row['p_id']]=$row['p_name'];
            }
            $this->multiple_checkbox("PRODUCT_LIST",$prod_arr,$value);
        }        
    }    
    //隨機產生密碼
    function rand_str($len=8){
        $no_arr = array(34,39,44,46,96);
        $str = "";
        for($i=1;$i<$len;$i++){
            do{
                $c = rand(33,126);
            }while(in_array($c,$no_arr));
            $str.=chr($c);
        }
        return $str;        
    }    
    function print_server(){
        echo "<pre>";
        print_r($_SERVER);
        echo "</pre>";
        die();
    }    
    function equal($c1,$c2,$compare_not_empty=true){
        if($compare_not_empty){
            return ($c1 && $c1==$c2);
        }else{
            return ($c1==$c2);
        }
    }
}
class MAINFUNC_NEW extends MAINFUNC{
    //固定顯示主分類及次分類的左方menu
    function left_fix_cate_list($main_id){
        global $tpl,$db,$main,$cms_cfg,$TPLMSG;
        $tpl->newBlock("LEFT_CATE_TITLE_IMG");
        $tpl->assignGlobal("LEFT_CATE_TITLE_IMG",$cms_cfg['base_images']."left-title-products.png");
        //判斷是否顯示主分類
        if($cms_cfg["ws_module"]["ws_left_main_pc"]==1) {
            $sql="select * from ".$cms_cfg['tb_prefix']."_products_cate where pc_parent='".$main_id."' and pc_status='1' order by pc_up_sort desc,pc_sort ".$cms_cfg['sort_pos']." ";
            $selectrs = $db->query($sql);
            $rsnum    = $db->numRows($selectrs);
        }else{
            $rsnum = 0;
        }
        if($rsnum > 0 ){ //有主分類
            //有次分類或主分類產品
            if($cms_cfg["ws_module"]["ws_left_menu_effects"]==1) {
                $tpl->newBlock("JS_LEFT_MENU");
                switch($cms_cfg["ws_module"]['ws_left_menu_type']){
                    case 1:
                        $tpl->newBlock("CLICK_MODE");
                        break;
                    case 0:
                        $tpl->newBlock("OVER_MODE");
                        break;
                }
            }
            $i=0;
            while($row = $db->fetch_array($selectrs,1)){
                if($cms_cfg['ws_module']['ws_seo']==1){
                    if(trim($row["pc_seo_filename"]) !=""){
                        $dirname1=$row["pc_seo_filename"];
                        $pc_link=$cms_cfg["base_root"].$row["pc_seo_filename"].".htm";
                    }else{
                        $dirname1=$row["pc_id"];
                        $pc_link=$cms_cfg["base_root"]."category-".$row["pc_id"].".htm";
                    }
                }else{
                    $pc_link=$cms_cfg["base_root"]."products.php?func=p_list&pc_parent=".$row["pc_id"];
                }
                $tpl->newBlock( "LEFT_CATE_LIST" );
                $tpl->assign( array( "VALUE_CATE_NAME"       => $row["pc_name"],
                                     "VALUE_CATE_LINK"       => $pc_link,
                                     "VALUE_CATE_LINK_CLASS" => (($_REQUEST['pc_parent']==$row['pc_id'] || ($cms_cfg['ws_module']['ws_seo']==1 && $_REQUEST['f']==$row['pc_seo_filename']))?"current":""),
                                     "TAG_CURRENT_CLASS"     => ($_REQUEST['pc_parent']==$row['pc_id'] || ($cms_cfg['ws_module']['ws_seo']==1 && ($_REQUEST['f']==$row['pc_seo_filename'] || $_REQUEST['d']==$row['pc_seo_filename'])))?"class='current'":"",
                ));
                //左方產品次分類為click menu
                if($cms_cfg['ws_module']['ws_seo']==1){
                    if($_REQUEST["d"] || $_REQUEST["f"]) {
                        if($row["pc_seo_filename"]==$_REQUEST["d"] || $row["pc_seo_filename"]==$_REQUEST["f"]){
                            if($cms_cfg["ws_module"]["ws_left_menu_type"]==1  ) {
                                $tpl->assignGlobal("CLICK_NUM1", $i);
                            }else{
                                $tpl->assignGlobal("OVER_NUM1", $i);
                            }
                        }
                    }else{
                        if($cms_cfg["ws_module"]["ws_left_menu_effects"]==1 && $row["pc_id"]==$_REQUEST["pc_parent"]) {
                            if($cms_cfg["ws_module"]["ws_left_menu_type"]==1  ) {
                                $tpl->assignGlobal("CLICK_NUM1", $i);
                            }else{
                                $tpl->assignGlobal("OVER_NUM1", $i);
                            }
                        }
                    }
                }else{
                    if($cms_cfg["ws_module"]["ws_left_menu_effects"]==1 && $row["pc_id"]==$_REQUEST["pc_parent"]) {
                        if($cms_cfg["ws_module"]["ws_left_menu_type"]==1  ) {
                            $tpl->assignGlobal("CLICK_NUM1", $i);
                        }else{
                            $tpl->assignGlobal("OVER_NUM1", $i);
                        }
                    }
                }
                //判斷是否顯示次分類
                if($cms_cfg["ws_module"]["ws_left_sub_pc"]==1){
                    $sql1="select * from ".$cms_cfg['tb_prefix']."_products_cate where pc_parent='".$row["pc_id"]."' and pc_status='1' order by pc_up_sort desc,pc_sort ".$cms_cfg['sort_pos']." ";
                    $selectrs1 = $db->query($sql1);
                    $rsnum1    = $db->numRows($selectrs1);
                }else{
                    $rsnum1 = 0;
                }
                if($rsnum1 > 0 ){ //有次分類
                    if($cms_cfg["ws_module"]["ws_left_menu_type"]==1) {
                        $tpl->assignGlobal("TAG_LEFT_MENU_TYPE", "id=\"firstpane\""); //click menu
                    }else{
                        $tpl->assignGlobal("TAG_LEFT_MENU_TYPE", "id=\"secondpane\""); //over menu
                    }
                    while($row1 = $db->fetch_array($selectrs1,1)){
                        if($cms_cfg['ws_module']['ws_seo']==1){
                            if(trim($row1["pc_seo_filename"]) !=""){
                                $dirname1=$row1["pc_seo_filename"];
                                $pc_link1=$cms_cfg["base_root"].$row1["pc_seo_filename"].".htm";
                            }else{
                                $dirname1=$row1["pc_id"];
                                $pc_link1=$cms_cfg["base_root"]."category-".$row1["pc_id"].".htm";
                            }
                        }else{
                            $pc_link1=$cms_cfg["base_root"]."products.php?func=p_list&pc_parent=".$row1["pc_id"];
                        }
                        $tpl->newBlock("LEFT_SUBCATE_LIST");
                        $tpl->assign( array( "VALUE_SUBCATE_NAME" => $row1["pc_name"],
                                             "VALUE_SUBCATE_LINK" => $pc_link1,
                                             "TAG_CURRENT_CLASS"  => ($row1["pc_seo_filename"]==$_REQUEST["d"] || $row1["pc_seo_filename"]==$_REQUEST["f"] || $_REQUEST['pc_parent']==$row1['pc_id'])?"class='current'":"",
                        ));
                        //左方產品次分類為click menu
                        if($cms_cfg['ws_module']['ws_seo']==1){
                            if($_REQUEST["d"] || $_REQUEST["f"]) {
                                if($row1["pc_seo_filename"]==$_REQUEST["d"] || $row1["pc_seo_filename"]==$_REQUEST["f"]){
                                    if($cms_cfg["ws_module"]["ws_left_menu_type"]==1 ) {
                                        $tpl->assignGlobal("CLICK_NUM1", $i);
                                    }else{
                                        $tpl->assignGlobal("OVER_NUM1", $i);
                                    }
                                }
                            }else{
                                if($cms_cfg["ws_module"]["ws_left_menu_type"]==1 && $row1["pc_id"]==$_REQUEST["pc_parent"]) {
                                    $tpl->assignGlobal("CLICK_NUM1", $i);
                                }
                            }
                        }else{
                            if($cms_cfg["ws_module"]["ws_left_menu_effects"]==1 && $row1["pc_id"]==$_REQUEST["pc_parent"]){
                                if($cms_cfg["ws_module"]["ws_left_menu_type"]==1) {
                                    $tpl->assignGlobal("CLICK_NUM1", $i);
                                }else{
                                    $tpl->assignGlobal("OVER_NUM1", $i);
                                }
                            }
                        }
                    }
                    $tpl->gotoBlock("LEFT_CATE_LIST");
                    $tpl->assign("TAG_SUB_UL1","<div class=\"menu_body\"><ul>");
                    $tpl->assign("TAG_SUB_UL2","</ul></div>");
                    $tpl->assign("VALUE_CATE_LINK_CLASS" ,$_REQUEST['pc_parent']==$row['pc_id']?"current":"");
                }else{ //無次分類
                    //判斷是否顯示次分類的產品
                    if($cms_cfg["ws_module"]["ws_left_products"]==1){
                        $sql2="select * from ".$cms_cfg['tb_prefix']."_products where pc_id='".$row["pc_id"]."' and p_status='1' order by p_up_sort desc,p_sort ".$cms_cfg['sort_pos']." ";
                        $selectrs2 = $db->query($sql2);
                        $rsnum2    = $db->numRows($selectrs2);
                    }else{
                        $rsnum2 = 0;
                    }
                    if($rsnum2 > 0 ){ //有次分類產品
                        if($cms_cfg["ws_module"]["ws_left_menu_type"]==1) {
                            $tpl->assignGlobal("TAG_LEFT_MENU_TYPE", "id=\"firstpane\""); //click menu
                        }else{
                            $tpl->assignGlobal("TAG_LEFT_MENU_TYPE", "id=\"secondpane\""); //over menu
                        }
                        while($row2 = $db->fetch_array($selectrs2,1)){
                            if($cms_cfg['ws_module']['ws_seo']==1){
                                if(trim($row2["p_seo_filename"]) !=""){
                                    $p_link=$cms_cfg["base_root"].$dirname1."/".$row2["p_seo_filename"].".html";
                                }else{
                                    $p_link=$cms_cfg["base_root"].$dirname1."/products-".$row2["p_id"]."-".$row2["pc_id"].".html";
                                }
                            }else{
                                $p_link=$cms_cfg["base_root"]."products.php?func=p_detail&p_id=".$row2["p_id"]."&pc_parent=".$row2["pc_id"];
                            }
                            $tpl->newBlock( "LEFT_SUBCATE_LIST" );
                            $tpl->assign( array( "VALUE_SUBCATE_NAME" => $row2["p_name"],
                                                 "VALUE_SUBCATE_LINK"  => $p_link,
                            ));
                            //左方產品次分類為click menu
                            if($cms_cfg['ws_module']['ws_seo']==1){
                                if($_REQUEST["f"]!="") {
                                    if($cms_cfg["ws_module"]["ws_left_menu_type"]==1 && $row2["p_seo_filename"]==$_REQUEST["f"]) {
                                        $tpl->assignGlobal("CLICK_NUM1", $i);
                                    }
                                }else{
                                    if($cms_cfg["ws_module"]["ws_left_menu_type"]==1 && $row2["pc_id"]==$_REQUEST["pc_parent"]) {
                                        $tpl->assignGlobal("CLICK_NUM1", $i);
                                    }
                                }
                            }else{
                                if($cms_cfg["ws_module"]["ws_left_menu_type"]==1 && $row2["pc_id"]==$_REQUEST["pc_parent"]) {
                                    $tpl->assignGlobal("CLICK_NUM1", $i);
                                }
                            }
                        }
                        $tpl->gotoBlock("LEFT_CATE_LIST");
                        $tpl->assign("TAG_SUB_UL1","<div class=\"menu_body\"><ul>");
                        $tpl->assign("TAG_SUB_UL2","</ul></div>");
                        $tpl->assign("VALUE_CATE_LINK_CLASS" ,$_REQUEST['pc_parent']==$row2['pc_id']?"current":"");
                    }
                }
                $i++;
            }
        }else{//無主分類,顯示未分類產品
            if($cms_cfg["ws_module"]["ws_left_products"]==1){
                $sql3="select * from ".$cms_cfg['tb_prefix']."_products where pc_id='0' and p_status='1' order by p_up_sort desc,p_sort ".$cms_cfg['sort_pos']." ";
                $selectrs3 = $db->query($sql3);
                $rsnum3    = $db->numRows($selectrs3);
            }else{
                $rsnum3 = 0;
            }
            if($rsnum3 > 0 ){
                //顯示左方次分類
                while($row3 = $db->fetch_array($selectrs3,1)){
                    if($cms_cfg['ws_module']['ws_seo']==1){
                        if(trim($row3["p_seo_filename"]) !=""){
                            $p_link=$cms_cfg["base_root"]."products/".$row3["p_seo_filename"].".html"; //未分類產品資料夾預設為products
                        }else{
                            $p_link=$cms_cfg["base_root"]."products/products-".$row3["p_id"]."-".$row3["pc_id"].".html";//未分類產品資料夾預設為products
                        }
                    }else{
                        $p_link=$cms_cfg["base_root"]."products.php?func=p_detail&p_id=".$row3["p_id"]."&pc_parent=".$row3["pc_id"];
                    }
                    $tpl->newBlock("LEFT_PRODUCTS_LIST");
                    $tpl->assign( array( "VALUE_PRODUCTS_NAME" => $row3["p_name"],
                                         "VALUE_PRODUCTS_LINK"  => $p_link,
                                         "VALUE_CATE_LINK_CLASS" => (($_REQUEST['p_id']==$row3['p_id'] || $_GET['f']==$row3['p_seo_filename'])?"class='current'":""),
                    ));
                }
            }
        }
    }    
    //頭尾檔設定
    function header_footer($meta_array,$seo_h1=""){
        global $db,$tpl,$cms_cfg,$ws_array,$TPLMSG;
        static $e =0;//本方法的執行次數
        $e++;        
        if($cms_cfg["ws_module"]["ws_seo"] ==0 ){
            unset($meta_array);
            // IPB META SETUP
            $sql ="select sc_meta_title,sc_meta_keyword,sc_meta_description from ".$cms_cfg['tb_prefix']."_system_config where sc_status='1' and sc_id='1'";
            $selectrs = $db->query($sql);
            $rsnum = $db->numRows($selectrs);
            if($rsnum > 0) {
                $row = $db->fetch_array($selectrs,1);
                $tpl->assignGlobal(array(
                        "HEADER_META_TITLE" => $row["sc_meta_title"],
                        "HEADER_META_KEYWORD" => $row["sc_meta_keyword"],
                        "HEADER_META_DESCRIPTION" => $row["sc_meta_description"],
                        "TAG_MAIN_FUNC" => $seo_h1,
                ));
            }
        }else{
            //各項功能主頁專屬的seo 設定
            if(!is_array($meta_array)){
                //頭檔
                $meta_array=$this->func_metatitle($meta_array);
            }
            $tpl->assignGlobal(array("TAG_BASE_CSS" => $cms_cfg['base_css'],
                                     "HEADER_META_TITLE" => ($meta_array["meta_title"])?$meta_array["meta_title"]:$_SESSION[$cms_cfg['sess_cookie_name']]["sc_meta_title"],
                                     "HEADER_META_KEYWORD" => ($meta_array["meta_keyword"])?$meta_array["meta_keyword"]:$_SESSION[$cms_cfg['sess_cookie_name']]["sc_meta_keyword"],
                                     "HEADER_META_DESCRIPTION" => ($meta_array["meta_description"])?$meta_array["meta_description"]:$_SESSION[$cms_cfg['sess_cookie_name']]["sc_meta_description"],
                                     "HEADER_SHORT_DESC" => ($meta_array["seo_short_desc"])?$meta_array["seo_short_desc"]:"",
                                     "TAG_MAIN_FUNC" => ($meta_array["seo_h1"])?$meta_array["seo_h1"]:$seo_h1,
            ));
            if($meta_array["seo_short_desc"]){
            $tpl->newBlock("SEO_SHORT_DESC");
            $tpl->assign("VALUE_SEO_SHORT_DESC",$meta_array["seo_short_desc"]);
        }
        }
        if($e==1){  //第一次執行才做
            if($_SESSION[$cms_cfg['sess_cookie_name']]["sc_im_status"]==1 && $_SESSION[$cms_cfg['sess_cookie_name']]["sc_im_starttime"] < date("H:i:s") && $_SESSION[$cms_cfg['sess_cookie_name']]["sc_im_endtime"] > date("H:i:s")){
                $tpl->newBlock( "IM_ZONE" );
                $tpl->assign(array("VALUE_SC_IM_SKYPE" =>"skype:<a href=\"callto:".$_SESSION[$cms_cfg['sess_cookie_name']]["sc_im_skype"]."\"><img src=\"".$cms_cfg['base_images']."skype_call_me.png\" alt=\"Skype Me™!\" border='0' width='70' height='23'/></a>",
                                   "VALUE_SC_IM_MSN" =>"msn:".$_SESSION[$cms_cfg['sess_cookie_name']]["sc_im_msn"],
                ));
            }
            $tpl->assignGlobal("MSG_HOME",$TPLMSG['HOME']);
            $tpl->assignGlobal("TAG_THEME_PATH" , $cms_cfg['default_theme']);
            $tpl->assignGlobal("TAG_ROOT_PATH" , $cms_cfg['base_root']);
            $tpl->assignGlobal("TAG_FILE_ROOT" , $cms_cfg['file_root']);
            $tpl->assignGlobal("TAG_BASE_URL" ,$cms_cfg["base_url"]);
            $tpl->assignGlobal("TAG_LANG",$cms_cfg['language']);
            $tpl->assignGlobal("MSG_SITEMAP",$TPLMSG["SITEMAP"]);
            $tpl->assignGlobal("MSG_PRODUCT_SEARCH",$TPLMSG['PRODUCTS_SEARCH']);
            $tpl->assignGlobal("MSG_PRODUCT_SEARCH_KEYWORD",$TPLMSG['ENTER_KEYWORD']);
            //設定主選單變數
            if(!empty($ws_array["main"])){
                foreach($ws_array["main"] as $item => $itemName){
                    $tpl->assignGlobal("TAG_MENU_".  strtoupper($item),  $itemName);
                }
            }
            //設定頁腳變數
            $tpl->assignGlobal("TAG_FOOTER_ADDRESS",$TPLMSG['COMPANY_ADDRESS']);
            $tpl->assignGlobal("TAG_FOOTER_FAX",$TPLMSG['FAX']);
            $tpl->assignGlobal("TAG_FOOTER_TEL",$TPLMSG['TEL']);
            $tpl->assignGlobal("TAG_FOOTER_EMAIL",$TPLMSG['EMAIL']);
            //有會員即顯示會員登入區
            if($cms_cfg["ws_module"]["ws_member"]==1){
                $this->login_zone();
            }
            $this->mouse_disable(); //鎖滑鼠右鍵功能
            $this->clearfield(); //搜尋區塊, 投入true值啟用autocomplete 
            //下拉式選單
            /*參數說明
             * 第一個參數型態是字串，可輸入aboutus,products,news，輸入多個項目時用半型逗號區隔
             * 第二個參數型態是陣列，設定於config.inc.php裡，為自訂下拉式選單項目，格式於config.inc.php有範例
             * $cms_cfg['extra_dd_menu']陣列索引是下拉選單div的id名稱，完整的div id名稱是dd_[div名稱]，不包含[]
             */            
            $this->dropdown_menu(null,$cms_cfg['extra_dd_menu']);
            //$this->float_menu();
            //$this->goodlink_select();
            //尾檔
            //$tpl->assignGlobal("VALUE_SC_FOOTER" ,$_SESSION[$cms_cfg['sess_cookie_name']]["sc_footer"]);
            $this->main_cate_list();
            $this->ad_list(2);
        }
    }
    //主選單裡的產品主分類
    function main_cate_list(){
        global $db,$tpl,$cms_cfg,$TPLMSG;
        $sql = "select pc_name,pc_status,pc_seo_filename from ".$cms_cfg['tb_prefix']."_products_cate order by pc_sort ".$cms_cfg['sort_pos'];
        $res = $db->query($sql,true);
        while(list($pc_name,$status,$block_name)=$db->fetch_array($res,false)){
            $link = ($status)?$cms_cfg['base_root'].$block_name.".htm":"#";
            $tpl->assignGlobal("TOP_MENU_".strtoupper($block_name)."_LINK",$link);
            $tpl->assignGlobal(strtoupper($block_name)."_CLICK_EVENT",($status)?"":sprintf($TPLMSG['INVALID_CATE_NOTIFICATION'],$pc_name));
        }
    }
    function brand_cate_list(){
        global $db,$tpl,$cms_cfg,$TPLMSG,$main;
        //前台關於我們列表
        $sql="select * from ".$cms_cfg['tb_prefix']."_aboutus  where au_status='1' and au_cate = 'aboutus' order by au_sort ".$cms_cfg['sort_pos'].",au_modifydate desc";
        $selectrs = $db->query($sql);
        if(empty($_REQUEST["au_id"]) && empty($_REQUEST["f"])){
           $sel_top_record=true;
        }
        $current_row = null;
        $i=0;
        while ( $row = $db->fetch_array($selectrs,1) ) {
            $i++;
            $tmp = array();
            $tmp['name'] = $row["au_subject"];
            $tmp['link'] = ($i==1)?$cms_cfg["base_root"]."aboutus.htm":$this->get_au_link($row);
            if(($i==1 && $sel_top_record) || ($_REQUEST["au_id"]==$row["au_id"]) || ($cms_cfg['ws_module']['ws_seo'] && ($_REQUEST["f"]==$row["au_seo_filename"]))){
                $tpl->assign("TAG_CURRENT_CLASS", "class=\"current\"");
                $current_row = $row;
                if($cms_cfg['ws_module']['ws_seo']){
                    $meta_array=array("meta_title"=>$row["au_seo_title"],
                                      "meta_keyword"=>$row["au_seo_keyword"],
                                      "meta_description"=>$row["au_seo_description"],
                                      "seo_h1"=>(trim($row["au_seo_h1"])=="")?$row["au_subject"]:$row["au_seo_h1"],
                    );
                    $main->header_footer($meta_array);
                }else{
                    $main->header_footer("aboutus",$row["au_subject"]);
                }
                $tmp['tag_cur']="class='current'";
            }
            $menu_items[]=$tmp;
        }    
        //加入新選單項目:直營門市據點
        $shops = array(
            'name'    => "直營門市據點",
            'link'    => $cms_cfg['base_root']."stores.htm",
            'tag_cur' => ($this->get_main_fun()=="stores")?"class='current'":"",
        );
        $last = array_pop($menu_items);
        array_push($menu_items,$shops);
        array_push($menu_items,$last);
        $this->new_left_menu($menu_items);
        return $current_row;
    }
    //取得aboutus連結
    function get_au_link($row){
        global $cms_cfg;
        if($cms_cfg['ws_module']['ws_seo']==1 ){
            $cate_link=$cms_cfg["base_root"]."aboutus/".$row["au_seo_filename"].".html";
        }else{
            if($cms_cfg["ws_module"]['ws_aboutus_au_cate']){
                $cate_link=$cms_cfg["base_root"]."aboutus.php?au_cate=".$row['au_cate']."&au_id=".$row["au_id"];
            }else{
                $cate_link=$cms_cfg["base_root"]."aboutus.php?au_id=".$row["au_id"];
            }
        } 
        return $cate_link;
    }    
    //登入專區
    function login_zone(){
        global $tpl,$db,$cms_cfg,$TPLMSG;
        if(empty($_SESSION[$cms_cfg['sess_cookie_name']]['MEMBER_ID'])){
            $tpl->newBlock( "LOGIN_ZONE" );
            $tpl->assignGlobal( "MSG_ERROR_MESSAGE",$_SESSION[$cms_cfg['sess_cookie_name']]["ERROR_MSG"]);
//            $_SESSION[$cms_cfg['sess_cookie_name']]["ERROR_MSG"]=""; //清空錯誤訊息
            $tpl->assignGlobal( "MSG_LOGIN_ACCOUNT",$TPLMSG["LOGIN_ACCOUNT"]);
            $tpl->assignGlobal( "MSG_LOGIN_PASSWORD",$TPLMSG["LOGIN_PASSWORD"]);
            $tpl->assignGlobal( "MSG_LOGIN_BUTTON",$TPLMSG["LOGIN_BUTTON"]);
            $tpl->assignGlobal( "MSG_LOGIN_FORGOT_PASSWORD",$TPLMSG["LOGIN_FORGOT_PASSWORD"]);
            $tpl->assignGlobal( "MSG_LOGIN_REGISTER",$TPLMSG["LOGIN_REGISTER"]);
            //載入驗証碼
            $this->security_zone($cms_cfg['security_image_width'],$cms_cfg['security_image_height']);
            //*載入服務條款
            $sql = "select st_service_term as service_term from ".$cms_cfg['tb_prefix']."_service_term where st_id='1'";
            list($term) = $db->query_firstrow($sql,false);
            $tpl->assignGlobal("MSG_SERVICE_TERM",$term);
        }else{
            $tpl->newBlock( "MEMBER_INFO" );
            $tpl->assign("TAG_LOGIN_MEMBER_CATE",$_SESSION[$cms_cfg['sess_cookie_name']]['MEMBER_CATE']);
            $tpl->assign("TAG_LOGIN_MEMBER_NAME",$_SESSION[$cms_cfg['sess_cookie_name']]['MEMBER_NAME']);
            $tpl->assign("TAG_LOGIN_MEMBER_DATA",$TPLMSG['MEMBER_ZONE_DATA']);
            switch($_SESSION[$cms_cfg['sess_cookie_name']]['sc_cart_type']){
                case "0":
                    $tpl->newBlock("CART_TYPE_INQUIRY");
                    $tpl->assign("TAG_LOGIN_MEMBER_INQUIRY",$TPLMSG['MEMBER_ZONE_INQUIRY']);
                    $tpl->gotoBlock( "MEMBER_INFO" );
                    break;
                case "1":
                    $tpl->newBlock("CART_TYPE_ORDER");
                    $tpl->assign("TAG_LOGIN_MEMBER_ORDER",$TPLMSG['MEMBER_ZONE_ORDER']);
                    $tpl->gotoBlock( "MEMBER_INFO" );
                    break;
            }
            if($cms_cfg['ws_module']['ws_contactus']){
                $tpl->newBlock("MEMBER_CONTACTUS");
                $tpl->assign("TAG_LOGIN_MEMBER_CONTACTUS",$TPLMSG['MEMBER_ZONE_CONTACTUS']);
                $tpl->gotoBlock( "MEMBER_INFO" );
            }
            if($cms_cfg['ws_module']['ws_member_download']){
                $tpl->newBlock("MEMBER_DOWNLOAD");
                $tpl->assign("TAG_LOGIN_MEMBER_DOWNLOAD",$TPLMSG['DOWNLOAD']);
            }
            $tpl->assign("TAG_LOGIN_ALI_QUERY",$TPLMSG["MEMBER_ALI_QUERY"]);
        }
    }    
    //寄送確認信,電子報
    function ws_mail_send($from,$to,$mail_content,$mail_subject,$mail_type,$goto_url,$admin_subject=null,$none_header=0,$note_msg=""){
        global $TPLMSG,$cms_cfg;
        if($mail_type =="epaper"){
            set_time_limit(0);
        }
        $mail_subject = sprintf("%s - %s",$_SESSION[$cms_cfg['sess_cookie_name']]['sc_company'],$mail_subject);
        $from_email=explode(",",$from);
        $from_name=(trim($_SESSION[$cms_cfg['sess_cookie_name']]["sc_company"]))?$_SESSION[$cms_cfg['sess_cookie_name']]["sc_company"]:$from_email[0];
        $mail_subject = "=?UTF-8?B?".base64_encode($mail_subject)."?=";
        //寄給送信者
        $MAIL_HEADER   = "MIME-Version: 1.0\n";
        $MAIL_HEADER  .= "Content-Type: text/html; charset=\"utf-8\"\n";
        $MAIL_HEADER  .= "From: =?UTF-8?B?".base64_encode($from_name)."?= <".$from_email[0].">"."\n";
        $MAIL_HEADER  .= "Reply-To: ".$from_email[0]."\n";
        $MAIL_HEADER  .= "Return-Path: ".$from_email[0]."\n";    // these two to set reply address
        $MAIL_HEADER  .= "X-Priority: 1\n";
        $MAIL_HEADER  .= "Message-ID: <".time()."-".$from_email[0].">\n";
        $MAIL_HEADER  .= "X-Mailer: PHP v".phpversion()."\n";          // These two to help avoid spam-filters
        $to_email = explode(",",$to);
        for($i=0;$i<count($to_email);$i++){
            if($i!=0 && $i%2==0){
                sleep(2);
            }
            if($i!=0 && $i%5==0){
                sleep(10);
            }
            if($i!=0 && $i%60==0){
                sleep(300);
            }
            if($i!=0 && $i%600==0){
                sleep(2000);
            }
            if($i!=0 && $i%1000==0){
                sleep(10000);
            }
            @mail($to_email[$i], $mail_subject, $mail_content,$MAIL_HEADER);
        }
        //除了電子報、忘記密碼外寄給管理者
        if($mail_type !="epaper" && $mail_type!="pw"){
            $MAIL_HEADER   = "MIME-Version: 1.0\n";
            $MAIL_HEADER  .= "Content-Type: text/html; charset=\"utf-8\"\n";
            $MAIL_HEADER  .= "From: =?UTF-8?B?".base64_encode($to_email[0])."?= <".$to_email[0].">"."\n";
            $MAIL_HEADER  .= "Reply-To: ".$to_email[0]."\n";
            $MAIL_HEADER  .= "Return-Path: ".$to_email[0]."\n";    // these two to set reply address
            $MAIL_HEADER  .= "X-Priority: 1\n";
            $MAIL_HEADER  .= "Message-ID: <".time()."-".$to_email[0].">\n";
            $MAIL_HEADER  .= "X-Mailer: PHP v".phpversion()."\n";          // These two to help avoid spam-filters
            if($admin_subject){
                $mail_subject = $admin_subject;
            }else{
                $mail_subject .= " from ".$_SERVER["HTTP_HOST"]."--[For Administrator]";
            }
            $mail_content = preg_replace("#<span class=\"not_for_admin\">.+</span>#", "******", $mail_content);
            for($i=0;$i<count($from_email);$i++){
                @mail($from_email[$i], $mail_subject, $mail_content,$MAIL_HEADER);
            }
        }

        if(empty($none_header)){
            $goto_url=(empty($goto_url))?$cms_cfg["base_url"]:$goto_url;
            $msg = $note_msg?$note_msg:$TPLMSG['ACTION_TERM_JS'];
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
            echo "<script language=javascript>";
            echo "Javascript:alert('".$msg."')";
            echo "</script>";
            echo "<script language=javascript>";
            echo "document.location='".$goto_url."'";
            echo "</script>";
        }
    }    
}
?>