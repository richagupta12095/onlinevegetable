/**
 * NOT USED. SEE smshare-quicksend-vue.js
 */
$(function(){

    /*
     * onChange
     */
    var qtNumber = document.querySelector("#quicktest-number");
    qtNumber.addEventListener('input', function (e) {
        localStorage.setItem("qtNumberValue", e.target.value);
    });

    /*
     * onChange
     */
    var qtText = document.querySelector("#quicktest-text");
    qtText.addEventListener('input', function (e) {
        localStorage.setItem("qtTextValue", e.target.value);
    });

    /*
     *
     */
    $('#quicktext-clear').click(function () {
        localStorage.setItem("qtNumberValue", "");
        localStorage.setItem("qtTextValue", "");
    });

    /*
     *
     */
    $('[quicktest-launcher]').click(function () {
        $.fancybox.open({
            src  : '#hidden-content',
            type : 'inline',
            opts : {
                afterShow : function( instance, current ) {
                    console.info( '★ fancybox afterShow event' );
                    qtNumber.value = localStorage.getItem("qtNumberValue");
                    qtText.value   = localStorage.getItem("qtTextValue");

                    $successBox.html('').hide();
                    $errorBox.html('').hide();
                }
            }
        });
    });


    var $successBox = $(this).find('#success-box');
    var $errorBox = $(this).find('#error-box');
    /*
     *
     */
    $('#quicktest-form').submit(function (e) {

        e.preventDefault();

        var lady = Ladda.create(document.querySelector('#send-sms-btn'));
        lady.start();

        $successBox.html('').hide();
        $errorBox.html('').hide();

        var $form = $("#tv-module-form");
        var data = $form.serializeArray();

        data.push({name : "sms_to", value : qtNumber.value});
        data.push({name : "sms_body", value : qtText.value});
        data.push({name : "submit_btn", value : 'quick_send_sms'});


        $.ajax($form.attr('action'), {
            data   : $.param(data),
            method : 'post',

            success: function (result) {
                console.log("★ success", result);
                $successBox.html(result.payload).show();
            },
            error  : function (xhr, e1, e2) {
                console.log("✘ ", e1, e2);
                $errorBox.html(e1 + " " + e2);
            },
            complete : function(){
                lady.stop();
            }
        });
    });

});