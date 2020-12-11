$(document).ready( function () {
    $("input[type='radio'][name='gender_picker']").change(function() {
        var val = $("input[type='radio'][name='gender_picker']:checked").val();
        $("ol.records").each(function(){
            $(this).children("li").removeClass("hidden bold");
            if (val != 'all') {            
                $(this).children("li:not(."+val+")").addClass("hidden");
            }
            $(this).children("li:not(.hidden)").slice(10).addClass("hidden");
            $(this).children("li:not(.hidden)").slice(0,1).addClass("bold");
        });
    });

    $("input[type='radio'][name='gender_picker']").trigger("change");
});