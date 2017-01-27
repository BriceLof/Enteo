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

        // ID du statut supérieur au statut courant du bénéficiaire 
        var nextStatutId = Number(statutId) + Number(1)
        $(formDuBeneficiaire + " .statut").val(nextStatutId) // changement de la valeur du statut 
        ajaxFormNews(nextStatutId)
        //---------- END 
            
        //---------- START : Si on change le statut 
        $(formDuBeneficiaire + " .statut").change(function(){
            var statutId = $("option:selected").val()
            $(".detailStatut").attr("disabled", "disabled") 
            ajaxFormNews(statutId)
        }); 
        //---------- END
    });
});

// Récupération de la liste de détails du statut choisi
function ajaxFormNews(statut)
{
    $.ajax({
        type: 'get',
        url: Routing.generate("application_plateforme_detail_statut", { idStatut: statut }),
        beforeSend: function(){
            console.log('ça charge')
            $(".block_info_chargement").show();
        }
    }).done(function(data) {
        $(".detailStatut").removeAttr("disabled")
        $(".block_info_chargement").hide();
        $(".detailStatut").html("");
        for(var i = 0; i < data.details.length; i++)
        {
            console.log(data.details[i].detail);
            $(formDuBeneficiaire + " .detailStatut").append("<option value="+data.details[i].id+">"+data.details[i].detail+"</option>")
        }
    });
}