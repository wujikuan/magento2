define([
    'jquery','owlcarousel'
], function ($) {
    'use strict';
    $(document).ready(function(){
    	$(".product-owl-carousel").owlCarousel({
    		nav: true,
 			navText: ["<span class='fa fa-chevron-left'></span>", "<span class='fa fa-chevron-right'></span>"],
			dots: false,
			items: 4,
			mouseDrag: false,
            responsive:{
                0:{
                    items:2
                },
                480:{
                	items:2
                },
                767:{
                    items:4
                },
                992:{
                    items:4
                },
                1200:{
                    items:4
                }
            }
		});
		
    })
    
})