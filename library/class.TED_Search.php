<?php
#######################################################################################
#                             - TED_Search class  -
# -------------------------------------------------------------------------------------
#                      (c) TheEdelweiss  |  @Popa Stefan  2013
# -------------------------------------------------------------------------------------
#
# Usage:
#
#######################################################################################

class TED_Search
{
//-------------------------------------------------------------------------------------	     
  //------------------------------------------------------------------------------>>>>>
      // -------------------------------------------------------------------------	         
         // --class properties:		
            protected $paramsChain;
	  // -------------------------------------------------------------------------
  //------------------------------------------------------------------------------>>>>>
//-------------------------------------------------------------------------------------
		
public function __construct( $url ) 
{
	if(is_array($url)) 
	{
		$this->paramsChain = $url;
	}
}
		
//-------------------------------------------------------------------------------------

public function performSearch()
{
	try 
	{
	   if(count($this->paramsChain) != 2)
	   {
		 throw new InvalidArgumentException('Current search query is invalid.');
	   }
     
     switch(strtoupper($this->paramsChain[0]))
     {
	     case'ARTICLES':
	                  array_shift($this->paramsChain);
	                 
	                  $articles =  new TED_GetTableData("Articles");
	                  $articles->setRowsExtractCondition("cat_id NOT IN(3, 16, 11, 6)");
	                  
	                  if(strlen($this->paramsChain[0]) >= 4)
	                  {
	                  $search = new TED_SearchCore($articles -> getTableRows("title","content","id_art"), 
	                                               $this->prepareQuery($this->paramsChain[0]));
	                  } 
	                    else  throw new InvalidArgumentException("Too short search word in : '"
	                               							     .$this->paramsChain[0]
	                              							     ."'  ");
	                  // trim content to 60 words            							     
	                  $this->stripContentOfRow("content", 60, $search->JSONoutput); 
	                  return $search->getPHPArray();            							     
	     break;
	     
	     default: 
	            throw new InvalidArgumentException("Invalid element in search chain : '".$this->paramsChain[0]."'  ");
     }
	
	}
      catch (InvalidArgumentException $e)
      {
	      $error = new TED_ErrorReport('HTTP/1.0 400 Bad Request', $e->getMessage());
	      $error->reportError();
      }	
}

//-------------------------------------------------------------------------------------

private function prepareQuery($query)
{
	if(is_string($query)) 
      {	
	      $query = preg_replace('/(_)/'," ",$query);
	      return $query;	
      }
}

//-------------------------------------------------------------------------------------

private function stripContentOfRow($rowName, $length, &$arrayOfElements)
{
	if(is_string($rowName) & is_numeric($length))
	{
		if(!empty($arrayOfElements))
		{
		 $numOfElements = count($arrayOfElements);
		 for($j = 0; $j < $numOfElements; $j++)
		  { 
			 $keysArray = array_keys($arrayOfElements[$j]);  
			 $numOfKeys = count($keysArray); 
	        
			 for($i = 0; $i < $numOfKeys; $i++)
			 {		 
			    if(strcmp($rowName, $keysArray[$i]) == 0)
				  {   
					 $arrayOfElements[$j][$keysArray[$i]] = 
					 $this->truncateText($arrayOfElements[$j][$keysArray[$i]], 30);
					 
					 $arrayOfElements[$j][$keysArray[$i]] .=" ...";	
			      }
		     } 
		  }
	    }
     }
}

//-------------------------------------------------------------------------------------

private function truncateText($text, $length) 
{
   $length = abs((int)$length);
   $wordsArray = explode(" ", $text);
   $numberOfItems = count($wordsArray);
   
   if($length > $numberOfItems) 
   {
     $length = $numberOfItems;
   }
   
   $text = "";
   for($i=0; $i < $length; $i++) 
   {
	 $text.= $wordsArray[$i]." ";
   }
   return($text);
}

//-------------------------------------------------------------------------------------
}
?>