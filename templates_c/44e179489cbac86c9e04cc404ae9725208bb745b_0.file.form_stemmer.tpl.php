<?php
/* Smarty version 3.1.30, created on 2017-08-13 01:11:18
  from "/var/www/itmedia/data/www/meparts.itmediagroup.ru/slinker/templates/form_stemmer.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_598f7d06071916_73984240',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '44e179489cbac86c9e04cc404ae9725208bb745b' => 
    array (
      0 => '/var/www/itmedia/data/www/meparts.itmediagroup.ru/slinker/templates/form_stemmer.tpl',
      1 => 1502575662,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_598f7d06071916_73984240 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="form-block">
<form method="POST" action="">
	<table>
		<tr>
			<td class="form-label">Ключевая фраза: </td>
			<td class="form-input"><input type="text" name="keyphrase"></td>
		</tr>
		<tr>
			<td class="form-label"></td>
			<td class="form-button"><input type="submit" name="stemmer-submit" value="Выполнить"></td>
		</tr>
	</table>
</form>
</div><?php }
}
