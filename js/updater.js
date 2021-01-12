$(document).ready(function(){
    $("button#update-button").click(function(e){
        e.preventDefault();
        $("#result").empty();

        var updateGameList = false;
        var updateGameResult = false;
        var updateGameStats = false;
        var updateRecords = false;

        if ($("#update_game_list").is(":checked")) updateGameList = true;
        if ($("#update_game_result").is(":checked")) updateGameResult = true;
        if ($("#update_game_stats").is(":checked")) updateGameStats = true;
        if ($("#update_records").is(":checked")) updateRecords = true;

        if (updateGameList == false && updateGameStats == false && updateRecords == false && updateGameResult == false){
            setStatus(false,"Ingen opdateringer valgt!");
            return;
        }

        if (updateGameStats || updateGameList || updateGameResult){ //Some time of game data needs to be updated. Load chosen competitions
            setStatus(true,"Henter turneringer..");

            $.each($("#competitions option:selected"), function(){
                if (updateGameList) {
                    update_mode = 'update';
                }else{
                    update_mode = 'get';
                }
                $("#result").append('<div class="comp not-updated" comp-id="'+$(this).val()+'" update_game_list="'+updateGameList+'" update_game_result="'+updateGameResult+'" update_game_stats="'+updateGameStats+'"></div>');
            });
        }

        if (updateGameStats || updateGameResult){
            updateNextCompetitionAjax();
        }

        if (!updateGameStats && !updateGameResult && updateRecords){
            updateRecordsAjax();
        }
    });

    $("#cancel").click(function(){
        $(".not-updated").removeClass("not-updated");
        $(".updating").removeClass("updating");
        setStatus(false,"Anulleret!");
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
    update_game_list = $('.comp[comp-id="'+comp_id+'"]').attr("update_game_list");
    update_game_result = $('.comp[comp-id="'+comp_id+'"]').attr("update_game_result");
    update_game_stats = $('.comp[comp-id="'+comp_id+'"]').attr("update_game_stats");

    setStatus(true,"Henter kampe for turnering ID "+comp_id+"..");

    $('.comp[comp-id="'+comp_id+'"]').removeClass("not-updated").addClass("updating").load("?update=competition&competition_id="+comp_id+"&update_game_list="+update_game_list+"&update_game_result="+update_game_result+"&update_game_stats="+update_game_stats, function() {
        $(this).removeClass("updating").addClass("updated");
        updateNextCompetitionAjax();
    });
}

function updateNextGameAjax(){
    // console.log($(".game.not-updated").length);
    if ($(".game.not-updated").length == 0){
        //updateRecordsAjax();
        // console.log('Done with matches');
        if ($("#update_records").is(":checked")) updateRecordsAjax();
        return;
    }
    game_id = $(".game.not-updated:first").attr("game-id");
    competition_id = $('.game[game-id="'+game_id+'"]').attr("competition_id");
    gender = $('.game[game-id="'+game_id+'"]').attr("gender");
    update_game_result = $('.game[game-id="'+game_id+'"]').attr("update_game_result");
    update_game_stats = $('.game[game-id="'+game_id+'"]').attr("update_game_stats");
    setStatus(true,"Opdaterer kamp ID "+game_id+"..");

    // console.log('Updating game ID '+game_id);

    $('.game[game-id="'+game_id+'"]').removeClass("not-updated").addClass("updating").children(".result").load("?update=game&game_id="+game_id+"&competition_id="+competition_id+"&gender="+gender+"&update_game_result="+update_game_result+"&update_game_stats="+update_game_stats, function() {
        $(this).parent().removeClass("updating").addClass("updated");
        updateNextGameAjax();
    });
}

function updateRecordsAjax(){
    setStatus(true,"Opdaterer rekorder..");
    $('#result').append('<div class="records not-updated"><span>Rekorder: </span><span class="result"></span></div>');
    $("#result .records").removeClass("not-updated").addClass("updating").children(".result").load("?update=records", function() {
        $(this).parent().removeClass("updating").addClass("updated");
        setStatus(false,"Færdig med rekorder!");
    });
}

function setStatus(spinner,val){
    $("#status span").prepend("<div>"+val+"</div>");

    var count = $(".game.updated").length+$(".comp.updated").length+$(".records.updated").length;
    var total = $(".game").length+$(".comp").length+$(".records").length;
    var pcg = count/total*100; 

    if(isNaN(pcg) == false && pcg > 0){
        $('.progress').show().children(".progress-bar").attr('aria-valuenow',pcg).attr('style','width:'+pcg+'%').html(pcg.toFixed(2)+'%');
    }else{
        $('.progress').hide()
    }

    if (pcg == 100) spinner = false;

    if (spinner){
        $(".progress-bar").addClass("progress-bar-animated");
        $("button#update-button").prop('disabled', true).find("span.text").html('Henter..').parent().find("span.icon").removeClass("d-none");
        $("#cancel").show();
        $("input:not(.always-on), select").prop("disabled",true);
    }else{
        $(".progress-bar").removeClass("progress-bar-animated");
        $("button#update-button").prop('disabled', false).find("span.text").html('Færdig! Tryk for at køre igen..').parent().find("span.icon").addClass("d-none");
        $("#cancel").hide();
        $("input:not(.always-on), select").prop("disabled",false);
    }
}