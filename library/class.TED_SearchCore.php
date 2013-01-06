<?php
//==============================================================
//require("class.TED_JSONObject.php");
//==============================================================
################################################################
#    - TED_SearchCore class, extends TED_JSONObject class  -
# --------------------------------------------------------------
# (c) TheEdelweiss  |  @Popa Stefan  2012
# --------------------------------------------------------------
#
# Usage:
#
################################################################

class TED_SearchCore extends TED_JSONObject {
//----------------------------------------------------------	     
  //--------------------------------------------------->>>>>
      //-----------------------------------------------	         
      //class properties:
      
	  //-----------------------------------------------
  //--------------------------------------------------->>>>>
//----------------------------------------------------------
//class constructor, $materials - key/value array, 
//                   $query - query string;
 	    
function __construct($materials, $query) {
	
	if (is_string($query) & !empty($query) & is_array($materials) & !empty($materials)) {
		$this->searchResult($materials, $this->explodeQuery($query));		
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
//main method, search in materials[i] coincidences with 
//keywords[i],if matched then add to $this->JSONoutput, 
//else unset materials[i]  
     
function searchResult($materials, $keywords) {
		$JSONArrayCounter = 0;
	
	foreach ($materials as $material) {      
		
			  $keysArray = array_keys($material);  // get an array of $assocRow keys
	          $numOfKeys = count($keysArray);      // get the number of $keysArray elements
	        
       for($i = 0; $i < $numOfKeys; $i++){
			
			if(is_string($material[$keysArray[$i]])){
			     $material[$keysArray[$i]] = htmlspecialchars(strip_tags($material[$keysArray[$i]]));	
			   }
		}
			
			$wordWeight = 0; 
			$material['relevation'] = 0;
			
		foreach ($keywords as $word) { 	
				  $pattern = "/(".$word.")/"; 	
			 	
			for ($i = 0; $i < $numOfKeys; $i++)	{
				  if ( is_string ($material[$keysArray[$i]]) ){
			
				       $wordWeight += preg_match_all($pattern, $material[$keysArray[$i]], $out);	
				       $material['relevation'] += $wordWeight; 
				     
				    }   
			 }
	    }
	        
		if ( $material['relevation'] >= count($keywords) ) {       
			   $this->JSONoutput[$JSONArrayCounter] = $material;
			   $JSONArrayCounter ++;
			}
			 else {
			  	   unset($material);
			   }
	   }
	   	
	   	
	   	if (empty($this -> JSONoutput)) {
	         $this -> JSONoutput[0]["error_respone"] = "Nici o coincidenta in BD !";
          }
}	
//----------------------------------------------------------
  //--------------------------------------------------->>>>>    
//---------------------------------------------------------- 
//this method eliminates the endings of words 
//(romanian language only)
       
function removeWordEndingsRO($word) {

if(is_string($word)){		
		$endings = "/(ar|easă|ime|esc|iu|eşte|iş|andru|an|oi|oaie|aş|uc|el|ar|aş|easă|giu|al|ar|ară|aş|aşă|at|ată|bil|bilă|esc|ească|eţ|eaţă|aş|el|ea|iţă|uş|ar|iţă|tor|are|ere|ire|ătate|ea|arie|ărie|et|ime|iş|ie|et|este|iş|eanu|eseu|fil|for)$/i"; 
		$word = preg_replace($endings,'',$word); // drop ...
		return $word;
	   }

}
//----------------------------------------------------------
  //--------------------------------------------------->>>>>    
//---------------------------------------------------------- 
//this method eliminates stop words from a string 
//(romanian language only)
      
function cutStopWordsRO($query) {
		
if ( is_string($query) ) {	
	$stopListWords = "/\s(a|abia|acea|aceasta|această|aceea|aceeasi|aceeași|aceeaşi|aceia|acel|acela|acelasi|același|acelaşi|acelea|acest|acesta|aceste|acestea|acestei|acestia|aceștia|aceştia|acestui|acolo|acum|adica|adică|ai|ăi|aia|ăia|aici|aiurea|al|ăl|ala|ăla|alaturi|alături|ale|alt|alta|altă|altceva|alte|altfel|alti|alți|alţi|altii|alții|alţii|altul|am|anume|apoi|ar|are|as|aș|aş|asa|așa|aşa|asemenea|asta|ăsta|astazi|astăzi|astfel|asupra|asupră|atare|ati|ați|aţi|atat|atât|atata|atâta|atatea|atâtea|atatia|atâția|atâţia|atit|atita|atitea|atitia|atunci|au|avea|avem|avut|azi|b|ba|bă|bine|c|ca|că|caci|căci|cand|când|cam|capat|capăt|care|careia|căreia|carora|cărora|caruia|căruia|cat|cât|cata|câtă|cate|câte|cateva|câteva|cati|câți|câţi|cativa|câțiva|câţiva|ce|cea|ceea|cei|ceilalti|ceilalți|ceilalţi|cel|cele|celor|ceva|chiar|ci|cind|cine|cineva|cit|cita|cite|citeva|citi|citiva|conform|cu|cui|cum|cumva|d|da|dă|daca|dacă|dar|dat|de|deasupra|deci|decat|decât|decit|degraba|degrabă|deja|desi|deși|deşi|despre|din|dintr|dintre|doar|dupa|după|ei|el|ele|era|esti|ești|eşti|exact|f|face|fara|fără|fata|fată|față|faţă|fel|fi|fie|foarte|fost|g|geaba|h|i|ia|iar|ii|îi|il|îl|imi|îmi|in|în|inainte|înainte|inapoi|înapoi|inca|încă|incat|încât|incit|insa|însă|intr|într|intre|între|isi|își|îşi|iti|îți|îţi|j|jos|k|l|la|le|li|lor|lui|m|ma|mă|mai|măi|mare|meu|mea|mei|mi|mod|mult|multa|multă|multe|multi|mulți|mulţi|n|ne|ni|nici|nicio|niciodata|niciodată|niciun|nimeni|nimic|niste|niște|nişte|no|noi|nostri|noștri|noştri|nostru|nou|noua|nouă|nu|numai|o|ok|or|ori|orice|oricum|p|pai|păi|parca|parcă|pe|pentru|peste|pana|până|pina|plus|prea|prin|ps|pt|putini|puţini|puțini|r|s|sa|să|sai|săi|sale|sau|său|se|si|şi|și|sint|sintem|spre|sub|sunt|suntem|sus|t|tau|tău|te|ti|ți|ţi|toata|toată|toate|tocmai|tot|toti|toți|toţi|totul|totusi|totuși|totuşi|tu|tuturor|u|ul|un|una|unde|unei|unele|uneori|unii|unor|unui|unul|uri|v|va|vă|vi|via|voi|vom|vor|vreo|vreun|x|z)\s/im"; 
   $query = preg_replace($stopListWords," ",$query); // drop ...
   return $query;	
	}
}
//----------------------------------------------------------
  //--------------------------------------------------->>>>>    
//----------------------------------------------------------    
//try to eliminate SQL injections 
  
function secureQueryString($query)
    
    {
        $query = htmlspecialchars(stripslashes(trim($query)));
        $query = str_ireplace("script", "blocked", $query);
        $query = mysql_real_escape_string($query);
        return $query;
    }
//----------------------------------------------------------
  //--------------------------------------------------->>>>>    
//----------------------------------------------------------        	
//explode query, and eliminate unnecessary words

function explodeQuery ($query) {

		$query = $this->secureQueryString($query);
		$query = $this->cutStopWordsRO($query); 
		
		$words = explode(" ",$query); 	    
		
		$i = 0; 										
		
		foreach ( $words as $word ) { 	    
			        $word = trim($word);		
			        
			        if (strlen($word)<3) {		    
				        unset($word);
			          }
			           else {		
				             if ( strlen($word) > 6 ) {	
				 	              $keywords[$i] = $this->removeWordEndingsRO($word);  
					              $i++;								               
			                    }
				 
				 else {
	                   $keywords[$i]=$word; 				          
	                   $i++;
	               }
			 }
	   }
	   
	return $keywords;                                    
 }
//----------------------------------------------------------
  //--------------------------------------------------->>>>>    
//----------------------------------------------------------       	
/*

function colorSearchWord($word, $string, $color) {
		$replacement = "<span style='color:".$color."; border-bottom:1px dashed ".$color.";'>".$word."</span>";
		$result = str_replace($word, $replacement, $string);
		return $result;
  }
  
*/		
//----------------------------------------------------------
  //--------------------------------------------------->>>>>    

}
?>