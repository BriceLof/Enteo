$(function(){
    
    $("#fos_user_registration_form_ville option:first-child").val("")
    $("#fos_user_registration_form_ville option:first-child").text("")
    //-----------------------------------------------------------------------------------------------------------------------//
    //-------------------------- Formulaire AJOUT/MODIFICATION d'un USER ----------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//
    
    //---------- Afficher les champs en suivant du type d'utilisateur choisi
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
    
    //----------- Remplir ville en Ajax en indiquant le département
    
    // vider le premier champs qui est un vrai objet ville (combine de ma part pour faire accepter mes villes ensuite via l'ajax)
    $("#fos_user_registration_form_ville option:first-child").val("")
    $("#fos_user_registration_form_ville option:first-child").text("")
    
    $("#fos_user_registration_form_departement").keyup(function(){
        nombreOptions = $("#fos_user_registration_form_ville option").length;
        dpt = $(this).val();
        if(dpt.length == 2)
        {   
            // Evite de lancer l'ajax quand on tape plus de 2 caractères dans le champ département 
            // car j'ai bloqué le champs à 2 caractères maxi 
            //if(nombreOptions == 1)
            //{
                console.log("je lance l'ajax");
                $.ajax({
                    type: 'get',
                    url: Routing.generate("application_plateforme_get_ville_ajax", { departement: dpt }),
                    beforeSend: function(){
                        console.log('ça charge')
                    }
                })
                .done(function(data) {
                    $("#fos_user_registration_form_ville option").remove()
                    for(var i = 0; i < data.villes.length; i++)
                    {
                        $("#fos_user_registration_form_ville").append("<option value="+data.villes[i].id+">"+data.villes[i].nom+"</option>")
                    }
                });
            //}
        }
    })
    
    
    //-----------------------------------------------------------------------------------------------------------------------//
    //-------------------------- Formulaire Suppression USER ----------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//
    /*$('.delete_element').click(function() {
        console.log($(this).data("idUser"));
        //$('#formulaire_delete').attr('action', '')
        console.log(window.location);
    });*/
});

