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
		{if $vars.auth}
			<div class="header-block usermenu">
				<span class="user-logout"><a href="?logout=1">Выйти</a></span>
			</div>
		{/if}	
	</div> 
	

	