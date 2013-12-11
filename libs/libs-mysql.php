<?
putenv("TZ=Asia/Taipei");
class DB {
  var $no_error = 0;
  var $on_error = "report";
  var $connection;
  var $query_result;
  var $query_count = 0;
  var $query_time = 0;
  var $query_array = array();
  var $prefix;

  function DB($db_host, $db_user, $db_password, $db_name, $tb_prefix,$db_persistent = 0) {

    if ($db_persistent) {
      $this->connection = @mysql_pconnect($db_host, $db_user, $db_password)
        or $this->error("Could not connect to the database server ($db_host, $db_user).");
    }
    else {
      $this->connection = @mysql_connect($db_host, $db_user, $db_password)
        or $this->error("Could not connect to the database server ($db_host, $db_user).");
    }
    if ($this->connection) {
      if ($db_name != "") {
        //mysql_query("SET NAMES 'latin1'");
		mysql_query("SET NAMES 'utf8'");
        $dbselect = @mysql_select_db($db_name);
        if (!$dbselect) {
          @mysql_close($this->connection);
          $this->connection = $dbselect;
          $this->error("Could not select database ($db_name).");
        }
        $this->prefix = $tb_prefix;
      }
      return $this->connection;
    }
    else {
      return false;
    }
  }

  function close() {
    if ($this->connection) {
      if ($this->query_result) {
        @mysql_free_result($this->query_result);
      }
      $result = @mysql_close($this->connection);
      return $result;
    }
    else {
      return false;
    }
  }

  function query($query = "",$showError=false) {
    unset($this->query_result);
    if ($query != "") {
      //if (defined("PRINT_QUERIES") || defined("PRINT_STATS")) {
      //  $startsqltime = explode(" ", microtime());
      //}

      list($usec, $sec) = explode(' ',microtime());
      $querytime_before = ((float)$usec + (float)$sec);

      $this->query_result = @mysql_query($query, $this->connection);
      if($showError){
            if($this->report()){
                echo $this->report();die();
            }          
      }
      list($usec, $sec) = explode(' ',microtime());
      $querytime_after = ((float)$usec + (float)$sec);

      $this->query_time = round($querytime_after - $querytime_before,3);

      //or $this->error("<b>Bad SQL Query</b>: ".htmlentities($query)."<br /><b>".mysql_error()."</b>");
      //if (defined("PRINT_QUERIES") || defined("PRINT_STATS")) {
      //  $endsqltime = explode(" ", microtime());
      //  $totalsqltime = round($endsqltime[0]-$startsqltime[0]+$endsqltime[1]-$startsqltime[1],3);
      //  $this->query_time += $totalsqltime;
      //  $this->query_count++;
      //}
      //if (defined("PRINT_QUERIES")) {
      //  $query_stats = htmlentities($query);
      //  $query_stats .= "<br><b>Querytime:</b> ".$totalsqltime;
      //  $this->query_array[] = $query_stats;
      //}
      return $this->query_result;
    }
  }

  function fetch_array($query_id = 0, $assoc = 0) {
    if (!$query_id) {
      $query_id = $this->query_result;
    }
    if ($query_id) {
      if ($assoc) {
        return mysql_fetch_assoc($query_id);
      }
      else {
        return mysql_fetch_array($query_id, MYSQL_NUM);
      }
    }
  }

  function freeResult($query_id = 0) {
    if (!$query_id) {
      $query_id = $this->query_result;
    }
    if ($query_id) {
      mysql_free_result($query_id);
      return true;
    }
  }

  function query_firstrow($query = "",$assoc=true) {
    if ($query != "") {
      $this->query($query);
    }
    $result = $this->fetch_array($this->query_result,$assoc);
    $this->freeResult();
    return $result;
  }

  function numRows($query_id = 0) {
    if (!$query_id) {
      $query_id = $this->query_result;
    }
    if ($query_id) {
      return mysql_num_rows($query_id);
    }
    else {
      return false;
    }
  }
  function tbl_numrows($table) {
    if ($table) {
      $query_id = $this->query("SELECT * FROM ".$table);
    }
    if ($query_id) {
      return mysql_num_rows($query_id);
    }
    else {
      return false;
    }
  }
  function get_insert_id() {
    if ($this->connection) {
      return @mysql_insert_id($this->connection);
    }
    else {
      return false;
    }
  }

  function get_numfields($query_id = 0) {
    if (!$query_id) {
      $query_id = $this->query_result;
    }
    if ($query_id) {
      return @mysql_num_fields($query_id);
    }
    else {
      return false;
    }
  }

  function get_fieldname($query_id = 0, $offset) {
    if (!$query_id) {
      $query_id = $this->query_result;
    }
    if ($query_id) {
      return @mysql_field_name($query_id, $offset);
    }
    else {
      return false;
    }
  }

  function get_fieldtype($query_id = 0, $offset) {
    if (!$query_id) {
      $query_id = $this->query_result;
    }
    if ($query_id) {
      return @mysql_field_type($query_id, $offset);
    }
    else {
      return false;
    }
  }

  function affected_rows() {
    if ($this->connection) {
      return @mysql_affected_rows($this->connection);
    }
    else {
      return false;
    }
  }

  function get_result($query = "") {
    if ($query != "") {
      $this->query($query);
    }
    $i = 0;
    $res = array();
    while ($row = mysql_fetch_array($this->query_result)) {
      $res[$i] = $row;
      $i++;
    }
    $this->freeResult();
    return $res;
  }

  function is_empty($query = "") {
    if ($query != "") {
      $this->query($query);
    }
    if (!mysql_num_rows($this->query_result)) {
      return true;
    }
    else {
      return false;
    }
  }

  function not_empty($query = "") {
    if ($query != "") {
      $this->query($query);
    }
    if (!mysql_num_rows($this->query_result)) {
      return false;
    }
    else {
      return true;
    }
  }
  function report(){
    return mysql_error();
  }

  function error($errmsg) {
    if (!$this->no_error) {
      echo "<br /><font color='#FF0000'><b>DB Error</b></font>: ".$errmsg."<br />";
      if ('halt' == $this->on_error) {
        exit;
      }
    }
  }
  
  function quote($str){
      return mysql_real_escape_string($str);
  }
  function batch_quote(array $arr){
      foreach($arr as $k => $v){
          $arr[$k] = "'".mysql_real_escape_string($v)."'";
      }
      return $arr;
  }
  function prefix($table_name){
      return sprintf("`%s_%s`",$this->prefix,$table_name);
  }
} // end of class

?>
