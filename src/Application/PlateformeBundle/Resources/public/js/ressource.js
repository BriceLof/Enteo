$(function () {

    $(".block_form_add_file h4").click(function(){
        $(".block_form_add_file .hide_show_form_add_file").toggle();
    })

    // Hover sur le fichier, afficher les icones voir, modifier et supprimer
    $(".file").hover(function(){
        $(this).children('aside').toggle();
    })

});