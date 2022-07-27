$(document).ready(function() {
  $('.cimyheart').on('click', function(){
    var $this = $(this);
    var $ciblogpost = $this.parent().parent('.ciblogpost');

    $.ajax({
      url: 'index.php?route=extension/ciblog/ciblogpost/heartgiven',
      type: 'post',
      data: 'blogid=' + $this.parent('.hearting').attr('data-blogid'),
      dataType: 'json',
      beforeSend: function() {
        $this.fadeOut('slow');
      },
      complete: function() {
        $this.fadeIn('slow', function(){$(this).css('display','');});
      },
      success: function(json) {
        $ciblogpost.find('.alert, .text-danger, .text-success').remove();

        if (json['error']) {
          $ciblogpost.find('.ciblog-view').after('<div class="text-danger"><i class="fa fa-check-circle"></i> ' + json['error'] + '</div>');
        }
        if (json['success']) {
         $ciblogpost.find('.ciblog-view').after('<div class="text-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

         $this.parent('.hearting').find('span').html(json['heart']);

         $('.cimyheart').each(function() {

          if($(this).parent('.hearting').attr('data-blogid') == $this.parent('.hearting').attr('data-blogid') && $(this) !== $this) {
            $(this).removeClass('fa-heart-o').removeClass('cimyheart').addClass('fa-heart').off('click');
            $(this).parent('.hearting').find('span').html(json['heart']);
          }
         });
        }
        $this.removeClass('fa-heart-o').removeClass('cimyheart').addClass('fa-heart').off('click');
        setTimeout(function(){$ciblogpost.find('.alert, .text-danger, .text-success').remove();}, 2000);
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });

  })
});