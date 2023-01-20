/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/style.css';

global.$ = global.jQuery = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything

import { jarallax } from "jarallax";
require('bootstrap');
require('headroom.js');
require('moment');
require('gsap');
require('daterangepicker');

$(document).ready(function() {

    jarallax(document.querySelectorAll('.jarallax'), {
        speed: 0.2,
    });


    let filterAmenities = $('.filter .form-check');

    filterAmenities.each(function(index) {
        $(this).children().first().addClass('btn-check');
        $(this).children().next().addClass('filter_item text-white');
        let id = $(this).children().first().get(0).id;
        $(this).children().next().attr('for', id);

        $(this).children().first().change(function() {
            $("[name='amenity_form']").submit();
        });

    });

    $(".qtyplus").on("click", function (e) {
        e.preventDefault();
        const fieldName = 'search_form_'+$(this).attr("name");
        e = parseInt($("#"+fieldName).val());
        isNaN(e) ? $("#"+fieldName).val(0) : $("#"+fieldName).val(e + 1)
    })

    $(".qtyminus").on("click", function (e) {
        e.preventDefault();

        const fieldName = 'search_form_'+$(this).attr("name");
        e = parseInt($("#"+fieldName).val());
        !isNaN(e) && 0 < e ? $("#"+fieldName).val(e - 1) : $("#"+fieldName).val(0)
    });


});
