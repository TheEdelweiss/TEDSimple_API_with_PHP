<?
##############################################################
#              - MySQL CONNECT CLASS -
# ------------------------------------------------------------
# (c) TheEdelweiss  |  @Popa Stefan  2012
# ------------------------------------------------------------
#
# Usage:
#       ->Required: 
#        
#          $config = [
#                   "MySQL_DB_host" => "host_name",
#                   "MySQL_DB_name" => "DB_name",
#                   "MySQL_DB_user" => "user_name",
#                   "MySQL_DB_pass" => "user_password",
#                  ];
#
#        $DBConnect = new TED_MySQLDatabaseConnection($config);
#
##############################################################

class TED_MySQLDatabaseConnection {
//----------------------------------------------------------	     
  //--------------------------------------------------->>>>>
      //-----------------------------------------------	         
        private $dbHost;
        private $dbUser;
        private $dbPass;
        private $dbName;
	  //-----------------------------------------------
  //--------------------------------------------------->>>>>
//----------------------------------------------------------	    
  function __construct($config) {

        $this->dbHost = $config['MySQL_DB_host'];
        $this->dbName = $config['MySQL_DB_name'];
        $this->dbUser = $config['MySQL_DB_user'];
        $this->dbPass = $config['MySQL_DB_pass'];

        $connection = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass)
            or die("<script>alert(\'Could not connect to the database:'.mysql_error().'\');</script>");
        $DBConnectStatus = mysql_select_db($this->dbName, $connection) 
            or die("<script>alert(\'Database error: '.mysql_error().'\');</script>");
       
        mysql_query("SET NAMES utf8");
        mysql_query("SET character_set_connection=utf8");
        mysql_query("SET character_set_client=utf8");
        mysql_query("SET character_set_results=utf8");
  } 
//----------------------------------------------------------
  //--------------------------------------------------->>>>>    
//----------------------------------------------------------       
  function __destruct(){
	  
  }	    
//----------------------------------------------------------
  //--------------------------------------------------->>>>>      
    }
?>