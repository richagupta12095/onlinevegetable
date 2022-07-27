console.log("smshare quicksend vue js loaded");

$('[quicktest-launcher]').click(function () {
    $.fancybox.open({
        src  : '#hidden-content',
        type : 'inline',
        opts : {
            afterShow : function( instance, slide ) {
                console.info( '★ fancybox afterShow event' );
            },
            afterClose : function (instance) {
                console.log("★ fancybox afterClose event");
                //save value
                localStorage.setItem("sms_to", app.sms_to);
                localStorage.setItem("sms_body", app.sms_body);

                //clear value
                app.clear_success_failure_message_boxes();
            }
        }
    });
});


var app = new Vue({
    el : "#quicktest-form",
    data: {
        "success_message"        : "",
        "success_message_visible": false,
        "failure_message"        : "",
        "failure_message_visible": false,
        "sms_to"                 : localStorage.getItem("sms_to"),
        "sms_body"               : localStorage.getItem("sms_body"),
    },
    methods : {
        clear_success_failure_message_boxes: function () {
            this.success_message = "";
            this.failure_message = "";
            this.success_message_visible = false;
            this.failure_message_visible = false;
        },
        reset_the_form : function () {
            localStorage.setItem("sms_to", "");
            localStorage.setItem("sms_body", "");
        },
        submit_the_form: function () {
            var lady = Ladda.create(document.querySelector('#send-sms-btn'));
            lady.start();

            this.clear_success_failure_message_boxes();

            var $form = $("#tv-module-form");
            var data = $form.serializeArray();


            data.push({name : "sms_to", value : this.sms_to});
            data.push({name : "sms_body", value : this.sms_body});
            data.push({name : "submit_btn", value : 'quick_send_sms'});

            //dup_fe54d
            var $gwFields = $("#gw-fields");
            $('tr', $gwFields).each(function(i, el){
                $('input', el).each(function(j, input){
                    var $input = $(input);
                    data.push({name: $input.attr('data-tv-name').replace('__i__', i), value: $input.val()});
                });
            });


            $.ajax($form.attr('action'), {
                data   : $.param(data),
                method : 'post',

                success: function (result) {
                    console.log("★ success", result);
                    app.$data.success_message = result.payload;
                    app.$data.success_message_visible = true;
                },
                error  : function (xhr, e1, e2) {
                    console.log("✘ ", e1, e2);
                    app.$data.failure_message = e1 + " " + e2;
                    app.$data.failure_message_visible = true;
                },
                complete : function(){
                    lady.stop();
                }
            });
        }
    }
});