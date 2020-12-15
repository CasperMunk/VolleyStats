$(document).ready( function () {
    $("#gender_picker",this).on("keyup change", function () {
        var val = $("#gender_picker :checked").val();
        $("ol.records").each(function(){
            $(this).children("li").removeClass("hidden bold");
            if (val != 'all') {            
                $(this).children("li:not(."+val+")").addClass("hidden");
            }
            $(this).children("li:not(.hidden)").slice(10).addClass("hidden");
            $(this).children("li:not(.hidden)").slice(0,1).addClass("bold");
        });
    });

    $("#gender_picker").show().trigger("change");

    // $("#record_tabs",this).on("keyup change", function () {
    //     var val = $("#record_tabs :checked").val();
    //     var triggerEl = document.querySelector('#record_tabs a[href="#profile"]')
    //     bootstrap.Tab.getInstance(triggerEl).show()
    // });
});