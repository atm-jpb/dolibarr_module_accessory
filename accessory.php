<?php

	require 'config.php';

	dol_include_once('/accessory/class/accessory.class.php');
	dol_include_once('/product/class/product.class.php');
	dol_include_once('/core/lib/product.lib.php');

	$PDOdb = new TPDOdb;

	$action=GETPOST('action');
	$fk_object=(int)GETPOST('fk_object');
	$type_object=GETPOST('type_object');

	$object = new Product($db);
	$object->fetch($fk_object);
	
	switch ($action) {
		case 'save':
			
			$fk_product = (int)GETPOST('fk_product');
			var_dump($_REQUEST);
			if($fk_product>0 && GETPOST('btadd')) {
			
				$a=new TAccessory;
				$a->fk_accessory = $fk_product;
				$a->fk_object = $object->id;
				$a->type_object = $object->element;
				$a->save($PDOdb);
				
			}
			
			_card($PDOdb,$object);
			
			break;
		
		default:
			
			_card($PDOdb,$object);
			
			break;
	}
	
function _card(&$PDOdb, &$object) {
	global $langs,$db,$user,$conf,$form;
	
	llxHeader();
	
    $head=product_prepare_head($object);
    $titre=$langs->trans("CardProduct".$object->type);
    $picto=($object->type== Product::TYPE_SERVICE?'service':'product');
	
    dol_fiche_head($head, 'accessory', $titre, 0, $picto);

	$formCore = new TFormCore('auto','formAcc','get');
	echo $formCore->hidden('action', 'save');
	echo $formCore->hidden('fk_object',  $object->id);
	echo $formCore->hidden('type_object', $object->element);

	$form->select_produits(-1,'fk_product').'&nbsp;';
	echo $formCore->btsubmit($langs->trans('Add'), 'btadd');

	$TAccessory = TAccessory::getAccessories($PDOdb, $object->id, $object->element);

	echo '<table width="100%" class="border"><tr class="liste_titre"><td>'.$langs->trans('Accessory').'</td><td>'.$langs->trans('Qty').'</td></tr>';

	foreach($TAccessory as &$accessory) {
		
		$p=new Product($db);
		$p->fetch($accessory->fk_accessory);
		
		echo '<tr>
			<td>'.$p->getNomUrl(1).'</td>
			<td>'.$formCore->texte('', 'TAccessory['.$accessory->getId().'][qty]', $accessory->qty, 3,50).'</td>
		</tr>';
		
	}
	
	echo '</table>';


	$formCore->end();

	dol_fiche_end();
	
	llxFooter();
	
}
