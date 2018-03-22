(function($) {
	$(document).ready(function() {
		
		$('textarea').each(function(index, element) {
			if(!$(element).hasClass('slinker-textarea-processed')) {
				if($(element).hasClass('note-codable')) return;
				$(element).addClass('slinker-textarea-processed');
				var domain = document.domain;
				var current_path = '';
				if($('input[name="keyword"]').length != 0) {
					current_path = $('input[name="keyword"]').val();
				}
				
				var current_url = domain + '/' + current_path;
				var linker_button = '<div class="slinker-textarea-processor" data-textareaid="' + index + '">' +
					'<div class="slinker-textarea-processor-title">Линковщик (SLinker)</div>' + 
					'<select class="slinker-textarea-processor-deletelinks form-control slinker-textarea-processor-input" name="slinker-textarea-processor-deletelinks" data-textareaid="' + index + '">' + 
						'<option value="1">Удалять ссылки</option>' +
						'<option value="0">Не удалять ссылки</option>' +
					'</select>' +
					'<input type="text" class="slinker-textarea-processor-toskip form-control slinker-textarea-processor-input" name="slinker-textarea-processor-toskip" data-textareaid="' + index + '" value="1000" placeholder="Расстояние">' + 
					'<input style="width: 300px;" type="text" class="slinker-textarea-processor-path form-control slinker-textarea-processor-input" name="slinker-textarea-processor-path" data-textareaid="' + index + '" value="' + current_url + '" placeholder="Полный URL страницы">' + 
					'<button class="slinker-textarea-button btn slinker-textarea-button-' + index + '" data-textareaid="' + index + '">Линковка текста</button>' +
					'<div class="loadingProgressSlinkerWrapper"><div class="loadingProgressSlinker"></div></div>' +
					'</div>';
				$(element).attr('data-textareaid', index);
				$(element).before(linker_button);
				
				$('.slinker-textarea-button-' + index ).click(function(e) {
					e.preventDefault();
					e.stopPropagation();
					
					var button = this;
					if($.isFunction($.fn.summernote)) {
						var text = $('textarea[data-textareaid="' + index + '"]').summernote('code');
					} else {
						var text = $('textarea[data-textareaid="' + index + '"]').val();
					}
					var toskip = $('.slinker-textarea-processor-toskip[data-textareaid="' + index + '"]').val();
					var deletelinks = $('.slinker-textarea-processor-deletelinks[data-textareaid="' + index + '"]').val();
					var path = $('.slinker-textarea-processor-path[data-textareaid="' + index + '"]').val();
					
					if(text) {
						$(button).prop('disabled', true);
						$('.slinker-textarea-processor-toskip[data-textareaid="' + index + '"]').prop('disabled', true);
						$('.slinker-textarea-processor-deletelinks[data-textareaid="' + index + '"]').prop('disabled', true);
						$('.slinker-textarea-processor-path[data-textareaid="' + index + '"]').prop('disabled', true);
						$(button).parent().find('.loadingProgressSlinkerWrapper').show();
						$(button).parent().addClass('processing');
						
						$.ajax({
							type: 'POST',
							url: '/slinker/ajax/link.php',
							data: 'text=' + text + '&toskip=' + toskip + '&deletelinks=' + deletelinks + '&path=' + path,
							success: function(data){
								if(data) {
									if($.isFunction($.fn.summernote)) {
										$('textarea[data-textareaid="' + index + '"]').summernote('code', data);										
									} else {
										$('textarea[data-textareaid="' + index + '"]').val(data);
									}
									
								}
								$(button).prop('disabled', false);
								$('.slinker-textarea-processor-toskip[data-textareaid="' + index + '"]').prop('disabled', false);
								$('.slinker-textarea-processor-deletelinks[data-textareaid="' + index + '"]').prop('disabled', false);
								$('.slinker-textarea-processor-path[data-textareaid="' + index + '"]').prop('disabled', false);
								$(button).parent().find('.loadingProgressSlinkerWrapper').hide();
								$(button).parent().removeClass('processing');
							},
						});
					}
				}); 
			}
		});
		
	});
})(jQuery)

