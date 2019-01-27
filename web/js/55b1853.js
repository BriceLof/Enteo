$(function () {

    $(".block_form_add_file h4").click(function(){
        $(".block_form_add_file .hide_show_form_add_file").toggle();
    })

    // Hover sur le fichier, afficher les icones voir, modifier et supprimer
    $(".file").hover(function(){
        $(this).children('aside').toggle();
    })

    $( "#sortable" ).sortable({
        stop: function( event, ui ) {
            // console.log("Ma position initial : " +ui.item.data('ordre'))
            // position = ui.item.index() + 1
            // console.log("Nouvelle position : "+position)
$(".btn_submit_ordre_rubrique").show()
            $("#sortable li").each(function(index){
                // pour le faire commencer à 1 et non 0
                i = index + 1
                $(this).attr("data-ordre", i)
                $(this).children('input').val(i)

            })
        }

    });
});