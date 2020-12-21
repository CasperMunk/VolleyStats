var cancelAjax = false;
var maxThreads = 5;
var usedThreads = 1;
$(document).ready(function(){
    $("button#update-button").click(function(e){
        e.preventDefault();
        $("#result").empty();

        setStatus(true,"Henter turneringer..");

        var update_mode = 'get';
        if ($("#update_game_list").is(":checked")) var update_mode = 'update';

        $.each($("#competitions option:selected"), function(){
            $("#result").append('<div class="comp not-updated" comp-id="'+$(this).val()+'" update-mode="'+update_mode+'"></div>');
        });

        for (usedThreads = 1; usedThreads <= maxThreads; usedThreads++) {
            console.log('Starting thread '+usedThreads); 
            updateNextCompetitionAjax();
        }
    });

    $("#cancel").click(function(){
        $(".not-updated").removeClass("not-updated");
        $(".updating").removeClass("updating");
        setStatus(false,"Annullerer..");
    });

    $("#debug-mode").click(function(){
        $("#status").toggle();
        $("#result").toggle();
    });
});

function updateNextCompetitionAjax(){
    if ($(".comp.not-updated").length == 0){
        // No more comps to update
        console.log('No more comps to update')

        if ($(".comp.updating").length > 0){
            //Some comps are still updating
            //Wait for 2 seconds before updating games
            console.log('Run update competitions again in 2 secs!')
            setTimeout(updateNextCompetitionAjax,2000); 
            return;
        }else{
            //All comps are updated, ready to update games
            console.log('Start updating games')
            updateNextGameAjax();
            return;
        }
    }

    comp_id = $(".comp.not-updated:first").attr("comp-id");
    update_mode = $(".comp.not-updated:first").attr("update-mode");

    setStatus(true,"Henter kampe for turnering ID "+comp_id+"..");
    console.log('Updating comp ID '+comp_id);

    $('.comp[comp-id="'+comp_id+'"]').removeClass("not-updated").addClass("updating").load("?mode="+update_mode+"_competition&competition_id="+comp_id, function() {
        $(this).removeClass("updating").addClass("updated");
        updateNextCompetitionAjax();
    });
}

function updateNextGameAjax(){
    console.log($(".game.not-updated").length);
    if ($(".game.not-updated").length == 0){
        setStatus(false,"Færdig!");
        console.log('Done');
        return;
    }
    game_id = $(".game.not-updated:first").attr("game-id");
    competition_id = $(".game.not-updated:first").attr("competition_id");
    gender = $(".game.not-updated:first").attr("gender");
    setStatus(true,"Opdaterer kamp ID "+game_id+"..");

    console.log('Updating game ID '+game_id);

    $('.game[game-id="'+game_id+'"]').removeClass("not-updated").addClass("updating").children(".result").load("?mode=update_game&game_id="+game_id+"&competition_id="+competition_id+"&gender="+gender, function() {
        $(this).parent().removeClass("updating").addClass("updated");
        updateNextGameAjax();
    });
}

function setStatus(spinner,val){
    $("#status span").prepend("<div>"+val+"</div>");

    var count = $(".game.updated").length;
    var total = $(".game").length;
    var pcg = count/total*100; 

    if(isNaN(pcg) == false && pcg > 0){
        $('.progress').show().children(".progress-bar").attr('aria-valuenow',pcg).attr('style','width:'+pcg+'%').html(pcg.toFixed(1)+'%');
    }else{
        $('.progress').hide()
    }

    if (pcg==100) $(".progress-bar").removeClass("progress-bar-animated");

    if (spinner){
        $("button#update-button").prop('disabled', true).find("span.text").html('Henter..').parent().find("span.icon").removeClass("d-none");
        $("#cancel").show();
        $("input:not(.always-on), select").prop("disabled",true);
    }else{
        $("button#update-button").prop('disabled', false).find("span.text").html('Opdater').parent().find("span.icon").addClass("d-none");
        $("#cancel").hide();
        $("input:not(.always-on), select").prop("disabled",false);
    }
}