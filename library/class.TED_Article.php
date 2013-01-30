<?php
#######################################################################################
#                                 - TED_Article class  -
# -------------------------------------------------------------------------------------
#                      (c) TheEdelweiss  |  @Popa Stefan  2013
# -------------------------------------------------------------------------------------
#
# Usage:
#
#######################################################################################
	
class TED_Article 
{		
# -------------------------------------------------------------------------------------
	protected $paramsChain;
	protected $articlesPerPage;	   
# -------------------------------------------------------------------------------------
		
# -------------------------------------------------------------------------------------
public function __construct($url) 
{					
  if( is_array($url)) 
  {
	$this->paramsChain = $url;
  }
  
  $this->articlesPerPage = 50;
}
		
# -------------------------------------------------------------------------------------
public function performExtraction() 
{
  try 
	 {
	   if(count($this->paramsChain) == 0)
	   {
		  // extract first 50 articles
		  return $this->getShortArticles();
	   }
		else if(is_numeric($this->paramsChain[0])) 
			 {
				// extract article at this index
				$articleBody = $this->getArticleAtThisID($this->paramsChain[0]);
				
				if($articleBody == null) 
				  throw new InvalidArgumentException("Can't find an article with this ID: "
				                                     .$this->paramsChain[0]); 
				 else return $articleBody;
			 } 
			  else if(is_string($this->paramsChain[0]))
			  {
				switch(strtoupper($this->paramsChain[0]))
				 {
					case 'CATEGORY':
					 			    array_shift($this->paramsChain);  // remove first element in array
					 			    return $this->getShortArticles();
					        		break;
					 	
					default: throw new InvalidArgumentException('Bad articles parameters chain in: '
					                                            .$this->paramsChain[0]);      
				 }
			  } 
			   else throw new InvalidArgumentException('Bad articles parameters chain');
	 }
	  catch (InvalidArgumentException $e) 
	  {
		$error = new TED_ErrorReport('HTTP/1.0 400 Bad Request', $e->getMessage());
		$error->reportError();
		return null;
	  }
}
		
# -------------------------------------------------------------------------------------
private function getArticleAtThisID($ID)
{
	$fullArticle = new TED_GetTableData("Articles");
    $fullArticle -> setTotalRowsToExtract(1);
    $fullArticle -> cutTheContentOfThisColumn(array("content"),"<p>,
    															<table>,
    															<tr>,
    															<td>,
    															<strong>,
    															<em>,
    															<b>,
    															<i>,
    															<u>,
    															<strike>,
    															<center>,
    															<h1>,
    															<h2>,
    															<h3>,
    															<h4>,
    															<h5>,
    															<h6>"); // :)
    																
    $fullArticle -> setRowsExtractCondition("id_art = "
    										.intval($ID));
    $fullArticle -> getTableRows("title","content","thumb","id_art");
                         
    $newPage= new TED_HTMLObject($fullArticle->getPHPArray());
    return $newPage -> buildPageWithContentOfThisRows("content","title","thumb");
}
	    
# -------------------------------------------------------------------------------------
private function getShortArticles()
{
	$shortArticles =  new TED_GetTableData("Articles","Categories");
	$shortArticles -> sortRowsInAscendingOrDescendingUsingCol("id_art",false);
	$shortArticles -> cutTheContentOfThisColumn(array("content"), 30);	
	
	if(count($this->paramsChain) == 0)
	{
		$shortArticles -> setTotalRowsToExtract(intval($this->articlesPerPage));
	    $shortArticles -> cutTheContentOfThisColumn(array("content"), 30);	
	    $shortArticles -> setRowsExtractCondition("cat_id NOT IN (3, 16, 11, 6)");
	    
	} else {
	       if(is_numeric($this->paramsChain[0]))
 	       {
		     $shortArticles -> setRowsExtractCondition("cat_id = "
		     										   .intval($this->paramsChain[0]));
		     array_shift($this->paramsChain); 
	       } 
	        else if(is_string($this->paramsChain[0]) & 
	                strtoupper($this->paramsChain[0]) == 'ALL' )
	             { 
	               $shortArticles -> setRowsExtractCondition("cat_id NOT IN (3, 16, 11, 6)");
	               array_shift($this->paramsChain); 
	             }
	             
	if(count($this->paramsChain) != 0 & is_string($this->paramsChain[0])) {
		
		switch(strtoupper($this->paramsChain[0]))
		{
			case 'PAGE':
						array_shift($this->paramsChain);
						if(count($this->paramsChain) != 0) 
						{
						  $page = $this->paramsChain[0];
						  $shortArticles ->setStartRowToExtract((intval($page)-1) * 
						                                        $this->articlesPerPage);
						                                        
						  $shortArticles ->setTotalRowsToExtract(intval($page) * 
						  										 $this->articlesPerPage);
						} 
						
						break;

			case 'PAGECOUNT':
			                return $shortArticles ->count();
							break;
			default: 
			        break;							
		}
	} 
	else {      
	        $shortArticles -> setTotalRowsToExtract(intval($this->articlesPerPage));
	     }
	
	}
	$shortArticles -> getTableRows();
	return $shortArticles -> getPHPArray();
}

}
?>