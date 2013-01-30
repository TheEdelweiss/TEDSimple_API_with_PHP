<?php
	
	class TED_ErrorReport 
	{
		
		// +-------------------
		private $htmlErrorBody;
		private $header;
		// +-------------------
		
		// +------------------------------------------
		public function __construct($header, $reason) 
		{	
			if(is_string($header) & is_string($reason)){
				$this->header = $header;
				$this->htmlErrorBody = '</br></br>
				                        <center>
				                           <strong>
				                            <h2>API says : "Dude, something goes wrong !"</h2>
				                           </strong>
				                         </center>
				                         
				                        <center>
				                          <strong>
				                          <h1>:(</h1>
				                          </strong>
				                        </center>
				                     
				                        <center>
				                          <strong>Reason:</strong>';
				$this->htmlErrorBody .= '   "'.$reason.'".</center>';
				$this->htmlErrorBody .= '</br><center>
				 								<strong>API recommends you: </strong>
				 								"Keep calm and use the Force !".</center>'; 
			}
		}
		// +------------------------------------------
		
		// +------------------------------------------
		public function reportError()
		{
			header($this->header);
			header('Content-Type: text/html; charset=utf-8');
			echo($this->htmlErrorBody);
		}
		// +------------------------------------------
	}
?>