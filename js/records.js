$(document).ready( function () {
    $("#gender_picker",this).on("change", function () {
        var val = $("#gender_picker :checked").val();
        $("ol.records").each(function(){
            $(this).children("li").removeClass("hidden bold").css("list-style-type","");;
            if (val != 'all') {            
                $(this).children("li:not(."+val+")").addClass("hidden");
            }
            $(this).children("li").not(".hidden").slice(10).addClass("hidden");
            $(this).children("li").not(".hidden").slice(0,1).addClass("bold");
            // $(this).children("li:not(.hidden)").slice(10).addClass("hidden");
            // $(this).children("li:not(.hidden)").slice(0,1).addClass("bold");

            $(this).children("li").not(".hidden").each(function(){
                // console.log('test');
                if ($(this).data("value") == $(this).prevAll("li").not(".hidden").first().data("value")){
                    $(this).css("list-style-type","none");
                }
            });

            // $(this).children("li:not(.hidden)").slice(0,1).addClass("bold").css("list-style-type","inherit");
        });
    });

    $("#gender_picker").show().trigger("change");
});