jQuery(window).on('load', function () {
    /*----running line home top--------*/
    if(jQuery('.producers').length) {
        jQuery('.producers').marquee({
            pauseOnHover: false
        });
    }
    /*----------*/

    /*----phone mask--------*/
    if(jQuery('#phone').length) {
        jQuery.jMaskGlobals.translation["9"] = jQuery.jMaskGlobals.translation["0"];
        delete jQuery.jMaskGlobals.translation["0"];
        jQuery("#phone").mask('+380 (99) 999-99-99');
    }
    /*----------*/

    /*----minicart, delivery and declination--------*/
    function sumCart() {
        var sum_price = 0;
        jQuery('.right-cart .item .price').each(function() {
            var total_price = jQuery(this).text().replace(/[^.\d]+/g,"").replace(/[^\d;]/g, '');
            var qty = jQuery(this).closest('.bottom').find('input').val();
            sum_price += +total_price*qty;
        });
        jQuery('#total_order').val(sum_price);
        jQuery('.total div span').text(sum_price);
        var price = jQuery('.right-cart .total .price').text().replace(/[^.\d]+/g,"").replace(/[^\d;]/g, '');
        var total_delivery = 1500 - price;
        if(price < 1500) {
            jQuery('.delivery_free span:nth-child(1)').text(total_delivery);
            var declination = (number, txt, cases = [2, 0, 1, 1, 1, 2]) => txt[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]];
            var uah = 'грив'  + declination(total_delivery, ['ну', 'ны', 'ен']);
            jQuery('.delivery_free span:nth-child(2)').text(uah);
            jQuery('.delivery_free').show();
            jQuery('.delivery_ok').hide();
        } else if(price == 0) {
            jQuery('.delivery_free').hide();
            jQuery('.delivery_ok').hide();
            jQuery('.content-full .total').hide();
        } else {
            jQuery('.delivery_free').hide();
            jQuery('.delivery_ok').show();
        }
    }
    /*----------*/

    /*----counter cart--------*/
    function calkCount() {
        var count = 0;
        jQuery('.right-cart .fieldCount').each(function() {
            count += Number($(this).val());
        });
        if(count != 0) {
            //console.log(sum);
            jQuery('header .cart span').text(count);
        } else {
            jQuery('header .cart span').text('0');
        }
    }
    /*----------*/

    /*----plus/minus cart--------*/
    function minus() {
        jQuery(document).on('click', '.dec', function() {
            var qty = jQuery(this).parent().find('input');
            var count = parseInt(qty.val()) - 1;
            count = count < 1 ? 1 : count;
            qty.val(count);
            sumCart();
            calkCount();
        });
    }
    function plus() {
        jQuery(document).on('click', '.inc', function() {
            var qty = jQuery(this).parent().find('input');
            qty.val(parseInt(qty.val()) + 1);
            sumCart();
            calkCount();
        });
    }
    /*----------*/

    /*----select flavor--------*/
    jQuery('.items li .dropdownhidden li a').click(function() {
        var data_flavor = jQuery(this).text();
        jQuery(this).closest('.border').find('.activetrigger').removeClass('unactive');
        jQuery(this).closest('.border').find('.add_to_cart').attr('data_flavor', data_flavor);
        jQuery(this).closest('.border').find('.add_to_cart').removeClass('added_to_cart');
        jQuery(this).closest('.border').find('.add_to_cart span').text('Добавить');
    });
    /*----------*/

    /*----add to cart--------*/
    jQuery('.items li .add_to_cart').click(function(e) {
        e.preventDefault();
        var selected = jQuery(this).closest('.border').find('.trigger').text();
        if(selected == 'Выбрать вкус') {
            jQuery(this).closest('.border').find('.trigger').addClass('unactive');
        } else {
            jQuery('.content-empty').hide();
            jQuery('.content-full').show();
            jQuery('.cart-block').show();
            jQuery('.right-cart').animate({right: '0px'}, 500);
            jQuery('.bg-close').animate({opacity: '1'}, 500);
            jQuery(this).find('span').text('Добавлено');
            jQuery(this).addClass('added_to_cart');
            jQuery('.right-cart').removeClass('empty_cart');
            jQuery('.content-full .total').show();
            plus();
            minus();
            calkCount();
            sumCart();
        }
    });
    /*----------*/

    /*----bg-close--------*/
    jQuery('.bg-close').click(function() {
        jQuery('.right-cart').animate({right: '-415px'}, 500);
        jQuery('.bg-close').animate({opacity: '0'}, 500);
    });
    jQuery('.close').click(function() {
        jQuery('.right-cart').animate({right: '-415px'}, 500);
        jQuery('.bg-close').animate({opacity: '0'}, 500);
    });
    jQuery('#errormodal .black-btn').click(function(e) {
        e.preventDefault();
        jQuery('.modal').animate({opacity: '0'}, 500);
        setTimeout(function() {
            jQuery('.modal').hide();
        }, 500);
    });

    /*----------*/

    /*----empty cart--------*/
    jQuery('#opencart').click(function(e) {
        e.preventDefault();
        jQuery('.right-cart').animate({right: '0px'}, 500);
        jQuery('.bg-close').animate({opacity: '1'}, 500);
        if(!jQuery('.item').length) {
            jQuery('.right-cart').addClass('empty_cart');
            jQuery('.content-empty').show();
            jQuery('.content-full').hide();
        }else{
            jQuery('.content-empty').hide();
            jQuery('.content-full').show();
        }

    });
    /*----------*/

    /*----delete cart--------*/
    jQuery(document).on('click', '.delete', function() {
        setTimeout(function() {
            var price = jQuery('.right-cart .total .price').text().replace(/[^.\d]+/g,"").replace(/[^\d;]/g, '');
            if(price == 0) {
                jQuery('.content-full .total').hide();
            }
        }, 50);
        calkCount();
        sumCart();
    });
    /*----------*/

    /*----cart delete button--------*/
    jQuery('.first_delete').click(function(event) {
        event.preventDefault();
    });
    jQuery('.next_delete').click(function(event) {
        event.preventDefault();
    });
    jQuery('.last_delete').click(function(event) {
        event.preventDefault();
    });
    /*----------*/

    /*----cart delete button--------*/
    jQuery('.last_delete').click(function(event) {
        event.preventDefault();

    });
    /*----------*/


    $(document).on('click', 'a.first_delete', function(e) {
        e.preventDefault();
        jQuery("#order_first").attr('value', '');
    });
    $(document).on('click', 'a.next_delete', function(e) {
        e.preventDefault();
        jQuery("#order_next").attr('value', '');
    }); 
    $(document).on('click', 'a.last_delete', function(e) {
        e.preventDefault();
        jQuery("#order_last").attr('value', '');
    }); 

    $(document).on('click', 'a.delete', function(e) {
        e.preventDefault();
    });

    plus();
    minus();
    calkCount();
    sumCart();
});
