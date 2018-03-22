<?php echo $header; ?>

<style>
.slinker-result {
    padding: 2em;
    margin: 1em 0;
    border: 2px solid #7be27e;
    background-color: #e1ffe2;
    border-radius: 5px;
    color: #333;
}
.slinker-result-title {
	text-align: center;
    color: #1a9c00;
    font-weight: bold;
	text-transform: uppercase;
}


/* Таблицы */

.slinker-admin-panel table {
	background-color: #fff;
	border-collapse: collapse;
	width: 100%;
}


.slinker-admin-panel table th,
.slinker-admin-panel table td {
	padding: 0.5em;
    border: 0;
    box-sizing: border-box;
}

.slinker-admin-panel table .sort-btns {
	display: block;
	width: 100%;
}

.slinker-admin-panel table th {
	background-color: #ffb840;
	color: #EEE;
	border-bottom: 3px solid #963920;
}

.slinker-admin-panel table td {
	background-color: #fff;
	color: #333;
	border-bottom: 1px solid #ffa000;
}


.delete-saved-keyphrase {
	color: #1e91cf;
	cursor: pointer;
}


#loadingProgressG {
	display: none;
}


#loadingProgressG{
	width: 100%;
	height:5px;
	overflow:hidden;
	background-color:rgb(0,15,255);
	margin:auto;
	margin-bottom: 1em;
}

.loadingProgressG{
	background-color:rgb(255,255,255);
	margin-top:0;
	margin-left:-100%;
	animation-name:bounce_loadingProgressG;
		-o-animation-name:bounce_loadingProgressG;
		-ms-animation-name:bounce_loadingProgressG;
		-webkit-animation-name:bounce_loadingProgressG;
		-moz-animation-name:bounce_loadingProgressG;
	animation-duration:1.5s;
		-o-animation-duration:1.5s;
		-ms-animation-duration:1.5s;
		-webkit-animation-duration:1.5s;
		-moz-animation-duration:1.5s;
	animation-iteration-count:infinite;
		-o-animation-iteration-count:infinite;
		-ms-animation-iteration-count:infinite;
		-webkit-animation-iteration-count:infinite;
		-moz-animation-iteration-count:infinite;
	animation-timing-function:linear;
		-o-animation-timing-function:linear;
		-ms-animation-timing-function:linear;
		-webkit-animation-timing-function:linear;
		-moz-animation-timing-function:linear;
	width:100%;
	height:5px;
}



@keyframes bounce_loadingProgressG{
	0%{
		margin-left:-100%;
	}

	100%{
		margin-left:100%;
	}
}

@-o-keyframes bounce_loadingProgressG{
	0%{
		margin-left:-100%;
	}

	100%{
		margin-left:100%;
	}
}

@-ms-keyframes bounce_loadingProgressG{
	0%{
		margin-left:-100%;
	}

	100%{
		margin-left:100%;
	}
}

@-webkit-keyframes bounce_loadingProgressG{
	0%{
		margin-left:-100%;
	}

	100%{
		margin-left:100%;
	}
}

@-moz-keyframes bounce_loadingProgressG{
	0%{
		margin-left:-100%;
	}

	100%{
		margin-left:100%;
	}
}

</style>
<?php echo $column_left; ?>
<div id="content" class="slinker-admin-panel">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-slinker" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
	
  
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	
	
	<ul class="nav nav-tabs">
		<li<?=($slinkerpage == '')?' class="active"':'';?>><a class="slinker_tab" href="?route=module/slinker&token=<?=$token;?>">Линковка сайта</a></li>
		<li<?=($slinkerpage == 'textlinker')?' class="active"':'';?>><a class="slinker_tab" href="?route=module/slinker/textlinker&token=<?=$token;?>">Линковка текста</a></li>
		<li<?=($slinkerpage == 'sitemap')?' class="active"':'';?>><a class="slinker_tab" href="?route=module/slinker/sitemap&token=<?=$token;?>">Сбор ключевых фраз</a></li>
		<li<?=($slinkerpage == 'keyphrases')?' class="active"':'';?>><a class="slinker_tab" href="?route=module/slinker/keyphrases&token=<?=$token;?>">Сохраненные фразы</a></li>
	</ul>
	
	<div id="loadingProgressG">
		<div id="loadingProgressG_1" class="loadingProgressG"></div>
	</div>
	
	<?php 
		switch($slinkerpage) {
			case '':
			
				?>
				<div class="pre-form">
				<?php if($islinked) { ?>
					<div class="slinker-result">
						<h2 class="slinker-result-title">Результаты перелинковки</h2>
						<h3>Параметры перелинковки:</h3>
						<ul>
							<li>Режим удаления старых ссылок: <?=($deletelinks)?'включен':'выключен'; ?></li>
							<li>Минимальное расстояние между ссылками: <?=($skiplen);?> символов</li>
							<li>Линковка объектов: 
								<ul>
									<?=($slinker_link_categories)?'<li>категории (description)</li>':'';?>
									<?=($slinker_link_manufacturers)?'<li>производители (description)</li>':'';?>
									<?=($slinker_link_products)?'<li>товары (description)</li>':'';?>
									<?=($slinker_link_information)?'<li>информация (description)</li>':'';?>
									<?=($slinker_link_htmlmodule)?'<li>HTML модули (description)</li>':'';?>
								</ul>
							</li>
						</ul>
						<?php if($slinker_link_categories) { ?>
							<h3>Перелинкованные категории:</h3>
							<ul>
								<?php foreach($category_paths_linked as $key=>$row) { ?>
								<li>#<?=$row['category_id'];?> (<?=$row['language_code'];?>) <?=$row['category_name'];?> <a href="<?=$row['path'];?>" target="_blank"><?=$row['path'];?></a></li>
								<?php } ?>
							</ul>
						<?php } ?>
						
						<?php if($slinker_link_products) { ?>
							<h3>Перелинкованные товары:</h3>
							<ul>
								<?php foreach($products_paths_linked as $key=>$row) { ?>
								<li>#<?=$row['product_id'];?> (<?=$row['language_code'];?>) <?=$row['product_name'];?> <a href="<?=$row['path'];?>" target="_blank"><?=$row['path'];?></a></li>
								<?php } ?>
							</ul>
						<?php } ?>
						
						
						<?php if($slinker_link_manufacturers) { ?>
							<h3>Перелинкованные производители:</h3>
							<ul>
								<?php foreach($manufacturers_paths_linked as $key=>$row) { ?>
								<li>#<?=$row['manufacturer_id'];?> (<?=$row['language_code'];?>) <?=$row['manufacturer_name'];?> <a href="<?=$row['path'];?>" target="_blank"><?=$row['path'];?></a></li>
								<?php } ?>
							</ul>
						<?php } ?>
						
						
						<?php if($slinker_link_information) { ?>
							<h3>Перелинкованные страницы (information):</h3>
							<ul>
								<?php foreach($information_paths_linked as $key=>$row) { ?>
								<li>#<?=$row['information_id'];?> (<?=$row['language_code'];?>) <?=$row['title'];?> <a href="<?=$row['path'];?>" target="_blank"><?=$row['path'];?></a></li>
								<?php } ?>
							</ul>
						<?php } ?>
						
						
						<?php if($slinker_link_htmlmodule) { ?>
							<h3>Перелинкованные HTML модули:</h3>
							<ul>
								<?php foreach($htmlmodule_paths_linked as $key=>$row) { ?>
								<li>#<?=$row['module_id'];?> (<?=$row['language_code'];?>) <?=$row['module_name'];?></li>
								<?php } ?>
							</ul>
						<?php } ?>
						
					</div>
				  <?php } ?>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
					</div>
				  
					<div class="panel-body">
						<p>Перелинковщик SLinker выполняет размещение ссылок в текстах на сайте. Текст может быть форматирован html или просто размещен "как есть". На данной странице доступна функция перелинковки Вашего сайта.</p>

					
						<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-slinker" class="form-horizontal">
						  <div class="form-group">
							<label class="col-sm-4 control-label" for="slinker_skiplen">Минимальное расстрояние между ссылками (в символах): </label>
							<div class="col-sm-8">
							  <input type="text" id="slinker_skiplen" name="slinker_skiplen" value="1000" class="form-control">
							</div>
						  </div>
						  <div class="form-group">
							<label class="col-sm-4 control-label" for="slinker_deletelinks">Предварительная обработка имеющихся ссылок: </label>
							<div class="col-sm-8">
							  <select name="slinker_deletelinks" id="slinker_deletelinks" class="form-control">
								<option value="1" selected="selected">Удалять все ссылки</option>
								<option value="0">Не затрагивать ссылки</option>
							  </select>
							</div>
						  </div>
						  <div class="form-group">
							<label class="col-sm-4 control-label">Объекты перелинковки: </label>
							<div class="col-sm-8">
								
								<div class="col-sm-12"><label class="col-sm-5" for="slinker_link_categories">Категории (поле description):&nbsp;</label><input type="checkbox" id="slinker_link_categories" name="slinker_link_categories" value="1" checked="checked"></div>
								<div class="col-sm-12"><label class="col-sm-5"  for="slinker_link_manufacturers">Производители (поле description):&nbsp;</label><input type="checkbox" id="slinker_link_manufacturers" name="slinker_link_manufacturers" value="1" checked="checked"></div>
								<div class="col-sm-12"><label class="col-sm-5"  for="slinker_link_products">Товары (поле description):&nbsp;</label><input type="checkbox" id="slinker_link_products" name="slinker_link_products" value="1" checked="checked"></div>
								<div class="col-sm-12"><label class="col-sm-5"  for="slinker_link_information">Информация (поле description):&nbsp;</label><input type="checkbox" id="slinker_link_information" name="slinker_link_information" value="1" checked="checked"></div>
								<div class="col-sm-12"><label class="col-sm-5"  for="slinker_link_information">HTML модули (поле description):&nbsp;</label><input type="checkbox" id="slinker_link_htmlmodule" name="slinker_link_htmlmodule" value="1"></div>

							</div>
						  </div>
						  
						  
						  <div class="form-group">
							<label class="col-sm-4 control-label">Batch size (только для пакетной перелинковки): </label>
							<div class="col-sm-8">
							  <select name="batch" id="batch" class="form-control">
								<option value="1" selected="selected">1</option>
								<option value="3">3</option>
								<option value="5">5</option>
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="50">50</option>
								<option value="100">100</option>
							  </select>
							</div>
						  </div>
						  
						  <div class="form-group">
							<div class="col-sm-12 control-label">
								
								<button type="button" class="btn batch-linker-button">Пакетная перелинковка</button>
							</div>
						  </div>
						</form>
					
					</div>
				
				</div>
				



				
				<?php
			
				break;
			case 'textlinker':
			
				?>

				<?php if($islinked) { ?>
					<div class="slinker-result">
						<h2 class="slinker-result-title">Результаты линковки</h2>
						<h3>Оригинальный текст:</h3>
						<div class="slinker-text"><?=$original_text;?></div>
						<hr>
						<h3>Пролинкованный текст:</h3>
						<div class="slinker-text"><?=$linked_text;?></div>
						<hr>
						<h3>Для копирования:</h3>
						<textarea rows="3" class="form-control"><?=$linked_text;?></textarea>
					</div>
				  <?php } ?>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
					</div>
				  
					<div class="panel-body">
						<p>Перелинковщик SLinker производит линковку Вашего текста. Текст может быть форматирован html.</p>
						<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-slinker" class="form-horizontal">
						  <div class="form-group">
							<label class="col-sm-4 control-label" for="slinker_skiplen">Минимальное расстрояние между ссылками (в символах): </label>
							<div class="col-sm-8">
							  <input type="text" id="slinker_skiplen" name="slinker_skiplen" value="1000" class="form-control">
							</div>
						  </div>
						  <div class="form-group">
							<label class="col-sm-4 control-label" for="slinker_deletelinks">Предварительная обработка имеющихся ссылок: </label>
							<div class="col-sm-8">
							  <select name="slinker_deletelinks" id="slinker_deletelinks" class="form-control">
								<option value="1" selected="selected">Удалять все ссылки</option>
								<option value="0">Не затрагивать ссылки</option>
							  </select>
							</div>
						  </div>
						  <div class="form-group">
							<label class="col-sm-4 control-label">Текст или HTML: </label>
							<div class="col-sm-8">
								
								<textarea rows="10" id="slinker_text" name="slinker_text" value=""  class="form-control"></textarea>

							</div>
						  </div>
						</form>
					
					</div>
				
				</div>
				



				
				<?php
			
			
			
			
			
			
			
				break;
			case 'sitemap':
				?>
				
				
				
				<?php if($islinked) { ?>
					<div class="slinker-result">
						<h2 class="slinker-result-title">Результаты сбора ключевых слов</h2>
						<p>Всего добавлено: 
						<ul>
						<li>Страниц: <?=$sitemap_counters['pages']; ?></li>
						<li>Ключевых фраз: <?=$sitemap_counters['keyphrases'];?></li>
						<li>Ошибок: <?=$sitemap_counters['errors'];?></li>
						</ul>
						</p>
					</div>
				<?php } ?>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-pencil"></i> Сбор ключевых фраз</h3>
					</div>
				  
					<div class="panel-body">
						<p>Построитель карты сайта выполняет парсинг всех ключевых фраз в тегах meta и сохраняет результаты в базу. Результаты перезаписываются.</p>
					
					
						<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-slinker" class="form-horizontal">
						  <div class="form-group">
							<label class="col-sm-4 control-label" for="slinker_domain">Домен сайта: </label>
							<div class="col-sm-8">
							  <input type="text" id="slinker_domain" name="slinker_domain" value="<?=$server_domain; ?>" class="form-control">
							</div>
						  </div>
						</form>
					
					</div>
				
				</div>				
				
				<?php
			
				break;
			case 'keyphrases':
				print $slinker_tpl;
				
				?>
				
				
				
				<?php if($islinked) { ?>
					<div class="slinker-result">
						<?php if($success) { ?>
						<p>Ключевая фраза добавлена.</p>
						<?php } else { ?> 
						<p>Произошла ошибка при добавлении ключевой фразы.</p>
						<?php } ?>
					</div>
				<?php } ?>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-pencil"></i>Добавить фразу</h3>
					</div>
				  
					<div class="panel-body">
						<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-slinker" class="form-horizontal">
						  <div class="form-group">
							<label class="col-sm-4 control-label" for="slinker_path">Полный URL страницы </label>
							<div class="col-sm-8">
							  <input type="text" id="slinker_path" name="slinker_path" value="<?=$server_domain; ?>" class="form-control">
							</div>
						  </div>
						  <div class="form-group">
							<label class="col-sm-4 control-label" for="slinker_keyphrase">Ключевая фраза</label>
							<div class="col-sm-8">
							  <input type="text" id="slinker_keyphrase" name="slinker_keyphrase" value="" class="form-control">
							</div>
						  </div>
						  <div class="form-group">
							<label class="col-sm-4 control-label" for="slinker_title">Заголовок</label>
							<div class="col-sm-8">
							  <input type="text" id="slinker_title" name="slinker_title" value="" class="form-control">
							</div>
						  </div>
						</form>
					</div>
				</div>		
				
				<div class="slinker-saved-keywords">
				<p>На данной странице отображается сохраненная в базе копия карты сайта и ключевые слова.</p>
				<table>
					<thead>
					<tr>
						<th>№</th> 
						<th>Path</th>
						<th>Depth</th> 
						<th>Keyphrase</th>
						<th>H1</th>
						<th></th>
					</tr>
				</thead><tbody>
				<?php foreach($keyphrases as $phrase_id=>$keyphrase) { ?>
					<tr class="saved-keyphrase saved-keyphrase-<?=$phrase_id; ?>">
						<td><?=$phrase_id; ?></td>
						<td><a class="smaller" href="<?=$keyphrase['path']; ?>"><?=$keyphrase['path']; ?></a></td> 
						<td><?=$keyphrase['path_depth']; ?></td>
						<td><?=((!empty($keyphrase['keyphrase'])?('<span class="green">' . $keyphrase['keyphrase'] . '</span>'):'<span class="red">NULL</span>')); ?></td> 
						<td><?=((!empty($keyphrase['title'])?('<span class="green">' . $keyphrase['title'] . '</span>'):'<span class="red">NULL</span>')); ?></td> 
						<td><span class="delete-saved-keyphrase" data-id="<?=$phrase_id; ?>">Удалить</span></td>
					</tr>
				<?php } ?>
		
				</tbody>
				</table>
				</div>
				
				
				<?php
				break;
		}
	?>
	
	
	
	<?php if($debug_content) { ?>
		<div class="debug_content">
			<h2>DEBUG INFORMATION:</h2>
			<?php echo $debug_content; ?>
		</div>
	<?php } ?>
	
	</div>
</div>

<script>
	$('.delete-saved-keyphrase').click(function() {
		var phrase_id = $(this).data('id');
		$.ajax({
			type: 'POST',
			url: '/admin/index.php?route=module/slinker/delete_keyphrase_callback&token=<?=$token;?>',
			data: 'id=' + phrase_id,
			success: function(data){
				if(data) {
					$('tr.saved-keyphrase-' + phrase_id).hide();
				}
			},
		});
	});
	
	
	$('.batch-linker-button').click(function() {
		var this_button = this;
		$('.pre-form').html('');
		
		var completed = 0;
		//alert(slinker_link_categories);
		$(this_button).prop('disabled', true);
		$('#loadingProgressG').show();
		var slinker_skiplen = $('#slinker_skiplen').val();
		var slinker_deletelinks = $('#slinker_deletelinks').val();
		
		var slinker_link_categories = $('#slinker_link_categories').prop('checked')?1:0;
		var slinker_link_manufacturers = $('#slinker_link_manufacturers').prop('checked')?1:0;
		var slinker_link_products = $('#slinker_link_products').prop('checked')?1:0;
		var slinker_link_information = $('#slinker_link_information').prop('checked')?1:0;
		var slinker_link_htmlmodule = $('#slinker_link_htmlmodule').prop('checked')?1:0;
		
		
		var batch_recursive = function(completed) {
			console.log(completed);
			
			var batch = $('#batch').val();
			
			$.ajax({
				type: 'POST',
				url: '/admin/index.php?route=module/slinker/batch_sitelinker_callback&token=<?=$token;?>',
				data: 'slinker_skiplen=' + slinker_skiplen + '&' +
					  'slinker_deletelinks=' + slinker_deletelinks + '&' +
					  'slinker_link_categories=' + slinker_link_categories + '&' +
					  'slinker_link_manufacturers=' + slinker_link_manufacturers + '&' +
					  'slinker_link_products=' + slinker_link_products + '&' +
					  'slinker_link_information=' + slinker_link_information + '&' +
					  'slinker_link_htmlmodule=' + slinker_link_htmlmodule + '&' +
					  'batch=' + batch + '&' +
					  'completed=' + completed,

				success: function(data){
					if(data) {
						data = JSON.parse(data);
						console.log(data);
						var batch_results = '';
						$.each(data['whatlinked'], function(index, element) {
							var current_time = new Date();

							var options = {
							  hour: 'numeric',
							  minute: 'numeric',
							  second: 'numeric'
							};
							
							batch_results += '<li>' + element + ' ' + current_time.toLocaleString("ru", options) + '</li>';
						});
						if($('.pre-form ul.batch-results').length == 0) {
							$('.pre-form').append('<h3>Перелинкованные объекты</h3><ul class="batch-results"></ul>');
						}
						
						$('.pre-form ul.batch-results').append(batch_results);
						
						if(!data['iscomplete']) {
							batch_recursive(data['completed']);
						} else {
							$(this_button).prop('disabled', false);
							$('#loadingProgressG').hide();
							$('.pre-form').append('<p class="batch-end-message">Пакетная линковка завершена.</p>');
						}
					}
				},
			});
		}
		
		batch_recursive(0);
		
		
	});
	
	
</script>
<?php echo $footer; ?>
