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