<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2015 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/actions_accessory.class.php
 * \ingroup accessory
 * \brief   This file is an example hook overload class file
 *          Put some comments here
 */

/**
 * Class ActionsAccessory
 */
class ActionsAccessory
{
	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	 
	function formObjectOptions($parameters, &$object, &$action, $hookmanager) {
		if (in_array('propalcard', explode(':', $parameters['context'])))
		{
			 
			?><script type="text/javascript">
			function addLineAccessory(lineid) {
		
				ToAddLine = [];
				$('tr[accessory-line-id='+lineid+'] tr[accessory-id]').each(function(i,item) {
				
					var obj = {
						accessoryid:$(item).attr('accessory-id')
						,qty : $(item).find('input[rel=qty]').val()
						,emplacement : $(item).find('input[rel=emplacement]').val()
						,note : $(item).find('input[rel=note]').val()
					};
					ToAddLine.push(obj);
					
				});
				
				$.ajax({
					url:"<?php echo dol_buildpath('/accessory/script/interface.php') ?>"
					,data:{
						put:'addlines'
						,lineid:lineid
						,object_type:'<?php echo $object->element; ?>'
						,object_id:<?php echo $object->id; ?>
						,ToAddLine:ToAddLine
						,txtva:$('#tva_tx').val()
					}
					
				});
				
				openAccessories(lineid);
				
			}
			</script><?php
					
		}
	} 
	
	function printObjectLine($parameters, &$object, &$action, $hookmanager)
	{
		
		if (in_array('propalcard', explode(':', $parameters['context'])))
		{
		 
		 	foreach ($parameters as $key => $value) {
				${$key} = $value;	 
			}
		  	
		  	$object->printObjectLine($action,$line,$var,$num,$i,$dateSelector,$seller,$buyer,$selected,$extrafieldsline);
		  
		  	define('INC_FROM_DOLIBARR', true);
		    dol_include_once('/accessory/config.php');
			dol_include_once('/accessory/class/accessory.class.php');
			
		  	if($line->fk_product>0) {
		  		
				$PDOdb=new TPDOdb;
				
				$TAccessory = TAccessory::getAccessories($PDOdb, $line->fk_product, 'product');
				
				if(!empty($TAccessory)) {
					
					global $db, $langs;
					$langs->load('accessory@accessory');
					dol_include_once('/product/class/product.class.php');
					
					?>
					
					<script type="text/javascript">
						$("#row-<?php echo $line->id; ?>>td").first().append('&nbsp;<a href="javascript:openAccessories(<?php echo $line->id; ?>)">A</a>&nbsp;')
					</script>
					
					<tr accessory-line-id="<?php echo $line->id ?>" ><td colspan="0"><?php //style="display:none"
						
						$formCore = new TFormCore;
						
						echo '<table width="100%" class="liste">';
					
						foreach($TAccessory as &$accessory) {
							
							$p=new Product($db);
							if($p->fetch($accessory->fk_accessory)>0) {
								echo '<tr accessory-id="'.$accessory->getId().'">
									<td style="padding-left:50px;">'.$p->getNomUrl(1).'</td>
									<td>'.$formCore->texte('', 'TAccessory['.$line->id.']['.$accessory->getId().'][qty]', $accessory->qty, 3,50, ' rel="qty" ').'</td>
									<td>'.$formCore->texte('', 'TAccessory['.$line->id.']['.$accessory->getId().'][emplacement]', $accessory->emplacement, 30,255, ' rel="emplacement" ').'</td>
									<td>'.$formCore->texte('', 'TAccessory['.$line->id.']['.$accessory->getId().'][note]', $accessory->note, 30,255, ' rel="note"').'</td>
									<td>'.$formCore->checkbox1('', 'TAccessory['.$line->id.']['.$accessory->getId().'][add]', 1,0,' rel="checked" ').'</td>
								</tr>';
								
							}
							
						}	
						
						echo '<tr><td colspan="5" align="right"><input type="button" onclick="addLineAccessory('.$line->id.')" value="'.$langs->trans('LinkAccessories').'" /></td></tr>';

						echo '</table>';

					?></td></tr>
					<?php

				}
				
		  		
		  	}
			  	
		  	return 1;
		}
		return 0;
	}
}