<div class="footer">
   
</div>
<script src="/layouts/js/backend.js"></script>
<script src="/layouts/js/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<!-- <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script> -->
<script src="https://code.jquery.com/jquery-1.12.1.min.js" integrity="sha256-I1nTg78tSrZev3kjvfdM5A5Ak/blglGzlaZANLPDl3I=" crossorigin="anonymous"></script>
<script src="/layouts/js/jquery.selectBoxIt.min.js"></script>
<script>
$('.confirm').click(function(){
    return confirm('Are You Sure?');
  });
</script>
<script>
$('.cat-link').hover(function(){
    $(this).find('.show-delete').fadeIn(400);
  },function(){
    $(this).find('.show-delete').fadeOut(400);
  });
</script>
<script>
  $('.option span').click(function(){

    $(this).addClass('active').siblings('span').removeClass('active');
    if($(this).data('view') === 'Full'){
      $('.cat .full-view').fadeIn(200);
    }else{
      $('.cat .full-view').fadeOut(200);
    }
  });
</script>
<!-- <script>
  $('select').selectBoxIt();
</script> -->
<script>
  $('.toggle-info').click(function(){
    $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
    if($(this).hasClass('selected')){
      $(this).html('<i class ="fa fa-minus fa-lg"></i>');
    }else{
      $(this).html('<i class="fa fa-plus fa-lg"></i>');
    }
  });
</script>
</body>
</html>