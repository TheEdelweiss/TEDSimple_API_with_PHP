<?php  
#####################################################################
#          - iOS "consiliu.rezina.md" REST client API -
# -------------------------------------------------------------------
#              (c) TheEdelweiss  |  @Popa Stefan  2013
# -------------------------------------------------------------------
#
# Usage:
#       GET VERB:
#         -----------------------------------------------------------
#       -- ./'api'/'category'/ - list of all categories
#         -----------------------------------------------------------
#       -- ./'api'/'article'/        - list of last 50 articles in DB
#       -- ./'api'/'article'/art_id  - get article by art_id (number)
#       -- ./'api'/'article'/'category'/cat_id | 'all'/'page'/page_nr
#       -- ./'api'/'article'/'category'/cat_id | 'all'/'pagecount' 
#       -- -return number of pages in this 'category' specified by 
#       -- -cat_id, or 'all' -means all categories
#          ----------------------------------------------------------      
#       -- ./'api'/'search'/'where'/'keyword1_[keyword2_..]'
#          ---------------------------------------------------------- 
#
#       DELETE, POST, PUT verb's is not implemented         
#####################################################################

//===================================================================
  require_once ("./config/DB_config.php");
//===================================================================
function __autoload($class_name) 
    {
        $directorys = array("./library/","./config/");
        
        foreach($directorys as $directory)
        {
            if (file_exists($directory."class.".$class_name.'.php'))
            {
               require_once($directory."class.".$class_name.'.php'); 
               return;
            }            
        }
    }
//===================================================================
function getPageURL() {
	
	$pageURL = 'http://';
 
    if ($_SERVER["SERVER_PORT"] != "80") 
    {
     $pageURL .= $_SERVER["SERVER_NAME"].
     			 ":"
     			 .$_SERVER["SERVER_PORT"]
     			 .$_SERVER["REQUEST_URI"];
    } 
    else {
          $pageURL .= $_SERVER["SERVER_NAME"]
          		   .$_SERVER["REQUEST_URI"];
          }
    
    return $pageURL;
}
//===================================================================
  // ----------------------------------------------------------------
    // -- connect to MySQL DB >  
       
    $DBConnect = new TED_MySQLDatabaseConnection($DB_config);
  // ----------------------------------------------------------------

  // ----------------------------------------------------------------
    // -- process request >
    
    $response = new TED_RESTResponse(getPageURL(), 
                                     $_SERVER['REQUEST_METHOD']);
    $response->execute();
  // ----------------------------------------------------------------
  // -- close connection >
    
    $DBConnect->close();
  // ----------------------------------------------------------------
//===================================================================      
?>