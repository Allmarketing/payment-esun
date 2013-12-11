<?
  ##############################################################################
  #  Project : ABG_SMTPMail                                                    #
  #  File    : ABG_SMTP.inc.php                                                #
  #  V1.0.0 20/03/2006 : Initial                                               #
  #  V1.1.0 21/03/2006 : Improved Logs and Error                               #
  #  V2.0.0 23/03/2006 : Add Email adress FULL check                           #
  #  V2.1.0 21/05/2006 : Bug fixes & enhancements                              #
  #                      Cc, Bcc & From headers                                #
  #                      Thanks to <marco@oostende.nl> <henry@henryflurry.com> #
  #  V2.1.1 21/05/2006 : Minor bug fixes                                       #
  #  (cy)  G. BENABOU / ABG Soft PARIS FRANCE                                  #
  #                                                                            #
  #  A PHP 4 script to send mail via SMTP Server                               #
  #  - Full RFC 2821 protocol with dialog control                              #
  #  - Support client authentification                                         #
  #  - Multi-recipients (To,Cc & Bcc) in comma/semi-colon separted lists       #
  #  - Full check (syntax & server availability) of e-mail adresses            #
  #  - Maintain a log of protocol transactions (useful for debbuging)          #
  #  - Comprehensive disgnostic of errors                                      #
  #  - Debug mode (no communication with servers)                              #
  #                                                                            #
  #  Object Properties                                                         #
  #  Name         Description                                                  #
  #--------------------------------------------------------------------------- #
  #  ob_Error     HTML string when error                                       #
  #  ob_Greets    Server answer to connection                                  #
  #  ob_Host      Name of the client (default to "$HTTP_HOST")                 #
  #  ob_Status    HTML string of the protocol transactions                     #
  #  ob_Port      SMTP Port (default to 25)                                    #
  #  ob_Server    Name of SMTP Server                                          #
  #  ob_State     Connection status (CONNECTED / DICONNECTED)                  #
  #                                                                            #
  #  Object Methods                                                            #
  #  ABG_SMTPMail( $_Server,                   // SMTP Server                  #
  #                $_Port = 25,                // SMTP Port                    #
  #                $_State = "DISCONNECTED")   // Connection status            #
  #    Object constructor : sets corresponding properties                      #
  #                                                                            #
  #  ob_SendMail( $_Login = "",    // Authent. Login (blank if no auth.)       #
  #               $_Pwd = "",      // Authent. Password                        #
  #               $_From = "",     // Sender email                             #
  #               $_To ,           // List of comma separated recipient(s)     #
  #               $_CC = "" ,      // More recipient(s) ...                    #
  #               $_BCC = "" ,     // More recipient(s) in blibd          v2.1 #
  #               $_Subject = "",                                              #
  #               $_Body = "")     // Body of the message                      #
  #    Core method to parse parameters and do the sending                      #
  #                                                                            #
  #  ob_SetHost($_Host)            // Sets "ob_Host" property                  #
  #                                                                            #
  ##############################################################################

  define("K_SYNTAX", "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$"); // v2.1
  define("K_DEBUG", FALSE);
  class ABG_SMTPMail {
    var $ob_Error  = "";
    var $ob_Greets = "";
    var $ob_Host   = "localhost";
    var $ob_Status = "<pre>";
    var $ob_Port   = 25;
    var $ob_Server = "";
  //...  Internal variables
    var $ob_Con    = null;
    var $ob_RecLin = "";
    var $ob_State  = "DISCONNECTED";
    var $ob_Recips = array();
    var $ob_Data = array(array ( null, "HELO : \$_Host",       250)   // 0
                        ,array (    4, "AUTH LOGIN",           334)   // 1
                        ,array ( null, "\$_Login",             334)   // 2
                        ,array ( null, "\$_Pwd",               235)   // 3
                        ,array ( null, "MAIL FROM: <\$_From>", 250)   // 4
                        ,array ( null, "RCPT TO: ",            250)   // 5
                        ,array ( null, "DATA",                 354)   // 6
                        ,array ( null, "To: \$_To",            null)  // 7
                        ,array ( null, "Cc: \$_CC",            null)  // 8  v2.1
                        ,array ( null, "Bcc: Undisclosed",     null)  // 9  v2.1
                        ,array ( null, "From: \$_From",        null)  //10  v2.1
                        ,array ( null, "Subject: \$_Subject",  null)  //11
                        ,array ( null, "\$_Body\n",            null)  //12  v2.1
                        ,array ( null, ".",                    250)   //13
                        ,array (   -1, "QUIT",                 221)); //14

    /*** Initialize object ***/
    function ABG_SMTPMail( $_Server
                          ,$_Port = 25
                          ,$_State = "DISCONNECTED")
    {
      $this->ob_Server  = ($_Server == "") ? ini_get("SMTP") : $_Server;
      $this->ob_Port    = $_Port;
      $this->ob_State   = $_State;
    }
    /*** Handle error string ***/
    function ob_HandleError($_Msg) {
      $this->ob_Error  = "<pre>*** Error ***\n".htmlentities($_Msg)."\n</pre>";
      $this->ob_close();
      return(FALSE);
    }
    /*** Check email server validity ***/
    function ob_CheckPOP($_Email) {
      if (!function_exists('checkdnsrr'))
      {
        function checkdnsrr($Host_, $Type_ = '')
        {
          @exec("nslookup -type=$Type_ $Host_", $Out_);
          while(list($k, $Line_) = each($Out_))
            if (eregi("^$Host_", $Line_)) return true;
          return false;
        }
      }
      $Host_ = explode('@', $_Email);
      return(checkdnsrr($Host_[1], "ANY"));
    }
    /*** Check email validity (Syntax & Server) ***/
    function ob_CheckEmail($_Email) {                                    // v2.1
      if(!eregi(K_SYNTAX, trim($_Email)))
        return($this->ob_HandleError("$_Email : Invalid syntax"));
      if(!$this->ob_CheckPOP($_Email))
        return($this->ob_HandleError("$_Email : No POP server"));
     return(TRUE);
    }
    /*** Check Email list ***/
    function ob_CheckEmailList($_List) {
      $Ary_ = preg_split("/[\s,;]+/", $_List, -1, PREG_SPLIT_NO_EMPTY);
      foreach($Ary_ as $Recip_){
        if (!$this->ob_CheckEmail($Recip_)) return(FALSE);               // v2.1
        array_push($this->ob_Recips, $Recip_);
      }
      return( count($this->ob_Recips)>0  or
              $this->ob_HandleError("No recipients"));
    }
    /*** Connect to SMTP Server ***/
    function ob_Connect() {
      if ($this->ob_State != "DISCONNECTED")                             // v2.1
        return($this->ob_HandleError("Connection already open"));
      $this->ob_Con = K_DEBUG ?  TRUE  :
                                 fsockopen( $this->ob_Server,
                                            $this->ob_Port,
                                            &$_ErrNo, &$_ErrStr);
      if (!$this->ob_Con)
        return($this->ob_HandleError("Connect error($_ErrNo):$_ErrStr"));
      stream_set_timeout($this->ob_Con, 5);                              // v2.1
      stream_set_blocking ($this->ob_Con, TRUE);                         // v2.1
      $this->ob_State  = "CONNECTED";
      $Res_ = $this->ob_Get_Line(220);                                   // v2.1
      $this->ob_Greets = $Res_  ? $this->ob_RecLin : "No greetings!";    // v2.1
      return($Res_);
    }
    /*** Close connection to server ***/
    function ob_close() {
      if (!K_DEBUG) fclose($this->ob_Con);                               // v2.1
      $this->ob_Con      = null;
      $this->ob_State    = "DISCONNECTED";
      $this->ob_Status  .= "</pre>";
    }

    /*** Main loop to Send mail ***/
    function ob_SendMail( $_Login = ""    // Authent. Login (blank if no auth.)
                         ,$_Pwd = ""      // Authent. Password
                         ,$_From = ""     // Sender email
                         ,$_To            // Recipient(s) email
                         ,$_CC = ""       // More recipient(s) ...
                         ,$_BCC = ""      // More recipient(s) in blibd     v2.1
                         ,$_Subject = ""
                         ,$_Body = "")
    {
      GLOBAL $HTTP_HOST;
      //.... Checks validity of all recipients emails....
      if (!$this->ob_CheckEmailList("$_To; $_CC; $_BCC")) return(FALSE);
      //.... Try to connect to server
      if (!$this->ob_Connect()) return(FALSE);
      //... Start !
      $_Host = isset($this->ob_Host) ? $this->ob_Host : $HTTP_HOST;
      $Idx_  = 0;
      while ($Idx_ >= 0) {
        list($Next_, $Str_, $Rcv_) = $this->ob_Data[$Idx_];
        $arySend_ = array();
        switch ($Idx_){
          case 1   : $Eval_ = ($_Login <> "");                           // v2.1
                     if ($Eval_ ) {
                       $_Login = base64_encode($_Login);
                       $_Pwd   = base64_encode($_Pwd);
                       $Next_  = null;
                     }
                     break;
          case 5   : foreach($this->ob_Recips as $Recip_)
                       array_push($arySend_ , "$Str_<$Recip_>");
                     $Eval_ = FALSE;                                     // v2.1
                     break;
          case 7   : $Eval_ = (trim($_To) <> "");  break;                // v2.1
          case 8   : $Eval_ = (trim($_CC) <> "");  break;                // v2.1
          case 9   : $Eval_ = (trim($_To.$_CC) == "");  break;           // v2.1
          default  : $Eval_ = TRUE;                                      // v2.1
        }
        if ($Eval_)                                                      // v2.1
          $arySend_ = array(eval("return \"$Str_\";" ));
        foreach($arySend_ as $Send_)
          if (!$this->ob_Protocol($Send_, $Rcv_))
            return(FALSE);
        $Idx_ = isset($Next_) ? $Next_ : $Idx_+ 1;
      }
      $this->ob_close();
      return(TRUE);
    }
    /*** Set Host ***/
    function ob_SetHost($_Host)
    { $this->ob_Host = $_Host; }
    /*** Get answer from server ***/
    function ob_Get_Line($_Resp = null){
      if (K_DEBUG or !isset($_Resp)) {
        $this->ob_RecLin = "";
        return (TRUE);
      }
      $this->ob_RecLin  = trim(fgets($this->ob_Con));
      $this->ob_Status .= htmlentities("<$this->ob_RecLin")."\n";
      $Rec_ = intval(strtok($this->ob_RecLin," "));
      $Res_ = ($Rec_ == $_Resp);
      if(!$Res_) {
        $Err_  = "Waiting  = $_Resp\nReceived = $Rec_";
        $this->ob_HandleError($Err_);
      }
      return ($Res_);
    }
    /*** Handle element of protocol  ***/
    function ob_Protocol($_Snd, $_Wait) {
      if (!isset($_Snd)) return(TRUE);
    // Send
      $this->ob_Status .= htmlentities(">$_Snd");
      $this->ob_Status .= isset($_Wait) ? " ? $_Wait\n" : "\n";
      if (!K_DEBUG)
        if (!fputs($this->ob_Con, "$_Snd\r\n"))
          return($this->ob_HandleError("$_Snd => fputs() failed"));
      return($this->ob_Get_Line($_Wait));
    }
  //  End methods ..............................................................
  }
?>