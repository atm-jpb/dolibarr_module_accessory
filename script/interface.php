<?php


	require '../config.php';
	dol_include_once('/product/class/product.class.php');
	dol_include_once('/accessory/class/accessory.class.php');
	dol_include_once('/comm/propal/class/propal.class.php');
	
	$get=GETPOST('get');
	$put=GETPOST('put');

	
	$PDOdb=new TPDOdb;
	
	switch ($put) {
		case 'addlines':
			
			$object_type=GETPOST('object_type');
			$object_id=(int)GETPOST('object_id');
			$ToAddLine=$_REQUEST['ToAddLine'];
			$txtva=(float)GETPOST('txtva');
			$lineid=(int)GETPOST('lineid');
			
			if(!empty($ToAddLine)) {
				$o=new $object_type($db);
				$o->fetch($object_id);
				
				foreach($ToAddLine as &$addline) {
					$a=new TAccessory;
					$PDOdb->debug=true;
					
					if($a->load($PDOdb, $addline['accessoryid'])) {
						$p=new Product($db);
						if($p->fetch($a->fk_accessory)>0) {
							$res = $o->addline($p->description, $p->price, $addline['qty'], $txtva,0,0,$p->id);
						}
					}
				}
			}
			
			echo 1;
			
			break;
		default:
			
			break;
	}

