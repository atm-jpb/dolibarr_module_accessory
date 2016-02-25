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
			if($fk_product>0 && GETPOST('btadd')) {
			
				$a=new TAccessory;
				$a->fk_accessory = $fk_product;
				$a->fk_object = $object->id;
				$a->type_object = $object->element;
				$a->save($PDOdb);
				setEventMessage($langs->trans('AccessoryAdded'));
				
			}
			
			$TAccessory = GETPOST('TAccessory');
			
			if(!empty($TAccessory)) {
				foreach($TAccessory as $id=>&$data) {
					
					$a=new TAccessory;
					if($a->load($PDOdb, $id)) {
						$a->set_values($data);
						$a->save($PDOdb);
					}

				}
				
				setEventMessage($langs->trans('AccessoriesSaved'));	
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

	echo '<br /><br /><table width="100%" class="border"><tr class="liste_titre"><td>'.$langs->trans('Accessory').'</td><td>'.$langs->trans('Qty').'</td>
	<td>'.$langs->trans('Emplacement').'</td><td>'.$langs->trans('Note').'</td><td>&nbsp;</td></tr>';

	foreach($TAccessory as &$accessory) {
		
		$p=new Product($db);
		$p->fetch($accessory->fk_accessory);
		
		echo '<tr>
			<td>'.$p->getNomUrl(1).'</td>
			<td>'.$formCore->texte('', 'TAccessory['.$accessory->getId().'][qty]', $accessory->qty, 3,50).'</td>
			<td>'.$formCore->texte('', 'TAccessory['.$accessory->getId().'][emplacement]', $accessory->emplacement, 30,255).'</td>
			<td>'.$formCore->texte('', 'TAccessory['.$accessory->getId().'][note]', $accessory->note, 30,255).'</td>
			<td><a href="?action=delete&fk_object='.$object->id.'&type_object='.$object->element.'&id='.$accessory->getId().'">'.img_delete().'</a></td>
		</tr>';
		
	}
	
	echo '</table>';

	echo '<div class="tabsAction">';
	echo $formCore->btsubmit($langs->trans('Save'), 'btsave');
	echo '</div>';

	$formCore->end();

	dol_fiche_end();
	
	llxFooter();
	
}
