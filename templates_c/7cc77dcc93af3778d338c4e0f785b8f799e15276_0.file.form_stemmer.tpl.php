<?php
/* Smarty version 3.1.30, created on 2017-08-18 14:37:13
  from "/var/www/itmedia/data/www/build-home.itmediagroup.ru/slinker/templates/form_stemmer.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5996d1690daee4_85105168',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7cc77dcc93af3778d338c4e0f785b8f799e15276' => 
    array (
      0 => '/var/www/itmedia/data/www/build-home.itmediagroup.ru/slinker/templates/form_stemmer.tpl',
      1 => 1503056090,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5996d1690daee4_85105168 (Smarty_Internal_Template $_smarty_tpl) {
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
