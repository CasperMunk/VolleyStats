var cancel_ajax = false;
$(document).ready(function(){
    $("button#update-button").click(function(){
        setStatus(true,"Loading games..");
        $("#result").empty().load("?mode=update&update_type="+$('input[name=updateType]:checked').val(), function() {
            updateNextGameAjax();
        });
    });

    $("#cancel").click(function(){
        cancel_ajax = true;
    });

    $("#debug-mode").click(function(){
        $("#status-field").toggle();
        $("#result").toggle();
    });
});

function updateNextGameAjax(){
    if (cancel_ajax === true){
        setStatus(false,"Cancelled!");
        $('.progress').hide();
        cancel_ajax = false;
        return;
    }
    if ($(".game.not-updated").length == 0){
        setStatus(false,"Done!");
        return;
    }
    game_id = $(".game.not-updated:first").attr("id");
    competition_id = $("#"+game_id).attr("competition_id");
    gender = $("#"+game_id).attr("gender");
    setStatus(true,"Updating game "+game_id+"..");
    $("#"+game_id).children(".result").load("?mode=loadgame&game_id="+game_id+"&competition_id="+competition_id+"&gender="+gender, function() {
        $(this).parent().removeClass("not-updated").addClass("updated");
        updateNextGameAjax();
    });
}

function setStatus(spinner,val){
    $("#status").val(val);

    var count = $(".game.updated").length;
    var total = $(".game").length;
    var pcg = Math.floor(count/total*100); 

    if(isNaN(pcg) == false && pcg > 0){
        $('.progress').show().children(".progress-bar").attr('aria-valuenow',pcg).attr('style','width:'+pcg+'%').html(pcg+'%');
    }else{
        $('.progress').hide()
    }

    if (pcg==100) $(".progress-bar").removeClass("progress-bar-animated");

    if (spinner){
        $("button#update-button").prop('disabled', true).find("span.text").html('Updating..').parent().find("span.icon").removeClass("d-none");
        $("#cancel").show();
        $("input[name=updateType]").prop("disabled",true);
    }else{
        $("button#update-button").prop('disabled', false).find("span.text").html('Update data').parent().find("span.icon").addClass("d-none");
        $("#cancel").hide();
        $("input[name=updateType]").prop("disabled",false);
    }
}