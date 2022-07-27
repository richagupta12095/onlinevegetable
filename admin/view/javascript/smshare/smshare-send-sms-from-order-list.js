// $(document).on('click', '#button-send-sms', function(){
$(document).on('click', '[data-tv-button-send-sms]', function(){

    console.log('Send SMS button clicked');
    
    if($("input[name='selected[]']:checked").length === 0){
        alert('No order selected');
        return;
    }
    

    swal({
        input : 'textarea',
        showCancelButton : true,
        showLoaderOnConfirm: true,
        preConfirm: function (text) {
            return new Promise(function (resolve, reject) {
                if(text){
                    sendSMS(text, resolve);
                }else{
                    swal({
                        title: "You didn't enter the message",
                        text: "Can't send empty SMS",
                        type: 'error',
                    });
                }
            })
        }
    })

});



var sendSMS = function(text, resolve){
    
    // $btn = $('#button-send-sms');
    $btn = $('[data-tv-button-send-sms]');
    //var l = Ladda.create( document.querySelector( '#button-send-sms' ) );
    var l = Ladda.create( $btn.get(0) );

    // Start loading
    l.start();
    
    var laddaFinishCount = 0;

    $("input[name='selected[]']:checked").each(function(i, ele){
        var $ele = $(ele);
        laddaFinishCount++;
            
        var myOrderId = $ele.val();

        var url = $btn.attr('data-tv-url');
        url = url.replace("__ORDER_ID__", myOrderId);
        
        $.ajax({
            url      : url,
            type     : 'post',
            dataType : 'json',
            data     : 'message=' + encodeURIComponent(text), 
            
            complete : function(){
                laddaFinishCount--;
                if(laddaFinishCount == 0)l.stop();
                resolve();
            },
            
            success  : function(json) {

                if (json['error']) {
                    $(ele).after("<div class='text-error'>" + json['error'] + "</div>");
                }

                if (json['success']) {
                    $(ele).after("<div class='text-success'>" + json['success'] + "</div>");
                }
                
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });//ajax
        
        
        
    });//end foreach
}