function lex_getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function lex_setCookie(name, value, options) {
  options = options || {};

  var expires = options.expires;

  if (typeof expires == "number" && expires) {
    var d = new Date();
    d.setTime(d.getTime() + expires * 1000);
    expires = options.expires = d;
  }
  if (expires && expires.toUTCString) {
    options.expires = expires.toUTCString();
  }

  value = encodeURIComponent(value);

  var updatedCookie = name + "=" + value;

  for (var propName in options) {
    updatedCookie += "; " + propName;
    var propValue = options[propName];
    if (propValue !== true) {
      updatedCookie += "=" + propValue;
    }
  }

  document.cookie = updatedCookie;
}


(function($) {
	$(document).ready(function() {
		// Инициализация
		var check_interval = 10000;
		if($('#schatAudio').length == 0) {
			$('<audio id="schatAudio"><source src="/schat/sound/notify.ogg" type="audio/ogg"><source src="/schat/sound/notify.mp3" type="audio/mpeg"><source src="/schat/sound/notify.wav" type="audio/wav"></audio>').appendTo('body');
		}

		//lex_setCookie('schat_guestid', '');
		//lex_setCookie('schat_guestname', '');
		
		// Загрузка куки
		var guest_name = '';
		var guest_id = '';
		if(lex_getCookie('schat_guestid') && lex_getCookie('schat_guestname')) {
			guest_id = lex_getCookie('schat_guestid');
			guest_name = lex_getCookie('schat_guestname');
		}
		
		
		var start_chat = function(start_button_this, hided) {
			// Сбор данных о чате
			var userid = $(start_button_this).data('userid');
			$.ajax({
				type: 'POST',
				url: '/schat/ajax/getguestchat.php',
				data: 'userid=' + userid,
				success: function(data){
					if(data) {
						var chat_modal = data;
						
						if($(".schat-modal-" + userid ).length == 0) {
												
							$('body').append(chat_modal);
							
							if(hided) {
								$(".schat-modal-" + userid ).hide();
							}
							
							$("#schat-modal-" + userid).draggable({ handle: "#schat-modal-drag-handle-" + userid, scroll: true});
							
							$("#schat-modal-" + userid).resizable({
								//maxHeight: 250,
								maxWidth: 600,
								minHeight: 200,
								minWidth: 250,
							});
							
							$(window).on('resize', function () {
								var position = $(".schat-modal-" + userid).position();
								if(($(window).width() - 50) < position.left) {
									$(".schat-modal-" + userid).css({ left: ($(window).width() - 50) + 'px' });
								}
								if(($(window).height() - 50) < position.top) {
									$(".schat-modal-" + userid).css({ top: ($(window).height() - 50) + 'px' });
								}
							});
							
							if(guest_id) {
								$.ajax({
									type: 'POST',
									url: '/schat/ajax/firstmessage.php',
									data: 'id=' + userid,
									success: function(data){										
										// получаем чужое сообщение и цепляем его в конец окна вывода
										var schat_messages = $(data).filter('.schat-message').each(function( index, message_element ) {
											$( '.schat-body-' + userid ).append(message_element);		
										});
										$( '.schat-body-' + userid ).animate({ scrollTop: $( '.schat-body-' + userid )[0].scrollHeight}, 0);
										
									}
								});
							}
							
							
							
							// Инициализация кнопок email
							$( '.schat-email-send-button-' + userid ).click(function(){
								var name = $.trim($('.schat-email-name-input-' + userid).val());
								var email = $.trim($('.schat-email-email-input-' + userid).val());
								var message = $.trim($('.schat-email-message-input-' + userid).val());
								
								if(name && email && message) {
									$('.schat-email-message-input-' + userid).val('');
									
									$.ajax({
										type: 'POST',
										url: '/schat/ajax/sendemail.php',
										data: 'userid=' + userid + '&name=' + name + '&email=' + email + '&message=' + message,
										success: function(data){
											$('.schat-email-' + userid + ' .schat-email-result').show();
											$('.schat-email-' + userid + ' .schat-email-result').html(data);
										}
									});
								} else {
									$('.schat-email-' + userid + ' .schat-email-result').show();
									$('.schat-email-' + userid + ' .schat-email-result').html('Пожалуйста, заполните все поля.');
								}
							});
							
							// Инициализация кнопок whatsapp
							$( '.schat-whatsapp-send-button-' + userid ).click(function(){
								var number = $.trim($(this).data('wanumber'));
								var message = $.trim($('.schat-whatsapp-message-input-' + userid).val());
								
								if(message) {
									$('.schat-whatsapp-message-input-' + userid).val('');
									window.open('https://api.whatsapp.com/send?phone=' + number + '&text=' + (message), '_blank');
								} else {
									$('.schat-whatsapp-' + userid + ' .schat-whatsapp-result').show();
									$('.schat-whatsapp-' + userid + ' .schat-whatsapp-result').html('Пожалуйста, заполните поле сообщения.');
								}
							});
							
							// Инициализация кнопок viber
							$( '.schat-viber-send-button-' + userid ).click(function(){
								var number = $.trim($(this).data('vnumber'));
								var message = $.trim($('.schat-viber-message-input-' + userid).val());
								
								if(message) {
									$('.schat-viber-message-input-' + userid).val('');
									//window.open('viber://chat?number=' + number + '&text=' + (message), '_blank');
									window.open('viber://pa?chatURI=' + number + '&context=SChat&text=' + (message));
								} else {
									$('.schat-viber-' + userid + ' .schat-viber-result').show();
									$('.schat-viber-' + userid + ' .schat-viber-result').html('Пожалуйста, заполните поле сообщения.');
								}
							});
							
							
							// Инициализация кнопок чата
							$( '.schat-tabs-' + userid + ' .schat-tab-button' ).click(function(){
								if($('.schat-tab-button').hasClass('active')){
									$('.schat-tab-button').removeClass('active')
								}
								$(this).addClass('active');	
								var tabname = $(this).data('tabname');
								$('.schat-modal-' + userid + ' .schat-tab-block').hide();
								$('.' + tabname + '-' + userid).show();
							});
							
							$( '.schat-close-button-' + userid ).click(function(){

								$( '.schat-modal-' + userid ).hide();
								//clearInterval(interval_listener_id);	// Раскомментировать если нужно не опрашивать сервер после закрытия окна
							});
							
							$( '.schat-hide-button-' + userid ).click(function(){
								var prevheight = $( '#schat-modal-' + userid ).data('prevheight');
								var newheight = $( '#schat-modal-' + userid ).height();
								$(this).toggleClass('hide');
								if($( '#schat-modal-' + userid ).height() > 100) {
									
									$( '#schat-modal-' + userid ).animate({
											height: "50px",
										}, 
										{ duration: 200, queue: false }
									);
									//$(this).addClass('schat-modal-hided');
									$( '#schat-modal-' + userid ).data('prevheight', newheight);
								} else {
									$( '#schat-modal-' + userid ).animate({
											height: prevheight,
										},
										{ duration: 200, queue: false }
									);
									//$(this).removeClass('schat-modal-hided');
								}
							});
						
							
						} else {
							$(".schat-modal-" + userid ).show();
						}
						
							
						// Если пользователь тут первый раз то спрашиваем имя чтобы установить id
						if(!guest_id) {
							// Создание модального окна чата
							var auth_form = '<div class="schat-auth-form schat-auth-form-' + userid + '">' +
								'<input class="schat-addguest-input" placeholder="Как к Вам обращаться?"><div class="schat-addguest-button"><input type="button" value="Отправить"></div></div>';
							
							if($(".schat-modal-" + userid + ' .schat-auth-form' ).length == 0) {
								$('.schat-modal-' + userid + ' .schat-body').append(auth_form);	
							} else {
								$(".schat-modal-" + userid + ' .schat-auth-form' ).show();
							}
							$( '.schat-footer' ).hide();
							$( '.schat-body' ).addClass('schat-body-nofooter');
								
							
							$( '.schat-addguest-button' ).click(function(){
								
								var guestname_input = $.trim($( '.schat-addguest-input' ).val());
								if(guestname_input) {
									
									$( '.schat-addguest-input' ).val('');
									
									$.ajax({
										type: 'POST',
										url: '/schat/ajax/addguest.php',
										data: 'name=' + guestname_input + '&referrer=' + window.location.href + '&id=' + userid,
										success: function(data){
											
											lex_setCookie('schat_guestid', data);
											lex_setCookie('schat_guestname', guestname_input);
											guest_id = data;
											guest_name = guestname_input;
											
											$(start_button_this).trigger('click');
																										
											$.ajax({
												type: 'POST',
												url: '/schat/ajax/firstmessage.php',
												data: 'id=' + userid,
												success: function(data){
													// получаем чужое сообщение и цепляем его в конец окна вывода
													var schat_messages = $(data).filter('.schat-message').each(function( index, message_element ) {
														$( '.schat-body-' + userid ).append(message_element);	
													});
													$( '.schat-body-' + userid ).animate({ scrollTop: $( '.schat-body-' + userid )[0].scrollHeight}, 0);														
												}
											});
											
											
										}
									});
								} // TODO: Вывод сообщения валидации
							});
							
						} else {
								
							$('.schat-auth-form' ).hide();
							$('.schat-footer').show();
							$('.schat-body').removeClass('schat-body-nofooter');
							
							// Запуск слушателя нисходящего канала
							var interval_listener_id = setInterval(function() {
								
								$.ajax({
									type: 'POST',
									url: '/schat/ajax/checkmessage.php',
									data: 'id=' + userid,
									success: function(data){
										// получаем чужое сообщение и цепляем его в конец окна вывода
										var schat_messages = $(data).filter('.schat-message').each(function( index, message_element ) {
											if($('.schat-message-' + $(message_element).data('messageid')).length == 0) {
												$( '.schat-body-' + userid ).append(message_element);	
												$('#schatAudio')[0].play();	
												$( '.schat-body-' + userid ).animate({ scrollTop: $( '.schat-body-' + userid )[0].scrollHeight}, 500);
												$( '.schat-modal-' + userid ).show();
											}
										});
										
										// TODO: Загружать вместе с сообщениями или отдельно? (с сообщениями экономнее)
										// получаем индикаторы прочтения
										var schat_messages = $(data).filter('.schat-message-watched-container').each(function( index, message_element ) {
											$( '.schat-message-' + $(message_element).data('messageid') + ' .schat-message-watched-indicator' ).show();
										});
									}
								});
							}, check_interval);
									
							$( '.schat-send-button-' + userid ).click(function(){			
								var guest_message = $.trim($( '.schat-message-input-' + userid ).val());
								if (guest_message) {
									$( '.schat-message-input-' + userid ).val('');
									$.ajax({
										type: 'POST',
										url: '/schat/ajax/sendmessage.php',
										data: 'id=' + userid + '&mess=' + guest_message + '&referrer="' + window.location.href + '"',
										success: function(data) {
											// получаем свое сообщение и цепляем его в конец окна вывода
											$( '.schat-body-' + userid ).append(data);
											$( '.schat-body-' + userid ).animate({ scrollTop: $( '.schat-body-' + userid )[0].scrollHeight}, 500);
										}
									});
								} // TODO: Вывод сообщения валидации?
							});
							
							$('.schat-message-input-' + userid).keyup(function(event){
								if(event.keyCode == 13){
									event.preventDefault();
									$( '.schat-send-button-' + userid ).trigger('click');
								}
							});
						
						}
					}
				}
			});
		};
		
		$('.schat-start-button').click(function(){
			var start_button_this = this;				
			start_chat(start_button_this, false);
		});
		
		
		if(guest_id) {
			$('.schat-start-button').each(function(index, element) {
				start_chat(element, true);
			});
		}
		
	});
})(jQuery)

