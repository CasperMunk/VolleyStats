$(document).ready(function(){
    $("button#update-button").click(function(e){
        e.preventDefault();
        $("#result").empty();

        var updateGameList = false;
        var updateGameStats = false;
        var updateRecords = false;

        if ($("#update_game_list").is(":checked")) updateGameList = true;
        if ($("#update_game_stats").is(":checked")) updateGameStats = true;
        if ($("#update_records").is(":checked")) updateRecords = true;

        if (updateGameList == false && updateGameStats == false && updateRecords == false){
            setStatus(false,"Ingen opdateringer valgt!");
            return;
        }

        if (updateGameStats || updateGameList){
            setStatus(true,"Henter turneringer..");

            $.each($("#competitions option:selected"), function(){
                if (updateGameList) {
                    update_mode = 'get';
                }else{
                    update_mode = 'update';
                }
                $("#result").append('<div class="comp not-updated" comp-id="'+$(this).val()+'" update-mode="'+update_mode+'"></div>');
            });
        }

        if (updateGameStats){
            updateNextCompetitionAjax();
        }

        if (!updateGameStats && updateRecords){
            updateRecordsAjax();
        }
    });

    $("#cancel").click(function(){
        setStatus(false,"Annullerer..");
        $(".not-updated").removeClass("not-updated");
        $(".updating").removeClass("updating");
    });

    $("#debug-mode").click(function(){
        $("#status").toggle();
        $("#result").toggle();
    });
});

function updateNextCompetitionAjax(){
    if ($(".comp.not-updated").length == 0){
        updateNextGameAjax();
        return;
    }

    comp_id = $(".comp.not-updated:first").attr("comp-id");
    update_mode = $(".comp.not-updated:first").attr("update-mode");

    setStatus(true,"Henter kampe for turnering ID "+comp_id+"..");

    $('.comp[comp-id="'+comp_id+'"]').removeClass("not-updated").addClass("updating").load("?mode="+update_mode+"_competition&competition_id="+comp_id, function() {
        $(this).removeClass("updating").addClass("updated");
        updateNextCompetitionAjax();
    });
}

function updateNextGameAjax(){
    // console.log($(".game.not-updated").length);
    if ($(".game.not-updated").length == 0){
        updateRecordsAjax();
        // console.log('Done with matches');
        return;
    }
    game_id = $(".game.not-updated:first").attr("game-id");
    competition_id = $(".game.not-updated:first").attr("competition_id");
    gender = $(".game.not-updated:first").attr("gender");
    setStatus(true,"Opdaterer kamp ID "+game_id+"..");

    // console.log('Updating game ID '+game_id);

    $('.game[game-id="'+game_id+'"]').removeClass("not-updated").addClass("updating").children(".result").load("?mode=update_game&game_id="+game_id+"&competition_id="+competition_id+"&gender="+gender, function() {
        $(this).parent().removeClass("updating").addClass("updated");
        updateNextGameAjax();
    });
}

function updateRecordsAjax(){
    setStatus(true,"Opdaterer rekorder..");
    $('#result').append('<div class="records"><span>Rekorder: </span><span class="result"></span></div>').find(".result").load("?mode=update_records", function() {
        setStatus(false,"Færdig!");
    });
}

function setStatus(spinner,val){
    $("#status span").prepend("<div>"+val+"</div>");

    var count = $(".game.updated").length+$(".comp.updated").length+$(".records").length;
    var total = $(".game").length+$(".comp").length+$(".records").length;
    var pcg = count/total*100; 

    if(isNaN(pcg) == false && pcg > 0){
        $('.progress').show().children(".progress-bar").attr('aria-valuenow',pcg).attr('style','width:'+pcg+'%').html(pcg.toFixed(2)+'%');
    }else{
        $('.progress').hide()
    }

    if (pcg==100) $(".progress-bar").removeClass("progress-bar-animated");

    if (spinner){
        $("button#update-button").prop('disabled', true).find("span.text").html('Henter..').parent().find("span.icon").removeClass("d-none");
        $("#cancel").show();
        $("input:not(.always-on), select").prop("disabled",true);
    }else{
        $("button#update-button").prop('disabled', false).find("span.text").html('Færdig! Tryk for at køre igen..').parent().find("span.icon").addClass("d-none");
        $("#cancel").hide();
        $("input:not(.always-on), select").prop("disabled",false);
    }
}