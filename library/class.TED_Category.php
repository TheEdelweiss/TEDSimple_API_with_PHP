<?php

	class TED_Category {
		
		// ------------------------
		   protected $paramsChain;
		// ------------------------
		
		// +----------------------------------

		public function __construct( $url ) 
		{
					if( is_array($url)) {
					   $this->paramsChain = $url;
					}
		}
		
		// +----------------------------------
        public function performExtraction()
        {
	        
	        $allCats = new TED_GetTableData("Categories");
	        $allCats->setRowsExtractCondition("cat_id NOT IN(3, 16, 11, 6)");
            $allCats->getTableRows("cat_id","cat_name");
            return $allCats->getPHPArray();
        }
        // +----------------------------------

	}
?>