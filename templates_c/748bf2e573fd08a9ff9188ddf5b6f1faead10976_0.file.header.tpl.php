<?php
/* Smarty version 3.1.30, created on 2017-08-13 01:10:54
  from "/var/www/itmedia/data/www/meparts.itmediagroup.ru/slinker/templates/header.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_598f7cee6c4333_31681639',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '748bf2e573fd08a9ff9188ddf5b6f1faead10976' => 
    array (
      0 => '/var/www/itmedia/data/www/meparts.itmediagroup.ru/slinker/templates/header.tpl',
      1 => 1502575845,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_598f7cee6c4333_31681639 (Smarty_Internal_Template $_smarty_tpl) {
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
