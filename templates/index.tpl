{include file="header.tpl" vars=$vars}

{if $vars.auth}
	{include file="topmenu.tpl" vars=$vars}
{/if}

	<div id="content">
		<h1>{$vars.title}</h1>
		{$vars.content}	
	</div>

{include file="footer.tpl" vars=$vars}
