$(window).load(function() {
    $("#overlayB").fadeOut("1500");
    console.log("Infos dispo ");
});
$(function () {
    console.log("Infos loading");
    $("#overlayB").show()
    $(".block_info_stat .btn_detail").click(function(){
        console.log($(this).find(".detail"))
        $(this).parent().find(".detail").toggle()
    })

    // Si l'utilisateur refresh la page (F5, CTRL+R or the browser's button)
    window.onbeforeunload = function() {
        $("#overlayB").show()
    }
    $(".form_date_interval_stats").submit(function(event){
        var timer = 0;
        var interval = setInterval(function() {
            timer++;
            $("#overlayB").show()
            console.log(timer);
        }, 1000);
    })

});