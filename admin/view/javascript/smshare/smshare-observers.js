/**
 *
 */
$("#tv-module-form").submit(function () {

    /*
     * Order observers
     */
    $("div[data-tv-observer-container]").each(function (index, observerContainer) {

        var $observerContainer = $(observerContainer);

        //Do not submit the template
        if($observerContainer.css('display') == 'none') $observerContainer.remove();

        $('[data-tv-rename]', $observerContainer).each(function(ignore, ele){
           var $this = $(ele);
           var name = $this.attr('data-tv-rename').replace("__i__", index);
           if($this.is("[data-tv-lg]")){
               name = name.replace("__lg__", $this.attr('data-tv-lg'));
           }
           $this.attr("name", name);
        });

    });
});


/**
 *
 */
$(function(){

    var $replicator = $("[data-observer-replicator]");

    $("#os-observers-area").on('click', "button[data-tv-add-observer]", function () {
        var $this = $(this);
        var $clone = $replicator.clone();
        if($this.attr("data-tv-add-observer") === "add-after"){
            $this.parents("[data-tv-observer-container]").after($clone);
        }else{
            $("#observers-wrapper").append($clone);
        }
        $clone.fadeIn(200);
    });



    /**
     *
     */
    $("#os-observers-area").on('click', "button[data-tv-del-observer]", function () {

        if($("[data-tv-observer-container]").length === 1){
            console.log("last container. Do not remove. Aborting!");
            return;
        }

        $(this).parents("[data-tv-observer-container]").fadeOut(200, function () {
            $(this).remove();
        });

    });


});