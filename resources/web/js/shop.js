
window.fly2cart = function(el){
    if(el){
        var cart = $('.floating-cart');
        if(el=='img_0'){
            var imgtodrag = $("#" + el).eq(0);
        }else{
            var imgtodrag = el.parent().parent().find('img').eq(0);
        }
        if (imgtodrag) {
            var imgclone = imgtodrag.clone().offset({
                top: imgtodrag.offset().top,
                left: imgtodrag.offset().left
            }).css({'opacity': '0.5','position': 'absolute','width': '150px','z-index': '100'})
            .appendTo($('body'))
            .animate({'top': cart.offset().top + 10,'left': cart.offset().left + 10,'width': 55,'height': 55}, 1000);
            imgclone.animate({'width': 0,'height': 0, 'text-align': 'center'}, function() {$(this).detach()});
        }
    }
};

window.add2cart = function(product_id,qnt,next,el){
    $(".ajax-loader").show();
    $.ajax({
        method: "POST",
        url:  window.base_url + "/ajax-cart",
        data: { type: 'add2cart', 'product_id': product_id, 'quantity': qnt},
        success: function(data){
            $(".ajax-loader").hide();
            window.fly2cart(el);
            fbq('track', 'AddToCart', {content_ids: ['bf_' + product_id]});
            loadCart();
            $("#cart_icon, #cart_count, .floating-cart i").addClass('headShake');
            setTimeout(function(){ $("#cart_icon, #cart_count, .floating-cart i").removeClass('headShake');}, 500);
            if(!el){
                Swal.fire({position: 'center',icon: 'success',title: 'Product added to Cart',showConfirmButton: false,timer: 1500});
            }
            if(next){
                setTimeout(function(){
                    window.location.replace(window.base_url + "/" + next);
                }, 500);
            }
        },error: function(data){
            var msg = (data.responseJSON) ? data.responseJSON : 'Someting Went Wrong!';
            Swal.fire({position: 'center',icon: 'error',title: msg,showConfirmButton: false,timer: 5000});
            $(".ajax-loader").hide();
        }
    });
}

window.updateShipping = function(){
    if(!$("#bill_district").val()){ return false;}
    $(".ajax-loader").show();
    $.ajax({
        method: "POST",
        url:  window.base_url + "/ajax-cart?inv=" + window.ck_inv,
        data: { type: 'updateShipping', 'district': $("#bill_district").val(), 'pg': $("input[name=payment]:checked").val()},
        success: function(data){
             $.ajax({
                method: "GET",
                url: window.base_url + "/ajax-cart-list?inv=" + window.ck_inv,
                dataType: 'JSON',
                success: function(data){
                    if(data){
                        $("#fill_shipping").html(data.shipping.toFixed(2));
                        if(data.shipping==0){
                            $("#fill_shipping").html("Free Shipping");
                        }
                        var total = Number(data.total) + Number(data.shipping) + Number(data.gift_wrap) - Number(data.coupon_discount);
                        if(Number(data.shipping)==50){
                            $(".cod_other").hide();
                            $(".cod_dhaka").show();
                        }else{
                            $(".cod_other").show();
                            $(".cod_dhaka").hide();
                        }
                        $("#fill_total").html(total.toFixed(2));
                    }
                    $(".ajax-loader").hide();
                },error: function(data){
                    $(".ajax-loader").hide();
                }
            });
        },error: function(data){
            $(".ajax-loader").hide();
        }
    });
};

window.giftWrap = function(){
    $(".ajax-loader").show();
    $.ajax({
        method: "POST",
        url:  window.base_url + "/ajax-cart",
        data: { type: 'giftWrap', 'value': $("#gift_wrap").prop('checked')},
        success: function(data){
            window.location = window.base_url + "/cart";
        },error: function(data){
        }
    });
}

window.add2wishlist = function(product_id){
    $.ajax({
        method: "POST",
        url:  window.base_url + "/ajax-cart",
        data: { type: 'add2wishlist', 'product_id': product_id},
        success: function(data){
            fbq('track', 'AddToWishlist', {content_ids: ['bf_' + product_id]});
            Swal.fire({position: 'center',icon: 'success',title: 'Product added to Wishlist!',showConfirmButton: false,timer: 1500});
        },error: function(data){
            Swal.fire({position: 'center',icon: 'error',title: 'Please sign in first!',showConfirmButton: false,timer: 1500});
        }
    });
}

window.rem4mcart = function(product_id, reload){
    $.ajax({
        method: "POST",
        url:  window.base_url + "/ajax-cart",
        data: { type: 'rem4mcart', 'product_id': product_id},
        success: function(data){
            loadCart();
            Swal.fire({position: 'center',icon: 'success',title: 'Product removed from Cart',showConfirmButton: false,timer: 1500});
            if(reload){
                setTimeout(function(){
                    location.reload();
                }, 500);
            }
        },error: function(data){
    
        }
    });
}

window.updateCart = function(product_id,qnt, reload){
    $.ajax({
        method: "POST",
        url:  window.base_url + "/ajax-cart",
        data: { type: 'updateCart', 'product_id': product_id, 'quantity': qnt},
        success: function(data){
            loadCart();
            Swal.fire({position: 'center',icon: 'success',title: 'Cart Updated Successfully',showConfirmButton: false,timer: 1500});
            if(reload){
                setTimeout(function(){
                    location.reload();
                }, 500);
            }
        },error: function(data){
    
        }
    });
}

window.loadCart = function(){
    $.ajax({
        method: "GET",
        url: window.base_url + "/ajax-cart-list",
        dataType: 'JSON',
        success: function(data){
            if(data){
                $(".total_item, .floating-cart-count").html(data.metas.length);
                $(".total_amount").html('৳' + data.total.toFixed(2));
                var html = '';
                $.each(data.metas, function(i,v){
                    var product_name = (v.product.name) ? v.product.name : v.product.title_bn;
                    var product_id = (v.book_id) ? v.book_id : v.product_id;
                    var image = window.base_url + '/assets/images/' + v.product.images[0];
                    image = image.replace('redactor/','redactor/xs_');
                    html += '<div class="px-4 py-2 px-md-6 border-bottom">';
                    html += '    <div class="media">';
                    html += '        <a href="#" class="d-block"><img src="' + image + '" class="img-fluid" alt="Image"></a>';
                    html += '        <div class="media-body ml-4d875">';
                    html += '            <h2 class="woocommerce-loop-product__title h6 text-lh-mdtext-height-2 crop-text-2">';
                    html += '                <a href="#" class="text-dark">' + product_name + '</a>';
                    html += '            </h2>';
                    html += '            <div class="price d-flex align-items-center font-weight-medium font-size-3">';
                    html += '                <span class="woocommerce-Price-amount amount">' + v.quantity + ' x <span class="woocommerce-Price-currencySymbol">৳</span>' + v.rate.toFixed(2) + '</span>';
                    html += '            </div>';
                    html += '        </div>';
                    html += '        <div class="mt-3 ml-3">';
                    html += '            <a href="javascript:{};" onclick="rem4mcart(' + product_id + ');" class="text-dark"><i class="fas fa-times"></i></a>';
                    html += '        </div>';
                    html += '    </div>';
                    html += '</div>';
                });

                $("#cart_items").html(html);
            }
        },error: function(data){
    
        }
    });
}

window.applyCoupon = function(coupon){
    if(!coupon){
        coupon = $('#coupon_code').val();
    }
    $.ajax({
        method: "POST",
        url:  window.base_url + "/ajax-cart",
        data: { type: 'applyCoupon', 'code': coupon},
        success: function(data){
            loadCart();
            Swal.fire({position: 'center',icon: 'success',title: 'Coupon code applied successfully!',showConfirmButton: false,timer: 1500});
            setTimeout(function(){
                location.reload();
            }, 500);
        },error: function(data){
            Swal.fire({position: 'center',icon: 'error',title: 'Invalid Coupon Code!',showConfirmButton: false,timer: 3000});
        }
    });
}

window.showProduct = function(slug){
    window.location = "/book/" + slug;
}

$(document).ready(function() {
    window.ga = window[window['GoogleAnalyticsObject'] || 'ga'];
    window.base_url = $('meta[name="base-url"]').attr('content');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.HSCore.components.HSSlickCarousel.init('.js-slick-carousel');
    $.HSCore.components.HSUnfold.init($('[data-unfold-target]'));
    $.HSCore.components.HSHeader.init($('#header'));
    $.HSCore.components.HSMalihuScrollBar.init($('.js-scrollbar'));
    $.HSCore.components.HSShowAnimation.init('.js-animation-link');

    $(window).scroll(function(){
        if (window.scrollY > 300) {
            $("#top_search").addClass('animated fadeInDown fixed-top');
        }else{
            $("#top_search").removeClass('animated fadeInDown fixed-top');
        }
    });

    var zeynep = $('.zeynep').zeynep({
        onClosed: function () {
            $("body main").attr("style", "");
        },
        onOpened: function () {
            $("body main").attr("style", "pointer-events: none;");
        }
    });

    $(".zeynep-overlay").click(function () {
        zeynep.close();
    });

    $(".cat-menu").click(function () {
        if ($("html").hasClass("zeynep-opened")) {
            zeynep.close();
        } else {
            zeynep.open();
        }
    });

    $('#search_text').autoComplete({
        minLength: 2,
        resolverSettings: {
            url: window.base_url + '/ajax-search',
            requestThrottling: 250
        },
        formatResult: function (item) {
            if(window.ga){
                fbq('track', 'Search', {search_string: $('#search_text').val()});
                ga('send', 'pageview', 'search?q=' + $('#search_text').val());
            }
            return {
                value: item.id,
                text: item.title,
                html: [ 
                    '<div class="d-none d-lg-block d-md-block d-xl-block"><div class="row justify-content-between" onclick="showProduct(\''+ item.slug +'\');">' +
                    '<img width="40px" src="'+ item.image +'" alt="">' +
                    '<div style="width: calc(100% - 140px); padding-left: 10px;"><strong>' + item.title +'</strong><br>' + item.author + ' (<i>' + item.publications +'</i>)</div>' +
                    '<div style="width: 100px;"><span class="badge text-black badge-'+ item.stock_color +'">' + item.stock +'</span><br>' + item.sale +' টাকা</div>' +
                    '</div></div>'+

                    '<div class="d-lg-none"><div class="row justify-content-between" onclick="showProduct(\''+ item.slug +'\');">' +
                    '<img width="40px" src="'+ item.image +'" alt="">' +
                    '<div style="width: calc(100% - 40px); padding-left: 10px;"><strong>' + item.title +'</strong><br>' + item.author + ' <br><span class="badge text-black badge-'+ item.stock_color +'">' + item.stock +'</span><br>' + item.sale +' টাকা</div>' +
                    '</div></div>'
                ] 
            };
        },
    });

    $('.cover').Lazy({
        beforeLoad: function(element) {
            element.attr('src', window.base_url + '/assets/images/default-book-sm.png');
        },
        onError: function(element) {
            element.attr('src',  window.base_url + '/assets/images/default-book-sm.png');
        },
    });
    
    loadCart();
});