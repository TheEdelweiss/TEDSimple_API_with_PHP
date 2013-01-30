<?php
#######################################################################################
#                             - TED_RESTResponse class  -
# -------------------------------------------------------------------------------------
#                      (c) TheEdelweiss  |  @Popa Stefan  2013
# -------------------------------------------------------------------------------------
#
# Usage:
#
#######################################################################################

class TED_RESTResponse 
{
//-------------------------------------------------------------------------------------	     
  //------------------------------------------------------------------------------>>>>>
      // -------------------------------------------------------------------------	         
         // --class properties:
	     protected $url;
	     protected $verb;
	     protected $responseBody;
	     protected $header;
	     protected $RESTPath;
	  // -------------------------------------------------------------------------
  //------------------------------------------------------------------------------>>>>>
//-------------------------------------------------------------------------------------

public function __construct( $url, $verb )
{
	if (is_string($url) & is_string($verb)) 
	{
	  $this->url = $url;
	  $this->verb = $verb;
	  $this->parseAndStoreRESTPath();
	} 
	 else {
		    $error = new TED_ErrorReport('HTTP/1.0 400 Bad Request', 'Bad request parameters');
			$error->reportError();
	      }
}
	
//-------------------------------------------------------------------------------------
	
public function execute() 
{
  try
	  {
		switch (strtoupper($this->verb))
		{
			case 'GET':
					   $this->executeGet();
					   break;
					   
			case 'POST':
					    $this->executePost();
					    break;
			case 'PUT':
					   $this->executePut();
					   break;
			case 'DELETE':
						  $this->executeDelete();
						  break;
			default:
					throw new InvalidArgumentException('Current verb < ' 
					                                   .$this->verb 
					                                   .' > is an invalid REST verb.');
		}
	  }
  catch (InvalidArgumentException $e)
  {
	$error = new TED_ErrorReport('HTTP/1.0 400 Bad Request', $e->getMessage());
	$error->reportError();

  }	
}
	
//-------------------------------------------------------------------------------------
	
private function  executeGet() 
{
	try
		{
		 if(count($this->RESTPath) == 0) 
			 throw new InvalidArgumentException('Current query is empty');
			
			$mainBlock = $this->RESTPath[0];
			
		 switch (strtoupper($mainBlock))
		 {
			case 'ARTICLE':
				           array_shift($this->RESTPath);   // -- remove first element in array
				                
				           $articles = new TED_Article($this->RESTPath);
					       $this->responseBody = $articles->performExtraction();
					       
					       if($this->responseBody != null) 
					       {
					         if ($this->hasHtml($this->responseBody)) 
					         {
					            // output is HTML
					            $this->header = "HTTP/1.0 200 OK";
					            $this->sendHTML();
					                   
					         } 
					          else {
					                  // output is PHP array, need to serialize
					                  $this->header = "HTTP/1.0 200 OK";
					                  $this->serializeAndSend();
					                            
					               }
					        }
					        break;
				
			case 'CATEGORY':
					        array_shift($this->RESTPath);   // -- remove first element in array
					            
					        $categories = new TED_Category($this->RESTPath);
					        $this->responseBody = $categories->performExtraction();
					        $this->header = "HTTP/1.0 200 OK";
					        $this->serializeAndSend();
					        break;
				
			case 'SEARCH':
					      array_shift($this->RESTPath);   // -- remove first element in array
					      $search = new TED_Search($this->RESTPath);
					      $this->responseBody = $search->performSearch();      
					      $this->header = "HTTP/1.0 200 OK";
					      $this->serializeAndSend();
					      break;
					
		    default:
					throw new InvalidArgumentException('Current query < ' 
					                                   .$mainBlock 
					                                   .' > is an invalid query');
		}
	}
    catch (InvalidArgumentException $e)
	{
	   $error = new TED_ErrorReport('HTTP/1.0 400 Bad Request', $e->getMessage());
	   $error->reportError();
	}	
}
	
//-------------------------------------------------------------------------------------

private function executePost() 
{
		$error = new TED_ErrorReport('HTTP/1.0 501 Not Implemented', 'POST verb is not implemented in this API');
		$error->reportError();
	}
	
//-------------------------------------------------------------------------------------

private function executePut() 
{
		$error = new TED_ErrorReport('HTTP/1.0 501 Not Implemented', 'PUT verb is not implemented in this API');
		$error->reportError();
	}
	
//-------------------------------------------------------------------------------------

private function executeDelete() 
{
		$error = new TED_ErrorReport('HTTP/1.0 501 Not Implemented', 'DELETE verb is not implemented in this API');
		$error->reportError();
	}
	
//-------------------------------------------------------------------------------------

private function parseAndStoreRESTPath() 
{
		$path = parse_url($this->url, PHP_URL_PATH);      // -- get path from url
		$pathTrimmed = trim($path, '/');                  // -- normalise with no leading or trailing slash
        $this->RESTPath = explode('/', $pathTrimmed);     // -- get segments delimited by a slash
        
        array_shift($this->RESTPath);                     // -- remove first element in array (/api/ - element)
   	}
	
//-------------------------------------------------------------------------------------

private function serializeAndSend()
{
	header($this->header);
		
	if(!empty($this->responseBody))
	{
		header("Content-Type: application/json");
		echo(json_encode($this->responseBody));
	}
}
	
//-------------------------------------------------------------------------------------

private function sendHTML()
{
   header($this->header);
   
   if(!empty($this->responseBody))
   {
      header('Content-Type: text/html; charset=utf-8');
	  echo($this->responseBody);
   }
}
    
//-------------------------------------------------------------------------------------

private function hasHtml($str)
{
  if(is_string($str))
   {
    if(strlen($str) != strlen(strip_tags($str))) return true; 
       else return false; 
   }
    return false;
}
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
   
}
?>