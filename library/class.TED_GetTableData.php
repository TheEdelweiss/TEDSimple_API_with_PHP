<?
#######################################################################################
#                                - GetTableData CLASS -
# -------------------------------------------------------------------------------------
#                        (c) TheEdelweiss  |  @Popa Stefan  2012
# -------------------------------------------------------------------------------------
#
# Usage:
#
#    Required:
#            $varName = new TED_GetTableData("tableName1"[,"tableName2",...]); 
#            $varName-> getTableRows();           // DB extract & format arrays
#            $varName-> printJSONEncodedObject(); // print JSON encoded result 
#    
#    Optional:
#            $varName-> setStartRowToExtract($startRow); // starting with $startRow (nr)
#            $varName-> setTotalRowsToExtract($totalRows); 
#            $varName-> setRowsExtractCondition($condition); //SQL WHERE cond
#            // cut the content of column, with $numberOfChars
#            $varName-> cutTheContentOfThisColumn($columnName,$numberOfChars);
#            // SQL ORDER BY, $ascOrDesc - bool value (true-ASC, false-DESC)  
#            $varName-> sortRowsInAscendingOrDescendingUsingCol($nameOfCol,$ascOrDesc);     
#
#######################################################################################


class TED_GetTableData extends TED_JSONObject {
//-------------------------------------------------------------------------------------	     
  //------------------------------------------------------------------------------>>>>>
      //--------------------------------------------------------------------------	   
	    private $tableName;
	    private $numOfTables;
	    //--------------------------------
	    private $rowsExtractCondition;
	    //--------------------------------
	    private $startNumbOfRowsToExtract;
	    private $totalNumbOfRowsToExtract;
	    //--------------------------------
	    private $colsToCutTheContentOf;
	    private $remainingNumberOfSymbols;
	    //--------------------------------
	    private $sortRowsInAscendingOrDescendingUsingCol;
	  //--------------------------------------------------------------------------
  //------------------------------------------------------------------------------>>>>>
//-------------------------------------------------------------------------------------	

  function __construct() {
	    
	    // get number of function args
	    $this->numOfTables = func_num_args();
	    
	    if($this->numOfTables > 0) {
	       $functionArgList = func_get_args();	    
	     } 
	    
	    for($i = 0; $i < $this->numOfTables; $i++) {
	         $this->tableName[$i] = $functionArgList[$i];
	        }     
	} 
 
//-------------------------------------------------------------------------------------	

  function __destruct() {
	  
  }	    

//-------------------------------------------------------------------------------------	
    
  function getTableRows() {
	    
    $mysqlGetRowsQuery = "SELECT ";
	$numOfFunctionArgs = func_num_args();     
	    
	if($numOfFunctionArgs > 0) 
	{
	   $functionArgList = func_get_args();	    
	    
	   for($i = 0; $i < $numOfFunctionArgs; $i++) 
	      {
		      $mysqlGetRowsQuery .= $functionArgList[$i];
		      $i == $numOfFunctionArgs - 1 ? $mysqlGetRowsQuery .= " " : 
		      $mysqlGetRowsQuery .= ", ";  
		  }
	  
	   $mysqlGetRowsQuery .=" FROM "
	                      .$this->tableName[0]
	                      ." ";
   } 
	else {
	       $mysqlGetRowsQuery .= "* FROM "
	       					  .$this->tableName[0]
	       					  ." ";
	 
	       if ($this->numOfTables > 1) 
	       {
	          for($i=1; $i<$this->numOfTables; $i++) 
	          {
	             $mysqlGetRowsQuery .= "NATURAL JOIN "
	             					.$this->tableName[$i]
	             					." ";
	          }
	       }
	    }  
	     
	// WHERE     
	if (isset($this->rowsExtractCondition)) 
	{
	    $mysqlGetRowsQuery .= " WHERE "
	                       .$this->rowsExtractCondition
	                       ." ";
	                       
	    unset($this->rowsExtractCondition); 
	}
	
	// ORDER BY  
	if(isset($this->sortRowsInAscendingOrDescendingUsingCol)) 
	{
	   $mysqlGetRowsQuery .= " ORDER BY "
	                      .$this->sortRowsInAscendingOrDescendingUsingCol 
	                      ." ";
	}  
	   
	// LIMIT 
    if(isset($this->totalNumbOfRowsToExtract) & isset($this->startNumbOfRowsToExtract))
    {       
	    $mysqlGetRowsQuery.= " LIMIT "
	    				  .$this->startNumbOfRowsToExtract
	    				  .","
	    				  .$this->totalNumbOfRowsToExtract
	    				  ." ;";
	    				  
	    unset($this->startNumbOfRowsToExtract,$this->totalNumbOfRowsToExtract);
	} 
	 else if(isset($this->totalNumbOfRowsToExtract) & !isset($this->startNumbOfRowsToExtract)) 
	 {
		$mysqlGetRowsQuery.= " LIMIT "
					      .$this->totalNumbOfRowsToExtract
					      ." ;";
		
		unset($this->totalNumbOfRowsToExtract); 
	 } 
	  else 
	  {
		$mysqlGetRowsQuery.= " ;";
		unset($this->totalNumbOfRowsToExtract);
	  }
	       
	    $SQLresult = mysql_query($mysqlGetRowsQuery);
	    $i = 0;
	    
	    // compose keys and values arrays
	    while($assocRow = mysql_fetch_assoc($SQLresult))
	    {	  
		    $keysArray = array_keys($assocRow);  // get an array of $assocRow keys
	        $numOfKeys = count($keysArray);      // get the number of $keysArray elements
	    	
	        // compose key / value array
	        for($j = 0; $j < $numOfKeys; $j++) 
	        { 
		        if (isset($this->colsToCutTheContentOf) & 
		            isset($this->remainingNumberOfSymbols) & 
		            !empty($this->remainingNumberOfSymbols)) 
		            {
		              if(is_integer($this->remainingNumberOfSymbols))
		              {       
		                if ($this->arrayContainThisValue($keysArray[$j],$this->colsToCutTheContentOf)) 
		                {
		                 if(mb_strlen(strip_tags($assocRow[$keysArray[$j]]),'UTF-8') > 
		                    $this->remainingNumberOfSymbols) 
		                    {   
			                   $assocRow[$keysArray[$j]] = 
			                    $this->truncate(strip_tags($assocRow[$keysArray[$j]]), 
			                    $this->remainingNumberOfSymbols); 
		       	               
		       	               $assocRow[$keysArray[$j]] .= "...";		            
		                    }
		                }
		              } 
		               else if(is_string($this->remainingNumberOfSymbols)) 
		               {
			             if($this->arrayContainThisValue($keysArray[$j],$this->colsToCutTheContentOf)) 
			             {
				           $assocRow[$keysArray[$j]] = 
				             strip_tags($assocRow[$keysArray[$j]],$this->remainingNumberOfSymbols);
		                 }          
		               }
		            } 
		        $this->JSONoutput[$i][$keysArray[$j]] = $assocRow[$keysArray[$j]];
            }	
	    	 
         $i++;
	   }   
	   unset($this->colsToCutTheContentOf,$this->remainingNumberOfSymbols);
	   return $this->JSONoutput; 
}

//-------------------------------------------------------------------------------------	  

private function arrayContainThisValue($value, $arrayWithValues) 
{
   foreach ($arrayWithValues as $val) 
   {
	 if ($value == $val)  return true;
   }
   return false;
}	

//-------------------------------------------------------------------------------------

private function truncate($text, $length) 
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

public function count()
{
 $mysqlGetRowsQuery = "SELECT COUNT(*) AS count";
 
 if(!empty($this->tableName[0]))
 { 
   $mysqlGetRowsQuery .= " FROM "
	       			  .$this->tableName[0];	
 } else $mysqlGetRowsQuery .= " FROM Articles ";
 
 if(!empty($this->rowsExtractCondition))
 {	       			       			
   $mysqlGetRowsQuery .= " WHERE "
	                  .$this->rowsExtractCondition;    
 }
                 
 $number = mysql_query($mysqlGetRowsQuery);	 
 $numberMessage = mysql_fetch_assoc($number);                			
 return $numberMessage; 
}

//-------------------------------------------------------------------------------------
	  
public function sortRowsInAscendingOrDescendingUsingCol($nameOfCol,$ascOrDesc) 
{
   if (is_string($nameOfCol) & is_bool($ascOrDesc)) 
   {
     $this->sortRowsInAscendingOrDescendingUsingCol = $nameOfCol;
     
     if($ascOrDesc)
     {       
       $this->sortRowsInAscendingOrDescendingUsingCol.=" ASC ";
     }
      else $this->sortRowsInAscendingOrDescendingUsingCol.=" DESC ";
   }
}  

//-------------------------------------------------------------------------------------

public function cutTheContentOfThisColumn($columnName,$numberOfChars) 
{
   if (is_array($columnName) & $numberOfChars > 0) 
   {
	 for ($i = 0; $i < count($columnName); $i ++) 
	 {
	   $this->colsToCutTheContentOf[$i] = $columnName[$i];
	 }
	        
	 $this->remainingNumberOfSymbols = intval($numberOfChars);    
   } 
     else if (is_array($columnName) & is_string($numberOfChars)) 
     {   	
	   for ($i = 0; $i < count($columnName); $i ++) 
	   {
	     $this->colsToCutTheContentOf[$i] = $columnName[$i];
	   } 	
	   $this-> remainingNumberOfSymbols = $numberOfChars;
	 }
}

//-------------------------------------------------------------------------------------
	  
public function setStartRowToExtract($startRow) 
{
  if (intval($startRow) > -1) 
  {
	 $this->startNumbOfRowsToExtract = intval($startRow);  
  }
} 	  

//-------------------------------------------------------------------------------------

public function setTotalRowsToExtract($totalRows) 
{
  if (intval($totalRows) > 0) 
  {
	$this->totalNumbOfRowsToExtract = intval($totalRows);  
  }
}

//-------------------------------------------------------------------------------------
 
public function setRowsExtractCondition($condition) 
{
  if (is_string($condition)) 
  {
  	$this->rowsExtractCondition = $condition;
  }
}

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
}
?>