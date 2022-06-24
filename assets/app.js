/*
 * Welcome to your app's home JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layouts (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';
import './styles/front/design.css';
import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'



import { Tooltip, Toast, Popover } from 'bootstrap';
const $ = require('jquery');

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require('./js/front/global.js');

$(document).ready(function() {
   // Accordion
   var all_panels = $('.templatemo-accordion > li > ul').hide();

   $('.templatemo-accordion > li > a').click(function() {
      var target =  $(this).next();
      if(!target.hasClass('active')){
         all_panels.removeClass('active').slideUp();
         target.addClass('active').slideDown();
      }
      return false;
   });
   // End accordion

   // Product detail
   $('.product-links-wap a').click(function(){
      var this_src = $(this).children('img').attr('src');
      $('#product-detail').attr('src',this_src);
      return false;
   });
   $('#btn-minus').click(function(){
      var val = $("#var-value").html();
      val = (val=='1')?val:val-1;
      $("#var-value").html(val);
      $("#product-quanity").val(val);
      return false;
   });
   $('#btn-plus').click(function(){
      var val = $("#var-value").html();
      val++;
      $("#var-value").html(val);
      $("#product-quanity").val(val);
      return false;
   });
   $('.btn-size').click(function(){
      var this_val = $(this).html();
      $("#product-size").val(this_val);
      $(".btn-size").removeClass('btn-secondary');
      $(".btn-size").addClass('btn-success');
      $(this).removeClass('btn-success');
      $(this).addClass('btn-secondary');
      return false;
   });

});


