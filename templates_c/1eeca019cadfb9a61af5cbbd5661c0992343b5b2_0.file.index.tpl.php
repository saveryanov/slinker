<?php
/* Smarty version 3.1.30, created on 2017-08-13 19:47:15
  from "/var/www/itmedia/data/www/meparts.itmediagroup.ru/slinker/templates/index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_599082939d1593_61029695',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1eeca019cadfb9a61af5cbbd5661c0992343b5b2' => 
    array (
      0 => '/var/www/itmedia/data/www/meparts.itmediagroup.ru/slinker/templates/index.tpl',
      1 => 1502642820,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:topmenu.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_599082939d1593_61029695 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('vars'=>$_smarty_tpl->tpl_vars['vars']->value), 0, false);
?>


<?php if ($_smarty_tpl->tpl_vars['vars']->value['auth']) {?>
	<?php $_smarty_tpl->_subTemplateRender("file:topmenu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('vars'=>$_smarty_tpl->tpl_vars['vars']->value), 0, false);
?>

<?php }?>

	<div id="content">
		<h1><?php echo $_smarty_tpl->tpl_vars['vars']->value['title'];?>
</h1>
		<?php echo $_smarty_tpl->tpl_vars['vars']->value['content'];?>
	
	</div>

<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('vars'=>$_smarty_tpl->tpl_vars['vars']->value), 0, false);
?>

<?php }
}
