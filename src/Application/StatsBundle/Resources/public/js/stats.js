$(function () {

    $(".block_info_stat .btn_detail").click(function(){
        console.log($(this).find(".detail"))
        $(this).parent().find(".detail").toggle()
    })



});