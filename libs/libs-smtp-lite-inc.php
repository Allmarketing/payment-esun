<?
  ##############################################################################
  #  Project : ABG_SMTPMail                                                    #
  #  File    : ABG_SMTP_Lite.inc.php                                           #
  #  V1.0.0 20/03/2006          : Initial                                      #
  #  V1.1.0 21/03/2006          : Improve Logs and Error                       #
  #  V2.0.0 23/03/2006          : Add Email adress FULL check                  #
  #  V2.1.0 21/05/2006          : Bug fixes & enhancements (Cc & Bcc headers)  #
  #                               Thanks to <marco@oostende.nl>                #
  #                                       & <henry@henryflurry.com>            #
  #  V2.1.1 21/05/2006          : Minor bug fixes                              #
  #  (cy)  G. BENABOU / ABG Soft PARIS FRANCE                                  #
  #                                                                            #
  #  A PHP 4 script to send mail via SMTP Server                               #
  #  - Full RFC 2821 protocol with dialog control                              #
  #  - Support client authentification                                         #
  #  - Multi-recipients (To,Cc & Bcc) in comma/semi-colon separted lists       #
  #  - Maintain a log of protocol transactions (useful for debbuging)          #
  #  - Comprehensive disgnostic of errors                                      #
  #  - Debug mode (no communication with servers)                              #
  #                                                                            #
  #  Object Properties                                                         #
  #  Name           Description                                                #
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
  #  ABG_SMTPMail( $_Server)       // SMTP Server                              #
  #    Object constructor : set Server                                         #
  #                                                                            #
  #  ob_SendMail( $_Login = "",    // Authent. Login (blank if no auth.)       #
  #               $_Pwd = "",      // Authent. Password                        #
  #               $_From = "",     // Sender email                             #
  #               $_To ,           // Unique recipient email                   #
  #               $_CC = "" ,      // NOT USED ! for compatibility             #
  #               $_BCC = "" ,     // NOT USED ! for compatibility             #
  #               $_Subject = "",                                              #
  #               $_Body = "")     // Body of the message                      #
  #    Core method to parse parameters and do the sending                      #
  #                                                                            #
  #  ob_SetHost($_Host)            // Sets "ob_Host" property                  #
  #                                                                            #
  ##############################################################################

  define("K_DEBUG", FALSE);
  class ABG_SMTPMail {
    var $ob_Error  = "";
    var $ob_Host   = "localhost";
    var $ob_Status = "<pre>";
    var $ob_Port   = 25;
    var $ob_Server = "";
  //...  Internal variables
    var $ob_Con    = null;
    var $ob_State  = "DISCONNECTED";
    var $ob_Data = array(array ( null, "HELO : \$_Host",       250)   // 0
                        ,array (    4, "AUTH LOGIN",           334)   // 1
                        ,array ( null, "\$_Login",             334)   // 2
                        ,array ( null, "\$_Pwd",               235)   // 3
                        ,array ( null, "MAIL FROM: <\$_From>", 250)   // 4
                        ,array ( null, "RCPT TO: <\$_To>",     250)   // 5
                        ,array ( null, "DATA",                 354)   // 6
                        ,array ( null, "To: \$_To",            null)  // 7
                        ,array ( null, "From: \$_From",        null)  // 8 v2.1
                        ,array ( null, "Subject: \$_Subject",  null)  // 9
                        ,array ( null, "\$_Body\n",            null)  //10
                        ,array ( null, ".",                    250)   //11
                        ,array (   -1, "QUIT",                 221)); //12

    /*** Initialize object ***/
    function ABG_SMTPMail($_Server) {
      $this->ob_Server  = ($_Server == "") ? ini_get("SMTP") : $_Server;
    }
    /*** Handle error string ***/
    function ob_HandleError($_Msg) {
      $this->ob_Error  = "<pre>*** Error ***\n".htmlentities($_Msg)."\n</pre>";
      $this->ob_close();
      return(FALSE);
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
      $this->ob_State  = "CONNECTED";
      return($this->ob_Get_Line(220));                                                 // v2.1
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
                         ,$_To            // Unique recipient email
                         ,$_CC = ""       // NOT USED ! for compatibility
                         ,$_BCC = ""      // NOT USED ! for compatibility
                         ,$_Subject = ""
                         ,$_Body = "")
    {
      GLOBAL $HTTP_HOST;
      //....
      if (!$this->ob_Connect()) return(FALSE);
      //....
      $_Host = isset($this->ob_Host) ? $this->ob_Host : $HTTP_HOST;
      $Idx_  = 0;
      while ($Idx_ >= 0) {
        list($Next_, $Str_, $Rcv_) = $this->ob_Data[$Idx_];
        $Send_ = null;
        switch ($Idx_){
          case 1   : if ($_Login == "") break;
                     $_Login = base64_encode($_Login);
                     $_Pwd   = base64_encode($_Pwd);
          default  : $Send_ = eval("return \"$Str_\";" );
        }
        if (!$this->ob_Protocol($Send_, $Rcv_)) return(FALSE);
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
      if (K_DEBUG or !isset($_Resp)) return (TRUE);
      $Line_  = trim(fgets($this->ob_Con));
      $this->ob_Status .= htmlentities("<$Line_")."\n";
      $Rec_ = intval(strtok($Line_," "));
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