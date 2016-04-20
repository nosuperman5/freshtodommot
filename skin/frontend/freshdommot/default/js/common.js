(function($) {
    "use strict";
    $(document).ready(function() {
        /* (function(){
            if($(window).width() > 768) {
                $(document).on('mouseover', '#nav > li > a', function(){
                    var $this = $(this),
                        $departmentsDropdownWrapNav = $this.closest('.departments__dropdown__wrap-nav'),
                        $dropdown = $this.find('+ ul'),
                        $positionTop = $this.position().top,
                        $wrapNavHeight = $departmentsDropdownWrapNav.outerHeight(),
                        $dropdownHeight = $dropdown.outerHeight(),
                        $summarySize = $positionTop + $dropdownHeight,
                        $positionDropdown = $positionTop - ($summarySize - $wrapNavHeight);

                    if($summarySize > $wrapNavHeight) {
                        $dropdown.css('top', $positionDropdown);
                    } else {
                        $dropdown.css('top', $positionTop);
                    };
                });
            };
        })();

        $(document).on('click', '[data-toggle-shadow]', function() {
            $('[data-toggle-dropdown]').removeClass("open");
            $('body').removeClass("open-dropdown");
        });

        $(document).on('click', '[data-toggle-btn]', function(){
            var $this = $(this),
                dataToggleContainer = $this.closest('[data-toggle-container]'),
                dataToggleDropdown = dataToggleContainer.find('[data-toggle-dropdown]'),
                $body = $('body');

            dataToggleDropdown.toggleClass("open");
            $body.toggleClass("open-dropdown");

            return false;
        }); */

        //$(document).on('click', 'a[href^="#"]', function() {
        //    var e = $(this).attr("href");
        //
        //    $("html, body").animate({
        //        "scrollTop": $(e).offset().top
        //    }, 500);
        //
        //    return false;
        //});
		$(document).on('click', '.quick-view', function(){
			//var $this = $(this);
			$.ajax({url: $(this).attr('href'), 
				success: function(result){
				$("#productOverview").html(result);
			}});
			
			return false;
		});
		
		$(document).on('click', '.update-cart', function(){
			var $url = $(this).attr('data-redirect');
			$.ajax({url: $url, 
				success: function(result){
					var result = JSON.parse(result);
					var counter = $('.products__item.item' + result.id + ' .products__item__counter');
					var qty = result.qty;
					$("#minicart_head").html(result.minicart);
					
					counter.find('span').html(qty);
					if(qty == 0){
						$('.product' + result.id + ' .product-overview__control__btn--minus').hide();
						$('.product-overview__control__btn--minus').hide();
						$('.products__item.item' + result.id + ' .update-cart.minus').hide();
						$('.fresh-list-product.product' + result.id + ' .update-cart.minus').hide();
						counter.hide();
						//products__item__btn update-cart minus
					} else {
						$('.product' + result.id + ' .product-overview__control__btn--minus').show(); //.modal-dialog
						$('.products__item.item' + result.id + ' .update-cart.minus').show();
						$('.fresh-list-product.product' + result.id + ' .update-cart.minus').show();
						counter.show();
					}
					$('.modal-dialog.product' + result.id + ' .product-overview__btn strong').html(qty);
					
			}});
		});
		$(document).on('click', '#coupon-form-window .close', function(){
			$('#coupon-form-window').hide();
		});
		$(document).on('click', '#checkout-coupon', function(){
			$('#coupon-form-window').show();
		});
		/*$(document).on('click', '#rewardpointsreferfriends-link', function(){
			$('#rewardpointsreferfriends-popup').show();
			return false;
		});*/
		$(document).on('click', '.wishlist-link', function(){
			var $url = $(this).attr('href');
			$.ajax({url: $url, 
				success: function(result){
					var result = JSON.parse(result);
					$(this).addClass('active');
			}});
			return false;
		});
    });
})(jQuery);
