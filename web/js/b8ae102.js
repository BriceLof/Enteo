<<<<<<< HEAD
$(function(){
    
    //-----------------------------------------------------------------------------------------------------------------------//
    //--------------------------  HOME : formulaire ajout d'une news ----------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//

    $(".btn_formulaire_add_news").click(function(){
        beneficiaire_id = $(this).attr('id')
        
        // Supprime tout les formulaires d'ouvert
        $(".modal-dialog form").remove();
        
        formDuBeneficiaire = "#block_formulaire_ajout_new_"+beneficiaire_id 
        
        // Je déplace le formulaire unique crée par defaut et je le lie à un bénéficiaire 
        // (car au début le formulaire n'est pas dans la boucle)
        $(formDuBeneficiaire + " .modal-content").append($("#block_formulaire_add_news").html())
        
        // Valeur qui servira à identifier le bénéficiaire lors de l'appel au controlleur pour ajouter une news
        $(formDuBeneficiaire + " #hidden_beneficiaire_id").val(beneficiaire_id) 
        
        //---------- START : Par défaut lorsqu'on ouvre le formulaire, le statut présenté est le statut supérieur à celui de la dernière news du bénéficiaire       
        var statutId = $(formDuBeneficiaire + " .statutIDCurrent").val()
        
        // ID du statut supérieur au statut courant du bénéficiaire (
        // si statutId = 6 => ( c a d RV2 réalisé, je fais un +3 sur l'id pour sauter le statut recevabilité jusqu'a facturation)
        if(statutId == 6)
            var nextStatutId = Number(statutId) + Number(4)
        else
            var nextStatutId = Number(statutId) + Number(1) 
        
        $(formDuBeneficiaire + " .statut").val(nextStatutId) // changement de la valeur du statut 
        ajaxFormNews(nextStatutId, true)
        //---------- END 
            
        //---------- START : Si on change le statut 
        $(formDuBeneficiaire + " .statut").change(function(){
            var statutId = $("option:selected").val()
            $(".detailStatut").attr("disabled", "disabled") 
            ajaxFormNews(statutId, true)
        }); 
        //---------- END
    });
    
    //-----------------------------------------------------------------------------------------------------------------------//
    //--------------------------  Fiche bénéficiaire  -----------------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//
    if( $("#tabNews").length != 0)
    {   
        // Formulaire ajout d'une news
        
        $(".link_formulaire_add_news").click(function(){
            $(".detailStatut").attr("disabled", "disabled") 
            var statutId = $("#statutIDCurrentBlockStatut").val()
            $("#statut #newsForm .statut").val(statutId) // changement de la valeur du statut 
            ajaxFormNews(statutId, true)
        })
 
        $("#newsForm .statut").change(function(){
            var statutId = $(this).children("option:selected").val()
            $(".detailStatut").attr("disabled", "disabled") 
            ajaxFormNews(statutId,true)
        }); 
        
        // Formulaire ajout d'une suivi administratif
  
        //---------- START : Par défaut lorsqu'on ouvre le formulaire, chargement des details statuts     
        var statutIdSuiviAd = $(".statutIDCurrentSuiviAd").val()
        $("#suiviAdministratifNewForm .statutSuiviAd").val(statutIdSuiviAd)
        ajaxFormNews(statutIdSuiviAd, false) 
        
        // Modification d'un suivi administratif
        $(".updateSuivi").click(function(){
            var boucle = $(this).attr('data-boucle')
            $("#myModalSuiviAdministratifModify"+boucle+" .detailStatutSuiviAd").attr("disabled", "disabled") 
            var statutIdSuiviAd = $(".statutIDCurrentSuiviAdUpdate"+boucle).val()
            var detailStatutIdSuiviAd = $(".detailStatutIDCurrentSuiviAdUpdate"+boucle).val()
            ajaxFormNews(statutIdSuiviAd, false, detailStatutIdSuiviAd)   
        })
        //---------- END 
        
        $("#suiviAdministratifNewForm .statutSuiviAd").change(function(){
            var statutId = $(this).children("option:selected").val()
            $("#suiviAdministratifNewForm .detailStatutSuiviAd").attr("disabled", "disabled") 
            ajaxFormNews(statutId)
        }); 
        
        $("#suiviAdministratifEditForm .statutSuiviAd").change(function(){
            var statutId = $(this).children("option:selected").val()
            $("#suiviAdministratifEditForm .detailStatutSuiviAd").attr("disabled", "disabled") 
            ajaxFormNews(statutId)
        }); 
    }
    
    //-----------------------------------------------------------------------------------------------------------------------//
    //--------------------------  Page Utilisateur  -------------------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//
    
    url = window.location.href
    console.log($(".typeUtilisateur a").text())
    if(url.indexOf("user/type/admin") != -1){
        $(".typeUtilisateur a").removeClass("active")
        $(".typeUtilisateur .itemAdmin").addClass("active") 
    }
    else if(url.indexOf("user/type/commercial") != -1){
        $(".typeUtilisateur a").removeClass("active")
        $(".typeUtilisateur .itemCommercial").addClass("active") 
    }
    else if(url.indexOf("user/type/gestion") != -1){
        $(".typeUtilisateur a").removeClass("active")
        $(".typeUtilisateur .itemGestion").addClass("active") 
    }
    else if(url.indexOf("user/type") != -1){
        $(".typeUtilisateur a").removeClass("active")
        $(".typeUtilisateur .itemConsultant").addClass("active") 
    }
        
    
});

// Récupération de la liste de détails du statut choisi 
// (param1 => ID statut, param2 => Booleen si c'est pour le formulaire de news ou non, param3 => ID detail Statut pour le formulaire de suivi administraif)
function ajaxFormNews(statut, news, detailStatutSuiviAd)
{
    $.ajax({
        type: 'get',
        url: Routing.generate("application_plateforme_detail_statut", { idStatut: statut }),
        beforeSend: function(){
            console.log('ça charge')
            $(".block_info_chargement").show();
        }
    }).done(function(data) {
        if(news == true)
        {
            $(".detailStatut").removeAttr("disabled")
            $(".detailStatut").html("");
        } 
        else{
            $("#suiviAdministratifNewForm .detailStatutSuiviAd").removeAttr("disabled")
            $("#suiviAdministratifEditForm .detailStatutSuiviAd").removeAttr("disabled")
            $("#suiviAdministratifNewForm .detailStatutSuiviAd").html("");
            $("#suiviAdministratifEditForm .detailStatutSuiviAd").html("");
        }
        
        $(".block_info_chargement").hide();
        
        for(var i = 0; i < data.details.length; i++)
        {
            console.log(data.details[i].detail);
            // Fiche bénéficiaire
            if( $("#tabNews").length != 0 ){
                // Formulaire news
                if(news == true)
                {
                    $("#newsForm .detailStatut").append("<option value="+data.details[i].id+">"+data.details[i].detail+"</option>")  
                }
                // Formulaire suivi administratif
                else{
                    $("#suiviAdministratifNewForm .detailStatutSuiviAd").append("<option value="+data.details[i].id+">"+data.details[i].detail+"</option>")
                    
                    // En modification : selectionne par défaut le detail statut qui était présent à la base
                    if(data.details[i].id == detailStatutSuiviAd )
                        $("#suiviAdministratifEditForm .detailStatutSuiviAd").append("<option selected value="+data.details[i].id+">"+data.details[i].detail+"</option>")
                    else
                        $("#suiviAdministratifEditForm .detailStatutSuiviAd").append("<option value="+data.details[i].id+">"+data.details[i].detail+"</option>") 
                } 
            }
            // Home    
            else{
                $(formDuBeneficiaire + " .detailStatut").append("<option value="+data.details[i].id+">"+data.details[i].detail+"</option>") 
            }     
        }
    });
}
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
        $('.block_form_ville').showshow();
    }else{
        $('.block_form_calendar_uri').hide();
        $('.block_form_calendar_id').hide();
        $('.block_form_format_presence').hide();
        $('.block_form_departement').hide();
        $('.departementInputForAjax').val("0");
        $('.block_form_ville').hide();
    }

    $('.role_user').click(function() {
        switch($(this).val()){
            case "ROLE_CONSULTANT" :
                $('.block_form_calendar_uri')[this.checked ? "show" : "hide"]();
                $('.block_form_calendar_id')[this.checked ? "show" : "hide"]();
                $('.block_form_format_presence')[this.checked ? "show" : "hide"]();
                $('.block_form_departement')[this.checked ? "show" : "hide"]();
                $('.block_form_ville')[this.checked ? "show" : "hide"]();
                
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

=======
;$(function(){$('.btn_formulaire_add_news').click(function(){beneficiaire_id=$(this).attr('id');$('.modal-dialog form').remove();formDuBeneficiaire='#block_formulaire_ajout_new_'+beneficiaire_id;$(formDuBeneficiaire+' .modal-content').append($('#block_formulaire_add_news').html());$(formDuBeneficiaire+' #hidden_beneficiaire_id').val(beneficiaire_id);var t=$(formDuBeneficiaire+' .statutIDCurrent').val();if(t==6)var i=Number(t)+Number(4);else var i=Number(t)+Number(1);$(formDuBeneficiaire+' .statut').val(i);ajaxFormNews(i,!0);$(formDuBeneficiaire+' .statut').change(function(){var t=$('option:selected').val();$('.detailStatut').attr('disabled','disabled');ajaxFormNews(t,!0)})});if($('#tabNews').length!=0){$('.link_formulaire_add_news').click(function(){$('.detailStatut').attr('disabled','disabled');var t=$('#statutIDCurrentBlockStatut').val();$('#statut #newsForm .statut').val(t);ajaxFormNews(t,!0)});$('#newsForm .statut').change(function(){var t=$(this).children('option:selected').val();$('.detailStatut').attr('disabled','disabled');ajaxFormNews(t,!0)});var t=$('.statutIDCurrentSuiviAd').val();$('#suiviAdministratifNewForm .statutSuiviAd').val(t);ajaxFormNews(t,!1);$('.updateSuivi').click(function(){var t=$(this).attr('data-boucle');$('#myModalSuiviAdministratifModify'+t+' .detailStatutSuiviAd').attr('disabled','disabled');var e=$('.statutIDCurrentSuiviAdUpdate'+t).val(),i=$('.detailStatutIDCurrentSuiviAdUpdate'+t).val();ajaxFormNews(e,!1,i)});$('#suiviAdministratifNewForm .statutSuiviAd').change(function(){var t=$(this).children('option:selected').val();$('#suiviAdministratifNewForm .detailStatutSuiviAd').attr('disabled','disabled');ajaxFormNews(t)});$('#suiviAdministratifEditForm .statutSuiviAd').change(function(){var t=$(this).children('option:selected').val();$('#suiviAdministratifEditForm .detailStatutSuiviAd').attr('disabled','disabled');ajaxFormNews(t)})};url=window.location.href;console.log($('.typeUtilisateur a').text());if(url.indexOf('user/type/admin')!=-1){$('.typeUtilisateur a').removeClass('active');$('.typeUtilisateur .itemAdmin').addClass('active')}
else if(url.indexOf('user/type/commercial')!=-1){$('.typeUtilisateur a').removeClass('active');$('.typeUtilisateur .itemCommercial').addClass('active')}
else if(url.indexOf('user/type/gestion')!=-1){$('.typeUtilisateur a').removeClass('active');$('.typeUtilisateur .itemGestion').addClass('active')}
else if(url.indexOf('user/type')!=-1){$('.typeUtilisateur a').removeClass('active');$('.typeUtilisateur .itemConsultant').addClass('active')}});function ajaxFormNews(t,i,e){$.ajax({type:'get',url:Routing.generate('application_plateforme_detail_statut',{idStatut:t}),beforeSend:function(){console.log('ça charge');$('.block_info_chargement').show()}}).done(function(a){if(i==!0){$('.detailStatut').removeAttr('disabled');$('.detailStatut').html('')}
else{$('#suiviAdministratifNewForm .detailStatutSuiviAd').removeAttr('disabled');$('#suiviAdministratifEditForm .detailStatutSuiviAd').removeAttr('disabled');$('#suiviAdministratifNewForm .detailStatutSuiviAd').html('');$('#suiviAdministratifEditForm .detailStatutSuiviAd').html('')};$('.block_info_chargement').hide();for(var t=0;t<a.details.length;t++){console.log(a.details[t].detail);if($('#tabNews').length!=0){if(i==!0){$('#newsForm .detailStatut').append('<option value='+a.details[t].id+'>'+a.details[t].detail+'</option>')}
else{$('#suiviAdministratifNewForm .detailStatutSuiviAd').append('<option value='+a.details[t].id+'>'+a.details[t].detail+'</option>');if(a.details[t].id==e)$('#suiviAdministratifEditForm .detailStatutSuiviAd').append('<option selected value='+a.details[t].id+'>'+a.details[t].detail+'</option>');else $('#suiviAdministratifEditForm .detailStatutSuiviAd').append('<option value='+a.details[t].id+'>'+a.details[t].detail+'</option>')}}
else{$(formDuBeneficiaire+' .detailStatut').append('<option value='+a.details[t].id+'>'+a.details[t].detail+'</option>')}}})};
;$(window).load(function(){$("#overlayB").fadeOut("1500");console.log("Infos disponibles ")});$(function(){console.log("Recupération de vos infos");typeUtilisateur=$("#application_usersbundle_users_typeUserHidden").val();if(typeUtilisateur!="Administrateur")$(".block_form_role_user").hide();if($(".role_user:checked").val()=="ROLE_CONSULTANT"){$(".block_form_calendar_uri").show();$(".block_form_calendar_id").show();$(".block_form_block_form_format_presence").show();$(".block_form_departement").show();$(".block_form_ville").showshow()}
else{$(".block_form_calendar_uri").hide();$(".block_form_calendar_id").hide();$(".block_form_format_presence").hide();$(".block_form_departement").hide();$(".departementInputForAjax").val("0");$(".block_form_ville").hide()};$(".role_user").click(function(){switch($(this).val()){case"ROLE_CONSULTANT":$(".block_form_calendar_uri")[this.checked?"show":"hide"]();$(".block_form_calendar_id")[this.checked?"show":"hide"]();$(".block_form_format_presence")[this.checked?"show":"hide"]();$(".block_form_departement")[this.checked?"show":"hide"]();$(".block_form_ville")[this.checked?"show":"hide"]();break}});$(".villeAjax option:first-child").val("");$(".villeAjax option:first-child").text("");$(".villeAjax").attr("disabled","disabled");$(".departementInputForAjax").keyup(function(){$(".villeAjax").attr("disabled","disabled");nombreOptions=$(".villeAjax  option").length;dpt=$(this).val();if(dpt.length==2){console.log("je lance l'ajax");$.ajax({type:"get",url:Routing.generate("application_plateforme_get_ville_ajax",{departement:dpt}),beforeSend:function(){console.log("ça charge");$(".block_info_chargement").show()}}).done(function(l){$(".block_info_chargement").hide();$(".villeAjax option").remove();$(".villeAjax").removeAttr("disabled");for(var e=0;e<l.villes.length;e++){$(".villeAjax").append("<option value="+l.villes[e].id+">"+l.villes[e].nom+"</option>")}})}});if($("#application_usersbundle_users_codePostalHidden").length==1){cp=$("#application_usersbundle_users_codePostalHidden").val();idVille=$("#application_usersbundle_users_idVilleHidden").val();if(cp.length==5){console.log("je lance l'ajax");$.ajax({type:"get",url:Routing.generate("application_plateforme_get_ville_ajax",{departement:cp.substr(0,2)}),beforeSend:function(){console.log("ça charge");$(".block_info_chargement").show()}}).done(function(l){$(".block_info_chargement").hide();$(".villeAjax option").remove();$(".villeAjax").removeAttr("disabled");for(var e=0;e<l.villes.length;e++){if(idVille==l.villes[e].id)$(".villeAjax").append("<option selected data-cp="+l.villes[e].cp+" value="+l.villes[e].id+">"+l.villes[e].nom+"</option>");else $(".villeAjax").append("<option data-cp="+l.villes[e].cp+" value="+l.villes[e].id+">"+l.villes[e].nom+"</option>")}})}}});
>>>>>>> d64a7e4560529e67a4672c69a28b11abb4364676
