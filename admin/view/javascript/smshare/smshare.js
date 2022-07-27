var Tv = Tv || {};
Tv.ctx = {};


/**
 * dup_fe54d
 */
Tv.ctx.renameDynamicFields = function($gwFields){
    $('tr', $gwFields).each(function(i, el){

        $('input', el).each(function(j, input){
            var $input = $(input);
            $input.attr('name', $input.attr('data-tv-name').replace('__i__', i));
        });

    });
};



/**
 * When we a click on the android/gateway select box, show the tab accordingly
 */

$("select[name=smshare_sender_profile]").on('change', function () {
    var $this = $(this);

    if ($this.val() == "profile_android") {
        $("a[href=#tab-android]").click();
    } else {
        $("a[href=#tab-gateway]").click();
    }

});



/**
 *
 */
$(function () {

    /*
     * Manage on "Add / Del" clicks on gateway dynamic fields
     */
    var $gwFields = $("#gw-fields");

    var $template = $("tr:last-child", $gwFields).clone();

    /*
     * on 'Add'
     */
    $gwFields.on('click', "*[data-add]", function(e){
        e.preventDefault();
        var $clone = $template.clone();
        $gwFields.append($clone);
        return false;

    /*
     * on 'Del'
     */
    }).on('click', "*[data-del]", function(e){
        e.preventDefault();

        //del only if at least one row
        if($("tr", $gwFields).length <= 1){
            console.log("★ This is the last row. Don't delete it. Aborting!");
            return;
        }

        $this = $(this);
        $this.parents('*[data-row]').remove();
        return false;
    });



    /*
     * Form submit
     */
    $("#tv-module-form").submit(function () {

        var $gwFields = $("#gw-fields");

        /*
         * Gateway params
         */
        Tv.ctx.renameDynamicFields($gwFields);
    });


    /*
     * Build prowessFields (accessToken and gatewayUuid) to be used on prowess GW automatic fields prefill
     */

    var $clone1 = $template.clone();
    $clone1.find("[data-tv-kv-key]").attr("value", "access_token");
    $clone1.find("[data-tv-kv-val]").attr("value", "YOUR_ACCESS_TOKEN");

    var $clone2 = $template.clone();
    $clone2.find("[data-tv-kv-key]").attr("value", "gateway");
    $clone2.find("[data-tv-kv-val]").attr("value", "YOUR_GATEWAY_UUID");

    var prowessFields = [$clone1, $clone2, $template];


    /*
     *
     */
    $("#smshare_core_cfg_gateway_provider").on("change", function () {

        if ($(this).val() === "profile_api_prowebsms") {
            $("#smshare_core_cfg_api_url").val("https://www.prowebsms.com/api/v3/texting");
            $("#method-x-www").prop('selected', true);
            $("#smshare_core_cfg_api_dest_var").val("numbers");
            $("#smshare_core_cfg_api_msg_var").val("message");

            $("#gw-fields").html("");
            $("#gw-fields").append(prowessFields);

        } else if ($(this).val() === "profile_api") {
            $("#smshare_core_cfg_api_url").val("");
            $("#smshare_api_http_method option[value=get]").prop('selected', true);
            $("#smshare_core_cfg_api_dest_var").val("");
            $("#smshare_core_cfg_api_msg_var").val("");

            $("#gw-fields").html($template);
        }
    });

});