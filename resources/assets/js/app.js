
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('jquery');
require('./bootstrap');
require('materialize-css');
require('moment');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */




$( document ).ready(function(){

  Vue.component('slip-records', require('./components/slipRecords.vue'));
  Vue.component('slip-records-form', require('./components/slipRecordsForm.vue'));
  const app = new Vue({
      el: '#prt-vue'
  });
  //M.AutoInit();
  $('.sidenav').sidenav();

  //M.updateTextFields();
    $(".button-collapse").sidenav();
  $(".dropdown-button").dropdown();
  $('.collapsible').collapsible();
  // $('select').material_select();
  // $('.datepicker').pickadate({
  //    selectMonths: true, // Creates a dropdown to control month
  //    selectYears: 2, // Creates a dropdown of 2 years to control year,
  //    today: 'Today',
  //    clear: 'Clear',
  //    close: 'Ok',
  //    closeOnSelect: false, // Close upon selecting a date,
  //    container: undefined, // ex. 'body' will append picker to body
  //  });
 // $('.datepicker').datepicker();
  $('select').formSelect();





   //check if the request if from a checkbox
     $(".houseStatus").on("change", function() {
       //alert("status:"+$(this).is(':checked') + " id:"+ $(this).attr('data-id') +"map_id:" + $(this).attr('data-map') + "token" + $('input[name=_token]').val());
       $.ajax({
         url: '/changeHouseStatus',
         type: "post",
         data: {'status':$(this).is(':checked'), 'id':$(this).attr('data-id'), 'map_id': $(this).attr('data-map'), 'ass_id': $(this).attr('data-assignment'), '_token': $('input[name=_token]').val() },
         success: function(data){
          // alert("saved successfully!");
         }
       });
     });

     //check if the request is from the submit button to share the slip
     //$('form').submit(function(event) {
       $('.share').change( function(e){
      e.preventDefault();
        $.ajax({
         url: '/share',
         type: "post",
         data: {'shared':$(this).is(':checked'), 'slip_id': $(this).attr('id'),'assignment_id': $(this).parent().attr('data-assignmentID'), '_token': $('input[name=_token]').val()},
         success: function(data){
           //alert("saved successfully!");
         }
       });
     });



     $('.share:checkbox').bind('change', function(e) {
         var $id = "#shared-" + $(this).attr('id') +"";

           if ($(this).is(':checked')) {
             ($($id)).fadeIn('fast');

           }
           else {
             ($($id)).fadeOut('fast');

           }
         });

         $(function(){
           $('.share:checkbox').each(function() {
             var $id = "#shared-" + $(this).attr('id') +"";

             if ( $(this).is(':checked') ){
             ($($id)).fadeIn('fast');
           } else {
               ($($id)).fadeOut('fast');
             }
           });
         });

       var isMobile = {
         Android: function() {
             return navigator.userAgent.match(/Android/i);
         },
         BlackBerry: function() {
             return navigator.userAgent.match(/BlackBerry/i);
         },
         iOS: function() {
             return navigator.userAgent.match(/iPhone|iPad|iPod/i);
         },
         Opera: function() {
             return navigator.userAgent.match(/Opera Mini/i);
         },
         Windows: function() {
             return navigator.userAgent.match(/IEMobile/i);
         },
         any: function() {
             return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
         }
     };
      $(".whatsapp").on("click", function() {
             if( isMobile.any() ) {
                 var text = $(this).attr("data-text");
                 var message = encodeURIComponent(text);
                 var whatsapp_url = "whatsapp://send?text=" + message;
                 window.location.href = whatsapp_url;
             } else {
                 alert("Please share this article in mobile device");
             }
      });


});
