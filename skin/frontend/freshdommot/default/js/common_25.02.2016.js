(function($) {
    "use strict";
    $(document).ready(function() {
		$(document).on('click', 'a.quick-view', function() {
			var href = $(this).attr('href');
			$.ajax({url: href, 
				success: function(result){
					$("#productOverview").html(result);
				}
			});
			return false;
		});
		
		
		$(document).on('click', '.btn-cart', function() {
			//console.log('product-addtocart-button');
			var postForm = $("#product_addtocart_form");
			var postData = postForm.serializeArray();
			$.ajax({
				url : postForm.attr('action'),
				type: "POST",
				data : postData,
				success:function(result) 
				{
					var obj =  JSON.parse(result)
					//$('#global_messages').html(obj.global_messages);
					$('#minicart_head').html(obj.minicart);
					$('#productOverview').html(obj.product_view);
					//$('#header-cart').addClass('open');
					//$('body').addClass('open-dropdown');
					if($(this).hasClass('product-overview__control__btn--plus')){
						var qty = $('.product-overview__btn strong').html();
						$('.product-overview__btn strong').html(qty + 1);
					}
				}
			});
			
			return false;
		});
	});
})(jQuery);
/*(function($) {
    "use strict";
    $(document).ready(function() {
        $('a[href^="#"]').click(function() {
            var e = $(this).attr("href");

            $("html, body").animate({
                "scrollTop": $(e).offset().top
            }, 500);

            return false;
        });
    });
})(jQuery);*/