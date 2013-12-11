payment-esun
============

玉山信用卡串接

===============
前置設定
=============== 
1.設定conf/creditcard.php裡的 $cms_cfg['creditcard']['mid'] (特店編號)和 $cms_cfg['esunkey'] (MAC key).
2.變更class/model/order/payment/esun.php第24行，改為測試環境接收授權結果的url，依documents/網路收單系統交易規格.pdf第二頁的說明，URL不可包含【#】、【?】及【&】字元.

===============
測試流程
===============
1.執行card-test1.php，輸入訂單號碼及訂單價格.
2.前述訂單號碼及訂單價格由card-test2.php接收後，依documents/網路收單系統交易規格.pdf第二頁的說明產品表單資料，以post方式傳給玉山伺服器.
3.通過驗證就會進入線上刷頁頁面，若沒通過則直接導回結果頁.
4.結果頁，輸出回傳的資訊，及產品修改訂單的sql.

===============
api說明
===============
####
# Model_Order_Payment_Esun::__construct($config,$mackey,$mode="testing")
####
1.$config: 即conf/credictcard.php裡的$cms_cfg['creditcard'].
2.$mackey: 即conf/credictcard.php裡的$cms_cfg['esunkey'].
3.$mode: 預設是[testing]，代表測試模式，正式環境需改為running，此設定會決定傳送到哪一個玉山的主機，即Model_Order_Payment_Esun::$url裡的項目.

####
# Model_Order_Payment_Esun::checkout($o_id,$total_price,$extra_info=array())
####
1.$o_id:訂單號碼.
2.$total_price:訂單價格.
3.$extra_info:額外的欄位，預設是空陣列，也就是不加新欄位，如果要加新欄位，請以關聯式陣列輸入，例如: array('email'=>'xxxx@some.domain','tel'=>'88881888').

####
# Model_Order_Payment_Esun::update_order($db,$result)
####
1.$db: 即libs/libs-mysql.php類別的實體物件。請使用本專案的libs/libs-mysql.php，因為有使用到新增的prefix().
2.$result: 即玉山伺服器回傳的結果，即$_GET.
說明:測試流程是以此方法傳回更新訂單的sql，實際上因為已傳入$db，所以可以直接在方法裡面直接執行查詢.
　　 除了訂單編號重複的錯誤之外(RC=G6)，無論授權成功或失敗都留有更新訂單的敘述.
　　 授權成功的部份保留自訂，沒有決定要寫入什麼，或全部寫入.
　　 授權失敗的部份則是將訂單狀態修改為拒絕訂單.