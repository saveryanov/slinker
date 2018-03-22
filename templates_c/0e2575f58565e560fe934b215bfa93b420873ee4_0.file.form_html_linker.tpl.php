<?php
/* Smarty version 3.1.30, created on 2017-08-18 14:37:09
  from "/var/www/itmedia/data/www/build-home.itmediagroup.ru/slinker/templates/form_html_linker.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5996d16568b867_83920915',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0e2575f58565e560fe934b215bfa93b420873ee4' => 
    array (
      0 => '/var/www/itmedia/data/www/build-home.itmediagroup.ru/slinker/templates/form_html_linker.tpl',
      1 => 1503056090,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5996d16568b867_83920915 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="noindex-search-form-block">
<form method="POST" action="">
	<table style="width: 100%;">
		<tr>
			<td class="form-label">Текст: </td>
			<td class="form-input"><textarea style="width: 100%; height: 300px;" name="text">
				<p>Интернет-магазин «СИТИЛИНК» предлагает запоминающие устройства для персонального компьютера – жесткие диски, предназначенные для записи и хранения данных. Данное устройство обеспечивает стабильную и надежную работу системы, а также определяет ее быстродействие. Цена зависит от объема модулей, тактовой частоты и других параметров. Купить запоминающие устройства в интернет-магазине «СИТИЛИНК» можно в режиме онлайн. Мы осуществляем доставку Ваших покупок по Москве, Санкт-Петербургу, Казани, Нижнему Новгороду, Красноярску, Перми, Екатеринбургу, Уфе, Краснодару, Новосибирску, Ростову-на-Дону, Челябинску, Самаре и другим городам России.</p>
				<table>А вот в теге TABLE не будет линковаться, потому что этот тег не в списке допустимых. Интернет-магазин «СИТИЛИНК» предлагает запоминающие устройства для персонального компьютера – жесткие диски, предназначенные для записи и хранения данных. Данное устройство обеспечивает стабильную и надежную работу системы, а также определяет ее быстродействие. Цена зависит от объема модулей, тактовой частоты и других параметров. Купить запоминающие устройства в интернет-магазине «СИТИЛИНК» можно в режиме онлайн. Мы осуществляем доставку Ваших покупок по Москве, Санкт-Петербургу, Казани, Нижнему Новгороду, Красноярску, Перми, Екатеринбургу, Уфе, Краснодару, Новосибирску, Ростову-на-Дону, Челябинску, Самаре и другим городам России.</table>
			</textarea></td>
		</tr>
		<tr>
			<td class="form-label">Плотность ссылок (будут расположены не ближе чем значение): </td>
			<td class="form-input"><input type="text" name="toskip" value="0"></td>
		</tr>
		<tr>
			<td class="form-label">Предварительно удалить ссылки в тексте: </td>
			<td class="form-input"><input type="checkbox" name="deletelinks" value="1"></td>
		</tr>
		<tr>
			<td class="form-label"></td>
			<td class="form-button"><input type="submit" name="noindex-search" value="Найти"></td>
		</tr>
	</table>
</form>
</div><?php }
}
