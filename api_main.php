<?php  
################################################################
#           - iOS "consiliu.rezina.md" client API -
# --------------------------------------------------------------
# (c) TheEdelweiss  |  @Popa Stefan  2012
# --------------------------------------------------------------
#
# Usage:
#       
#
#
################################################################

//==============================================================
require_once ("./config/DB_config.php");

function __autoload($class_name) 
    {
        $directorys = array("./library/","./config/");
        
        foreach($directorys as $directory)
        {
            if (file_exists ($directory . "class." . $class_name . '.php') )
            {
                require_once ($directory . "class." . $class_name . '.php'); 
                return;
            }            
        }
    }
//==============================================================

  //------------------------------------------------------------
    // Connect to MySQL DB >     
       $DBConnect = new TED_MySQLDatabaseConnection($DB_config);
  //------------------------------------------------------------
 // $shortArticles =  new TED_GetTableData("Articles");
  
 // print_r($shortArticles -> getTableRows("title","content"));
  
  //$search = new TED_SearchCore( $shortArticles -> getTableRows("title","content"),"Filantropie partea dar Lafarge Ciment (Moldova) SA");
  //$search -> printJSONEncodedObject();
  
  //===============================================
    if(isset($_POST["getMeCategories"])) {
  
      $cats = new TED_GetTableData("Categories");
      $cats -> getTableRows("cat_id","cat_name");
      $cats -> printJSONEncodedObject();
     
       }
  //===============================================
        else if (isset($_POST["getMeArticles"])) {
                
        //==================================================================================================================      
                if (isset($_POST["shortPreview"])) {    
                   // get nr of posts               
                   $shortArticles =  new TED_GetTableData("Articles","Categories");
                   
                   if (!empty($_POST["shortPreviewLength"])){   
                       
                        $shortArticles -> setTotalRowsToExtract(intval($_POST["shortPreview"]));
                    
                    }
                   
                   if (isset($_POST["shortPreviewLength"]) & !empty($_POST["shortPreviewLength"])) {
                        
                        $shortArticles -> cutTheContentOfThisColumn(array("content"),intval($_POST["shortPreviewLength"]));	
                     
                     }
                   
                   $shortArticles -> sortRowsInAscendingOrDescendingUsingCol("id_art",false);
                
                   if (isset($_POST["shortPreviewCategory"]) & !empty($_POST["shortPreviewCategory"])) {
                        
                        $shortArticles -> setRowsExtractCondition("cat_id = ".intval($_POST["shortPreviewCategory"]));
                     
                     }   
                   
                   $shortArticles -> getTableRows();
                   $shortArticles -> printJSONEncodedObject();
                
              } 
        //==================================================================================================================       
                else if (isset($_POST["fullArticleAtID"]) & !empty($_POST["fullArticleAtID"])) {
                         
                         $fullArticle = new TED_GetTableData("Articles","Categories");
                         
                         $fullArticle -> setTotalRowsToExtract(1);
                         $fullArticle -> cutTheContentOfThisColumn(array("content"),"<p>,<strong>,<em>,<b>,<i>,<u>,<strike>,<center>,<h1>,<h2>,<h3>,<h4>,<h5>,<h6>");	
                         $fullArticle -> setRowsExtractCondition("id_art = ".intval($_POST["fullArticleAtID"]));
                         $fullArticle -> getTableRows();
                         $fullArticle -> printJSONEncodedObject();
                     }
        //==================================================================================================================              
         
         
           
              
      }  
       
              
            
  
      
?>