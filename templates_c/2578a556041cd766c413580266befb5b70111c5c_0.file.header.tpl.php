<?php
/* Smarty version 3.1.30, created on 2017-08-18 14:36:25
  from "/var/www/itmedia/data/www/build-home.itmediagroup.ru/slinker/templates/header.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5996d13971d339_86453235',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2578a556041cd766c413580266befb5b70111c5c' => 
    array (
      0 => '/var/www/itmedia/data/www/build-home.itmediagroup.ru/slinker/templates/header.tpl',
      1 => 1503056091,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5996d13971d339_86453235 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html dir="ltr" lang="ru">
<head>
	<meta charset="UTF-8" />
	<title>Linker Admin panel</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
	<link rel="shortcut icon" href="imgs/favicon.ico" type="image/x-icon">
	
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="css/style.css" type="text/css" rel="stylesheet" />
</head>


<body>

	<div id="header">
		<div class="header-block logo">
			<span class="logoimg"></span>
			<span class="sitename">Linker</span>
		</div>
		<?php if ($_smarty_tpl->tpl_vars['vars']->value['auth']) {?>
			<div class="header-block usermenu">
				<span class="user-logout"><a href="?logout=1">Выйти</a></span>
			</div>
		<?php }?>	
	</div> 
	

	<?php }
}
