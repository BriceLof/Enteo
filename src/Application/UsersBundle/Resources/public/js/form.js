$(function(){
    //-----------------------------------------------------------------------------------------------------------------------//
    //-------------------------- Formulaire AJOUT/MODIFICATION d'un USER ----------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//
    
    // Afficher les champs en suivant du type d'utilisateur choisi
    $('.format_label').hide();
    $('.role_user').click(function() {
        console.log($(this).val())
        switch($(this).val()){
            case "ROLE_CONSULTANT" :
                $('.calendar_uri')[this.checked ? "show" : "hide"]();
                $('.calendar_id')[this.checked ? "show" : "hide"]();
                $('.format_label')[this.checked ? "show" : "hide"]();
                break;
           /* case "ROLE_CONSULTANT" :
                break;  */     
        }
        
    });
    
    
    //-----------------------------------------------------------------------------------------------------------------------//
    //-------------------------- Formulaire Suppression USER ----------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//
    /*$('.delete_element').click(function() {
        console.log($(this).data("idUser"));
        //$('#formulaire_delete').attr('action', '')
        console.log(window.location);
    });*/
});

