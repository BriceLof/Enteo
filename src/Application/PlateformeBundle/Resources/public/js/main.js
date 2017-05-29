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
        /*var statutId = $(formDuBeneficiaire + " .statutIDCurrent").val()
        
        // ID du statut supérieur au statut courant du bénéficiaire (
        // si statutId = 6 => ( c a d RV2 réalisé, je fais un +3 sur l'id pour sauter le statut recevabilité jusqu'a facturation)
        if(statutId == 6)
            var nextStatutId = Number(statutId) + Number(4)
        else
            var nextStatutId = Number(statutId) + Number(1) 
        
        $(formDuBeneficiaire + " .statut").val(nextStatutId) // changement de la valeur du statut 
        ajaxFormNews(nextStatutId, true)*/
        //---------- END 
            
        //---------- START : Si on change le statut 
        $(formDuBeneficiaire + " .statut").change(function(){
            var statutId = $(this).children("option:selected").val()
            $(".detailStatut").attr("disabled", "disabled") 
			console.log(statutId)
            ajaxFormNews(statutId, true)
        });
        //---------- END

        $("#form_add_news").on("submit",function(e){
            //ne pas envoyer le formulaire et ne pas affiche le # sur l'url
            e.preventDefault();

            $.ajax({
                url: Routing.generate('application_plateforme_homepage'), // le nom du fichier indiqué dans le formulaire
                type: $(this).attr('method'), // la méthode indiquée dans le formulaire (get ou post)
                data: $(this).serialize(),
                cache: true,
                dataType: 'json',
                beforeSend: function () {
                    //à rajouter un chargement
                },
                success: function (data) {
                    template = data;

                    //rechargement du html dans le block table du bénéficiaire
                    $('#b'+beneficiaire_id).html(template);

                    //ferme le modal
                    $('#block_formulaire_ajout_new_'+beneficiaire_id).modal('toggle');
                }
            });
        })
    });
    
    $(".btn_formulaire_add_nouvelle").click(function(){
        beneficiaire_id = $(this).attr('id')
        // Supprime tout les formulaires d'ouvert
        $(".modal-dialog form").remove();
        
        formDuBeneficiaire = "#block_formulaire_ajout_nouvelle_"+beneficiaire_id 
        
        // Je déplace le formulaire unique crée par defaut et je le lie à un bénéficiaire 
        // (car au début le formulaire n'est pas dans la boucle)
        $(formDuBeneficiaire + " .modal-content").append($("#block_formulaire_add_nouvelle").html())
        
        // Valeur qui servira à identifier le bénéficiaire lors de l'appel au controlleur pour ajouter une news
        $(formDuBeneficiaire + " #hidden_beneficiaire_id").val(beneficiaire_id) 

        $("#form_add_nouvelle").on("submit",function(e){
            //ne pas envoyer le formulaire et ne pas affiche le # sur l'url
            e.preventDefault();

            $.ajax({
                url: Routing.generate('application_plateforme_homepage'), // le nom du fichier indiqué dans le formulaire
                type: $(this).attr('method'), // la méthode indiquée dans le formulaire (get ou post)
                data: $(this).serialize(),
                cache: true,
                dataType: 'json',
                beforeSend: function () {
                    //à rajouter un chargement
                },
                success: function (data) {
                    template = data;
                    console.log(data)
                    //rechargement du html dans le block table du bénéficiaire
                    $('#b'+beneficiaire_id).html(template);

                    //ferme le modal
                    $('#block_formulaire_ajout_nouvelle_'+beneficiaire_id).modal('toggle');
                }
            });
        })
    });
    
    $(".voirPlusNouvelle").click(function(){
        beneficiaire_id = $(this).attr('id')

        $.ajax({
            type: 'get',
            url: Routing.generate("application_show_all_ajax_nouvelle", { idBeneficiaire: beneficiaire_id }),
            beforeSend: function(){
                console.log('ça charge')
            }
        }).done(function(data) {
            console.log(data.nouvelles[0]);
            var html = ""
            for(i=0; i<data.nouvelles.length; i++)
            {
                html += "<p>&bull; "+data.nouvelles[i].from+' le <i>'+data.nouvelles[i].date+'</i> : '+data.nouvelles[i].message+"</p>"
            }
            $("#listeNouvelle_"+beneficiaire_id).html(html)
        });

    });
    
    //-----------------------------------------------------------------------------------------------------------------------//
    //--------------------------  Fiche bénéficiaire  -----------------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//
    
    if( $("#tabNews").length != 0)
    {   
        // Formulaire ajout d'une news
        
        /*$(".link_formulaire_add_news").click(function(){
            $(".detailStatut").attr("disabled", "disabled") 
            var statutId = $("#statutIDCurrentBlockStatut").val()
            $("#statut #newsForm .statut").val(statutId) // changement de la valeur du statut 
            ajaxFormNews(statutId, true)
        })*/
 
        $("#newsForm .statut").change(function(){
            var statutId = $(this).children("option:selected").val()
            $(".detailStatut").attr("disabled", "disabled") 
            ajaxFormNews(statutId,true)
        }); 
        
        // Formulaire ajout d'une suivi administratif
  
        //---------- START : Par défaut lorsqu'on ouvre le formulaire, chargement des details statuts     
        /*var statutIdSuiviAd = $(".statutIDCurrentSuiviAd").val()
        $("#suiviAdministratifNewForm .statutSuiviAd").val(statutIdSuiviAd)
        ajaxFormNews(statutIdSuiviAd, false) 
        
        // Modification d'un suivi administratif
        $(".updateSuivi").click(function(){
            var boucle = $(this).attr('data-boucle')
            $("#myModalSuiviAdministratifModify"+boucle+" .detailStatutSuiviAd").attr("disabled", "disabled") 
            var statutIdSuiviAd = $(".statutIDCurrentSuiviAdUpdate"+boucle).val()
            var detailStatutIdSuiviAd = $(".detailStatutIDCurrentSuiviAdUpdate"+boucle).val()
            ajaxFormNews(statutIdSuiviAd, false, detailStatutIdSuiviAd)   
        })*/
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
    
    //--------- Partie ajout d'une news dans block Suivi Administratif 
    $("#suivi_administratif_info").keyup(function(){
        inputVal = $(this).val();
        if(inputVal.length > 0){
            $("#suivi_administratif_statut").removeAttr('required');
        }
    });
    
    //--------- Partie ajout d'une news dans block News en bas de page et sur la Home 
    
    
    
    
    
    
    
    
  
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
    console.log(statut)
    $.ajax({
        type: 'get',
        url: Routing.generate("application_plateforme_detail_statut", { idStatut: statut }),
        beforeSend: function(){
            console.log('ça charge')
            $(".block_info_chargement").show();
        }
    }).done(function(data) {
        console.log(data)
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
                    if(statut == 3 || statut == 5 || statut == 7){}
					else{ 
						if(i == 0 ) $("#newsForm .detailStatut").append("<option value=''>Choisissez</option>")
					}
                    $("#newsForm .detailStatut").append("<option value="+data.details[i].id+">"+data.details[i].detail+"</option>")  
                }
                // Formulaire suivi administratif
                else{
                    if(statut == 3 || statut == 5 || statut == 7){}
					else{
						if(i == 0 ){
							$("#suiviAdministratifNewForm .detailStatutSuiviAd").append("<option value=''>Choisissez</option>")
							$("#suiviAdministratifEditForm .detailStatutSuiviAd").append("<option value=''>Choisissez</option>")
						}
					}
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
				// si statut est egal à RV1 à faire ou RV2, ne pas mettre le choisissez car la valeur du champ selectionner sera vide en detail statut 
				if(statut == 3 || statut == 5 || statut == 7){}
				else{ 
					if(i == 0 ) $(formDuBeneficiaire + " .detailStatut").append("<option value=''>Choisissez</option>")
				}					
                
                $(formDuBeneficiaire + " .detailStatut").append("<option value="+data.details[i].id+">"+data.details[i].detail+"</option>") 
            }     
        }
    });
}