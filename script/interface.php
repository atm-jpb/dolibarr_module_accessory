<?php


	require '../config.php';
	dol_include_once('/product/class/product.class.php');
	dol_include_once('/accessory/class/accessory.class.php');
	dol_include_once('/comm/propal/class/propal.class.php');
	
	$get=GETPOST('get');
	$put=GETPOST('put');

	
	switch ($put) {
		case 'addlines':
			
			$object_type=GETPOST('object_type');
			$object_id=(int)GETPOST('object_id');
			$ToAddLine=GETPOST('ToAddLine');
			$txtva=(float)GETPOST('txtva');
			$lineid=(int)GETPOST('lineid');
			
			if(!empty($TProduct)) {
				$o=new $object_type($db);
				$o->fetch($object_id);
				
				foreach($ToAddLine as &$addline) {
					$a=new TAccessory;
					$a->load($PDOdb, $addline->accessoryid);
						
					$p=new Product($db);
					$p->fetch($fk_product);
					
					$o->addline($p->description, $p->price, $qty, $txtva,0,0,$fk_product);
				}
				
				
			}
			
			echo 1;
			
			break;
		default:
			
			break;
	}

