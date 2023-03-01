$(function(){
  'use strict';

  //placholder Focus
  $(['placeholder']).focus(function(){
    $(this).attr('data-text',$(this).attr('placeholder'));
    $(this).attr('placeholder','');
  }).blur(function(){
    $(this).attr('placeholder',$(this).attr('data-text'));
  });

  //
  $('.confirm').click(function(){
    return confirm('Are You Sure?');
  });
  //chick is required
$('input').each(function(){
  if($(this).attr('required') === "required"){
    $(this).after("<span class='asterisk'>*</span>");
  }

});
  //category view
  $('.cat h3').click(function(){
    $(this).next('.full-view').fadeToggle(200);

  });
});

