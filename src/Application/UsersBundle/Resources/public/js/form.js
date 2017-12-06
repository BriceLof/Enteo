$(window).load(function() {
    $("#overlayB").fadeOut("1500");
    console.log("Infos disponibles ");
});
$(function(){
    console.log("Recupération de vos infos");
    //-----------------------------------------------------------------------------------------------------------------------//
    //-------------------------- Formulaire AJOUT/MODIFICATION d'un USER ----------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//
    
    // Si le type utilisateur connecté n'est pas admin, il n'a pas la visibilité sur le choix du type utilisateur
    typeUtilisateur = $("#application_usersbundle_users_typeUserHidden").val();
    if(typeUtilisateur != "Administrateur") $(".block_form_role_user").hide();
        
    //---------- Afficher les champs en suivant du type d'utilisateur choisi
    if($('.role_user:checked').val() == "ROLE_CONSULTANT")
    {
        $('.block_form_calendar_uri').show();
        $('.block_form_calendar_id').show();
        $('.block_form_block_form_format_presence').show();
        $('.block_form_departement').show();
        $('.block_form_ville').show();
        $('.block_form_adresse').show();
    }else{
        $('.block_form_calendar_uri').hide();
        $('.block_form_calendar_id').hide();
        $('.block_form_format_presence').hide();
        $('.block_form_departement').hide();
        $('.departementInputForAjax').val("0");
        $('.block_form_ville').hide();
        $('.block_form_adresse').hide();
    }

    $('.role_user').click(function() {
        switch($(this).val()){
            case "ROLE_CONSULTANT" :
                $('.block_form_calendar_uri')[this.checked ? "show" : "hide"]();
                $('.block_form_calendar_id')[this.checked ? "show" : "hide"]();
                $('.block_form_format_presence')[this.checked ? "show" : "hide"]();
                $('.block_form_departement')[this.checked ? "show" : "hide"]();
                $('.block_form_ville')[this.checked ? "show" : "hide"]();
                $('.block_form_adresse')[this.checked ? "show" : "hide"]();
				$('.departementInputForAjax').val("");
                break;
           /* case "ROLE_CONSULTANT" :
                break;  */     
        }
        
    });
    
    //----------- Remplir ville en Ajax en indiquant le département
    
    // vider le premier champs qui est un vrai objet ville  (Uniquement sur la page de création d'un utilisateur)
    // (combine de ma part pour faire accepter mes villes ensuite via l'ajax)

    $(".villeAjax option:first-child").val("")
    $(".villeAjax option:first-child").text("") 
    $(".villeAjax").attr("disabled", "disabled") 

    
    $(".departementInputForAjax").keyup(function(){
        $(".villeAjax").attr("disabled", "disabled") 
        nombreOptions = $(".villeAjax  option").length;
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
                        $(".block_info_chargement").show();
                    }
                })
                .done(function(data) {
                    $(".block_info_chargement").hide();
                    $(".villeAjax option").remove()
                    $(".villeAjax").removeAttr("disabled") 
                    for(var i = 0; i < data.villes.length; i++)
                    {
                        $(".villeAjax").append("<option value="+data.villes[i].id+">"+data.villes[i].nom+"</option>")
                    }
                });
            //}
        }
    })
    
    
    // Pour la modification d'un utilisateur, on doit cette fois ci récupérer le code postal de sa ville 
    if($("#application_usersbundle_users_codePostalHidden").length == 1)
    {
        cp      = $("#application_usersbundle_users_codePostalHidden").val();
        idVille = $("#application_usersbundle_users_idVilleHidden").val();
        if(cp.length == 5)
        {   
            console.log("je lance l'ajax");
            $.ajax({
                type: 'get',
                url: Routing.generate("application_plateforme_get_ville_ajax", { departement: cp.substr(0,2) }),
                beforeSend: function(){
                    console.log('ça charge')
                    $(".block_info_chargement").show();
                }
            })
            .done(function(data) {
                $(".block_info_chargement").hide();
                $(".villeAjax option").remove()
                $(".villeAjax").removeAttr("disabled") 
                for(var i = 0; i < data.villes.length; i++)
                {
                    if(idVille == data.villes[i].id)
                        $(".villeAjax").append("<option selected data-cp="+data.villes[i].cp+" value="+data.villes[i].id+">"+data.villes[i].nom+"</option>")
                    else
                        $(".villeAjax").append("<option data-cp="+data.villes[i].cp+" value="+data.villes[i].id+">"+data.villes[i].nom+"</option>")
                }
            });
        }
    }
    //-----------------------------------------------------------------------------------------------------------------------//
    //-------------------------- Formulaire Suppression USER ----------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//
    /*$('.delete_element').click(function() {
        console.log($(this).data("idUser"));
        //$('#formulaire_delete').attr('action', '')
        console.log(window.location);
    });*/
});

$(function () {
    jQuery.extend(jQuery.validator.messages, {
        required: "Ceci ne peut être vide",
        accept: "Fichier accecpé : PDF",
        minlength: jQuery.validator.format("votre message doit être supérieur à 10 caractères"),
    });
    $('#form_mission_document').validate({
        rules: {
            "form[document]":{
                "required": true,
                "accept": "application/pdf",
            }
        },
        errorElement: 'div'
    });

    $('#form_mission_decline').validate({
        rules: {
            "mailing[message]": {
                "required" : true,
                "minlength": 10,
            }
        },
        errorElement: 'div'
    })
});

