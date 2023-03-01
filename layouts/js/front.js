$(function(){
  'use strict';
  /*confirm to delete*/
$('.confirm').click(function(){
  return confirm('Are You Sure?');
});
//category view
$('.cat h3').click(function(){
  $(this).next('.full-view').fadeToggle(200);

});
});
