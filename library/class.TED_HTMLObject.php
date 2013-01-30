<?php


class TED_HTMLObject {

//----------------------------------------------------------	     
  //--------------------------------------------------->>>>>
      //-----------------------------------------------	         
      //class properties:
        
        private $mainArray;
        public $HTMLPageString;
        private $title;
        private $imageURL;
        private $content;
        
	  //-----------------------------------------------
  //--------------------------------------------------->>>>>
//----------------------------------------------------------

function __construct($array) {
	
	if (is_array($array) & !empty($array)) {
	
		$this->mainArray = $array;	
	}
}	
//----------------------------------------------------------
  //--------------------------------------------------->>>>>    
//----------------------------------------------------------       
//class destructor, -

function __destruct(){

}	 
//----------------------------------------------------------
  //--------------------------------------------------->>>>>    
//----------------------------------------------------------

function buildPageWithContentOfThisRows() {
	
	// if data exist
	if(isset($this->mainArray) & !empty($this->mainArray) & is_array($this->mainArray)) {
		
		// if exist arguments
		if(func_num_args() >= 1) {
		// get function arguments
		$functionArgList = func_get_args();	   
	
	foreach($this->mainArray as $array) {	
		foreach($functionArgList as $argument) {
		    $i=0;
			if(array_key_exists($argument, $array)) {
				// title
				if (strcasecmp($argument, "title") == 0) {
					$this->title = $array[$argument];
				} 
				// image
			    if (strcasecmp($argument, "thumb") == 0) {
					// thumb exist?
					
					if (!(strcasecmp($array["thumb"], "../thumb.png") == 0)) {
					
						$this->imageURL .= "http://consiliu.rezina.md/content_imgs/articles_imgs/".$array["id_art"]."/thumb.jpg";
					}
				} else 
				       $this->content.= $array[$argument];
			  } 
		  }
	   } 
    }
}
  // compose page code 
  $this->buldWithTemplate($this->title, $this->imageURL, $this->content);
  return $this->HTMLPageString;
  
}



function buldWithTemplate ($titleString, $imageString, $contentString) {

if(empty($titleString)) return null;
// remove empty <p> tag's
$pattern = "/<p[^>]*>[\s|&nbsp;]*<\/p>/";
$contentString = preg_replace($pattern,'', $contentString);
$HTMLPage = "<!DOCTYPE HTML>
<html>
 <head>
  <meta charset=\"UTF-8\">
  <title></title>
  
  <style type=\"text/css\">

.block {width:100%; height:100%; position:relative; word-wrap:break-word; text-align:justify; font-family:Verdana;font-size:13px; text-shadow:0px 1px 0px #ffffff;}  
img {border-radius:4px; margin-bottom:10px; margin-top:5px;}   
h1 {}
p {font-size:1em; line-height:1.25em; margin:0; text-align:justify;}
p + p {text-indent:2.5em;}
                  </style> 

</head>
 
 <body>

  <div class=\"block\"><h3>".html_entity_decode($titleString)."</h3> <img src=\"".$imageString."\" alt=\"\" width=\"100%\" align=\"\" />".$contentString.".</div>
   </body>
</html>";
	
  $this->HTMLPageString = $HTMLPage; 
 }
}
?>