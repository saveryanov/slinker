<?php
/* Smarty version 3.1.30, created on 2017-08-18 14:36:25
  from "/var/www/itmedia/data/www/build-home.itmediagroup.ru/slinker/templates/form_auth.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5996d1396c3173_12983023',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6f67ab66d745d36ab5f3a99303506743f1226c36' => 
    array (
      0 => '/var/www/itmedia/data/www/build-home.itmediagroup.ru/slinker/templates/form_auth.tpl',
      1 => 1503056090,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5996d1396c3173_12983023 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="form-block">
<form method="POST" action="">
	<table>
		<tr>
			<td class="form-label">Логин: </td>
			<td class="form-input"><input type="text" name="login"></td>
		</tr>
		<tr>
			<td class="form-label">Пароль: </td>
			<td class="form-input"><input type="password" name="password"></td>
		</tr>
		<tr>
			<td class="form-label"></td>
			<td class="form-button"><input type="submit" name="auth-enter" value="Вход"></td>
		</tr>
	</table>
</form>
</div><?php }
}
