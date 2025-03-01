$(function(){

	$('#account').on('click', function(){
		$('#setting-modal').addClass('activate');
	});

	$('#setting-commit').on('click', function(){
		var count = +$('input:radio[name="display"]:checked').val();

		$.ajax({
			type: 'POST',
			url: 'ajax/display.php',
			data: {
				'count': count
			}
		}).done(function(data){
			var d  = $.parseJSON(data);
			//console.log(d);
			if( d.status == 0 ){

			}else if( d.status == 1 ){
				location.reload();

			}else if( d.status == 300 ){
				alert('ログインが必要です。');

			}

		}).fail(function(data){
			alert('サーバーエラー:時間を置いてから再度ご利用ください。');

		}).always(function(){
			$('#setting-modal').removeClass('activate');

		});

	});

	$('#profile-close').on('click', function(){
		$('#setting-modal').removeClass('activate');
	});

	$('#filter-caption').on('click', function(){
		if( $(this).hasClass('open') ){
			$('#filter').removeClass('open');
			$(this).removeClass('open');
		}else{
			$('#filter').addClass('open');
			$(this).addClass('open');
		}
	});

    $(document).on('input', '#freeword', function() {
		if( $(this).val() != '' ){
			if( $(this).hasClass('inputted') ){

			}else{
				$(this).addClass('inputted');
			}
		}else{
			$(this).removeClass('inputted');
		}
    });

	$('#select_shop').on('click', function(){
		$('#popup_shop').addClass('activate');
	});

	$('#area-list').on('change', function(){
		if( $(this).val() + 0 > 0 ){
			$(this).addClass('selected');
		}else{
			$(this).removeClass('selected');
		}
	});

	$('#popup_shop_close').on('click', function(){
		$('#popup_shop').removeClass('activate');
		shopInputted();
	});

	$('#popup_shop_submit').on('click', function(){
		$('#popup_shop_list input').prop("checked",true);
	})

	$('#popup_shop_cancel').on('click', function(){
		$('#popup_shop_list input').prop("checked",false);
	})

	$('#targetdate').datepicker({
		dateFormat: 'yy/mm/dd'
	});

	$('#targetdate').on('change', function(){
		if( $(this).val() != '' ){
			if( $('#datepicker').hasClass('inputted') ){

			}else{
				$('#datepicker').addClass('inputted')
			}
		}else{
			$('#datepicker').removeClass('inputted')
		}
	});

	$('#date-clear').on('click', function(){
		$('#targetdate').val('');
		$('#datepicker').removeClass('inputted');
	});

	$('.shop-p').on('change', function(){
		$(this)
			.closest('li')
			.find('input[name="shop[]"]')
			.prop('checked', $(this).prop('checked') );
	});

	$('input[name="shop[]"]').on('change',function(){
		var obj = $(this).closest('ul').find('input[name="shop[]"]');
		var shop = $(this).closest('ul').prev('p').find('.shop-p');
		var flg = true;

		obj.each(function(i, elm){
			flg *= $(elm).prop('checked');
		});
		shop.prop('checked', flg);

	});

	$('#login').on('click', function(){
		$('#login-modal').addClass('activate');
	});

	$('#login-close').on('click', function(){
		$('#login-modal').removeClass('activate');
	});

	$('#account').on('click', function(){
		$('#setting-modal').addClass('activate');
	});

	$(document).on('change', '.fav-tag', function(){
		var t = $(this);
		t.prop('disabled', true);
		//console.log('post');

		$.ajax({
			type: 'POST',
			url: 'ajax/fav.php',
			data: {
				'eid': $(this).val(),
				'status': t.prop('checked')
			}
		}).done(function(data){
			console.log(data);
			var d  = $.parseJSON(data);

			if( d.status == 1 ){

			}else if( d.status == 2 ){

			}else if( d.status == 100 ){
				alert('サーバーエラー:時間を置いてから再度ご利用ください。');
				t.prop('checked', false);

			}else if( d.status == 200 ){
				alert('ログインが必要です。');
				t.prop('checked', false);

			}

		}).fail(function(data){
			alert('サーバーエラー:時間を置いてから再度ご利用ください。');
			t.prop('checked', false);

		}).always(function(){
			t.prop('disabled', false);

		});
	});

	$(document).ready(shopInputted);

	function shopInputted(){
		var val = [];
		
		$('input[name="shop[]"]:checked').each(function(){
			var id = $(this).val();
			val.push( shop[id] );
		});

		if( val.length ){
			$('#select_shop').text(val.join(',')).addClass('inputted');
		}else{
			$('#select_shop').text('選択').removeClass('inputted');
		}
	}

	var is_loading = false;

	$('#load').on('click', function(){
		if( !is_loading ){
			is_loading = true;
			$('#waiting').css('display','none');
			$('#loading').css('display','block');

			var page = +$('input:radio[name="radio-event-category"]:checked').val();

			var data = {
				'url': location.href,
				'page': page
			}

			$.ajax({
				type: 'POST',
				url: './ajax/addEvent.php',
				data: data
			}).done(function(data){
				var d  = $.parseJSON(data);

				$.each(d, function(i, val ){
					var fav = (0 + val.fav)? true: false;
			
					var dt = $('<dt></dt>').append(
						$('<label></label>').append(
							$('<input>', {
								type: 'checkbox',
								value: val.eid,
								checked: fav,
								'class': 'fav-tag'
							}),
							$('<span></span>',{
								'class': 'material-icons-round fav'
							}).text('star')
						)
					);

					var fee = val.fee == 9999 ? '別途' : val.fee+'円';

					var dd = $('<dd></dd>').append(
						$('<a></a>', {
							href: val.url
						}).append(
							$('<p></p>', {
								'class': 'ev-info'
							}).append(
								$('<span></span>', {'class': 'ev-format '}).append(
									$('<span></span>', {'class': 'format '+val.format}).text(val.format)
								),
								$('<span></span>', {'class': 'ev-day'}).text(val.md),
								$('<span></span>', {'class': 'ev-start'}).text(val.start),
								$('<span></span>', {'class': 'ev-arena'}).text(val.area),
								$('<span></span>', {'class': 'ev-shop'}).text(val.shop),
								$('<span></span>', {'class': 'ev-pay'}).text(fee)
							),
							$('<p></p>', {
								'class': 'ev-title'
							}).text(val.title)

						)
					)

					if( page ){
						$('#event-list-future').append(dt, dd);

					}else{
						$('#event-list-past').append(dt, dd);

					}

				})

			}).fail(function(data){
				alert('サーバーエラー:時間を置いてから再度ご利用ください。');

			}).always(function(){
				is_loading = false;
				$('#waiting').css('display','block');
				$('#loading').css('display','none');

			});
		}
	});


});

