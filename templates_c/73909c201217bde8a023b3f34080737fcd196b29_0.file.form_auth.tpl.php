<?php
/* Smarty version 3.1.30, created on 2017-08-13 01:54:08
  from "/var/www/itmedia/data/www/meparts.itmediagroup.ru/slinker/templates/form_auth.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_598f8710e378c5_97185837',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '73909c201217bde8a023b3f34080737fcd196b29' => 
    array (
      0 => '/var/www/itmedia/data/www/meparts.itmediagroup.ru/slinker/templates/form_auth.tpl',
      1 => 1502578187,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_598f8710e378c5_97185837 (Smarty_Internal_Template $_smarty_tpl) {
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
