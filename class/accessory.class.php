<?php

class TAccessory extends TObjetStd {
	
	function __construct() {
		
		$this->set_table(MAIN_DB_PREFIX.'accessory');
		$this->add_champs('fk_object,fk_accessory',array('type'=>'integer', 'index'=>true));
		$this->add_champs('qty',array('type'=>'float'));
		$this->add_champs('emplacement,note');
		$this->add_champs('type_object',array('type'=>'string','length'=>20,'index'=>true));
		
		$this->_init_vars();
		
		$this->start();
	
		$this->qty = 1;
		
	}
	
	
	static function getAccessories(&$PDOdb, $fk_object, $type_object) {
		/*
		 * Récupère la liste des accessoires liés
		 */
		 
		$Tab = array();
		
		$TRes = $PDOdb->ExecuteAsArray("SELECT rowid FROM ".MAIN_DB_PREFIX."accessory 
		 		WHERE fk_object=".$fk_object." AND type_object='".$type_object."'");
		
		foreach($TRes as &$obj) {
			
			$a=new TAccessory;
			if($a->load($PDOdb, $obj->rowid)) {
				$Tab[] = $a;
			}
			
		}
		
		
		return $Tab;
	}
		
}
