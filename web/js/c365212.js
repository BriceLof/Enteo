/**
 * function qui permet de gerer le modal MAJ dans la home
 * @param el
 */
function addNews(el) {
    beneficiaire_id = $(el).attr('id');
    console.log(beneficiaire_id);
    // Supprime tout les formulaires d'ouvert
    $(".modal-dialog form").remove();

    formDuBeneficiaire = "#block_formulaire_ajout_new_" + beneficiaire_id;

    // Je déplace le formulaire unique crée par defaut et je le lie à un bénéficiaire
    // (car au début le formulaire n'est pas dans la boucle)
    $(formDuBeneficiaire + " .modal-content").append($("#block_formulaire_add_news").html());

    // Valeur qui servira à identifier le bénéficiaire lors de l'appel au controlleur pour ajouter une news
    $(formDuBeneficiaire + " #hidden_beneficiaire_id").val(beneficiaire_id);

    console.log(Routing.generate('application_new_news', { id: beneficiaire_id }))

    $(formDuBeneficiaire + ' form').attr('action', Routing.generate('application_new_news', { id: beneficiaire_id }))

    //---------- START : Si on change le statut
    $(formDuBeneficiaire + " .statut").change(function () {
        var statutId = $(this).children("option:selected").val();
        $(".detailStatut").attr("disabled", "disabled");
        console.log(statutId);
        ajaxFormNews(statutId, true)
    });
    //---------- END

    $("#form_add_news").on("submit", function (e) {
        //ne pas envoyer le formulaire et ne pas affiche le # sur l'url
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'), // le nom du fichier indiqué dans le formulaire
            type: $(this).attr('method'), // la méthode indiquée dans le formulaire (get ou post)
            data: $(this).serialize(),
            cache: true,
            dataType: 'json',
            beforeSend: function () {
                //à rajouter un chargement
            },
            success: function (data) {
                template = data;

                //ferme le modal
                $('#block_formulaire_ajout_new_' + beneficiaire_id).modal('toggle');

                //rechargement du html dans le block table du bénéficiaire
                $('#info_beneficiaire' + beneficiaire_id).html(template);
            }
        });
    })
}

//-----------------------------------------------------------------------------------------------------------------------//
//--------------------------  HOME : formulaire ajout d'une news ----------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------------//

/**
 * function qui permet de gere le modal News dans la home
 * @param el
 */
function addNouvelle(el) {
    beneficiaire_id = $(el).attr('id');
    // Supprime tout les formulaires d'ouvert
    $(".modal-dialog form").remove();
    console.log("on y est");

    formDuBeneficiaire = "#block_formulaire_ajout_nouvelle_" + beneficiaire_id;

    // Je déplace le formulaire unique crée par defaut et je le lie à un bénéficiaire
    // (car au début le formulaire n'est pas dans la boucle)
    $(formDuBeneficiaire + " .modal-content").append($("#block_formulaire_add_nouvelle").html());

    // Valeur qui servira à identifier le bénéficiaire lors de l'appel au controlleur pour ajouter une news
    $(formDuBeneficiaire + " #hidden_beneficiaire_id").val(beneficiaire_id);

    $(formDuBeneficiaire + ' form').attr('action', Routing.generate('application_new_nouvelle', { id: beneficiaire_id }))

    $("#form_add_nouvelle").on("submit", function (e) {
        //ne pas envoyer le formulaire et ne pas affiche le # sur l'url
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'), // le nom du fichier indiqué dans le formulaire
            type: $(this).attr('method'), // la méthode indiquée dans le formulaire (get ou post)
            data: $(this).serialize(),
            cache: true,
            dataType: 'json',
            beforeSend: function () {
                //à rajouter un chargement
            },
            success: function (data) {
                template = data;
                console.log(data);

                //ferme le modal
                $('#block_formulaire_ajout_nouvelle_' + beneficiaire_id).modal('toggle');

                //rechargement du html dans le block table du bénéficiaire
                $('#info_beneficiaire' + beneficiaire_id).html(template);
            }
        });
    })
}

/**
 * function qui permet de gere le modal "voir +" dans la home
 * @param el
 */
function voirNouvelle(el) {
    beneficiaire_id = $(el).attr('id');

    $.ajax({
        type: 'get',
        url: Routing.generate("application_show_all_ajax_nouvelle", {idBeneficiaire: beneficiaire_id}),
        beforeSend: function () {
            console.log('ça charge')
        }
    }).done(function (data) {
        console.log(data.nouvelles[0]);
        var html = "";
        for (i = 0; i < data.nouvelles.length; i++) {
            html += "<p>&bull; " + data.nouvelles[i].from + ' le <i>' + data.nouvelles[i].date + '</i> : <b>' + data.nouvelles[i].titre + "</b></p><p style='margin-left:20px'>" + data.nouvelles[i].message + "</p>"
        }
        $("#listeNouvelle_" + beneficiaire_id).html(html)
    });

}

$(function () {
    //-----------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------  Commun  -----------------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//

    // -------- Gestion des datepicker --------
    $( ".datepickerBrice" ).datepicker({
        dateFormat : 'dd/mm/yy',
        monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Decembre" ],
        dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
        dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ]
    });
    // Tranforme date version EN vers FR pour l'affichage
    $( ".datepickerBrice" ).each(function(){
        if($(this).val().length > 0){
            dateEn = $(this).val()
            dateSplit = dateEn.split("-")
            dateFr = dateSplit[2]+"/"+dateSplit[1]+"/"+dateSplit[0]
            $(this).val(dateFr)
        }
    })
    // Tranforme date version FR vers EN pour la bdd
    $("form").submit(function () {
        $( ".datepickerBrice" ).each(function(){
            if($(this).val().length > 0){
                dateFr = $(this).val()
                dateSplit = dateFr.split("/")
                dateEn = dateSplit[2]+"-"+dateSplit[1]+"-"+dateSplit[0]
                $(this).val(dateEn)
            }
        })
    })
    //-------- FIN Gestion des datepicker --------

    //-----------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------  FIN Commun  -----------------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//


    //-----------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------  Home  -----------------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//

    $("#ajaxForm .statutSuiviAd").change(function () {
        var statutId = $(this).children("option:selected").val();
        $("#ajaxForm .detailStatutSuiviAd").attr("disabled", "disabled")
        ajaxFormNews(statutId)
    });

    $("#linkMoreFilter").click(function () {
        if ($(".moreFilterSearch").is(':hidden')) {
            $(".moreFilterSearch").show()
            $("#linkMoreFilter").text("- de Filtres")
        }
        else {
            $(".moreFilterSearch").hide()
            $("#linkMoreFilter").text("+ de Filtres")
        }
        return false;
    });
    //-----------------------------------------------------------------------------------------------------------------------//
    //--------------------------  Fiche bénéficiaire  -----------------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//

    if ($("#tabNews").length != 0) {
        $("#newsForm .statut").change(function () {
            var statutId = $(this).children("option:selected").val()
            $(".detailStatut").attr("disabled", "disabled")
            if (statutId == 12){
                $("#newsForm #message_news").show()
            }else {
                $("#newsForm #message_news").hide()
            }
            ajaxFormNews(statutId, true)
        });

        $("#suiviAdministratifNewForm .statutSuiviAd").change(function () {
            var statutId = $(this).children("option:selected").val();
            $("#suiviAdministratifNewForm .detailStatutSuiviAd").attr("disabled", "disabled")
            ajaxFormNews(statutId)
        });

        $("#suiviAdministratifEditForm .statutSuiviAd").change(function () {
            var statutId = $(this).children("option:selected").val();
            $("#suiviAdministratifEditForm .detailStatutSuiviAd").attr("disabled", "disabled")
            ajaxFormNews(statutId)
        });
    }

    $(".modal").draggable({
        handle: ".modal-header"
    });

    //--------- Partie ajout d'une news dans block Suivi Administratif 
    $("#suivi_administratif_info").keyup(function () {
        inputVal = $(this).val();
        if (inputVal.length > 0) {
            $("#suivi_administratif_statut").removeAttr('required');
        }
    });

    //--------- Partie facture
    $(".statut_paiement_facture").mouseover(function() {
        $(this).css("cursor", "pointer");
        var id = $(this).attr('id')
        var block_to_display = id.replace("statut", "block_historique");
        $(".block_historique_paiement_facture").hide()
        $("#"+block_to_display).show()
    })
    .mouseout(function() {
        $(".block_historique_paiement_facture").hide()
    });
    
    //-----------------------------------------------------------------------------------------------------------------------//
    //--------------------------  Page Utilisateur  -------------------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//

    url = window.location.href;
    console.log($(".typeUtilisateur a").text());
    if (url.indexOf("user/type/admin") != -1) {
        $(".typeUtilisateur a").removeClass("active");
        $(".typeUtilisateur .itemAdmin").addClass("active")
    }
    else if (url.indexOf("user/type/commercial") != -1) {
        $(".typeUtilisateur a").removeClass("active");
        $(".typeUtilisateur .itemCommercial").addClass("active")
    }
    else if (url.indexOf("user/type/gestion") != -1) {
        $(".typeUtilisateur a").removeClass("active");
        $(".typeUtilisateur .itemGestion").addClass("active")
    }
    else if (url.indexOf("user/type") != -1) {
        $(".typeUtilisateur a").removeClass("active");
        $(".typeUtilisateur .itemConsultant").addClass("active")
    }


});

// Récupération de la liste de détails du statut choisi 
// (param1 => ID statut, param2 => Booleen si c'est pour le formulaire de news ou non, param3 => ID detail Statut pour le formulaire de suivi administraif)
function ajaxFormNews(statut, news, detailStatutSuiviAd) {
    console.log(statut)
    $.ajax({
        type: 'get',
        url: Routing.generate("application_plateforme_detail_statut", {idStatut: statut}),
        beforeSend: function () {
            console.log('ça charge')
            $(".block_info_chargement").show();
        }
    }).done(function (data) {
        console.log(data)
        if (news == true) {
            $(".detailStatut").removeAttr("disabled")
            $(".detailStatut").html("");
        }
        else {
            $("#suiviAdministratifNewForm .detailStatutSuiviAd").removeAttr("disabled")
            $("#suiviAdministratifEditForm .detailStatutSuiviAd").removeAttr("disabled")
            $("#suiviAdministratifNewForm .detailStatutSuiviAd").html("");
            $("#suiviAdministratifEditForm .detailStatutSuiviAd").html("");
            $("#ajaxForm .detailStatutSuiviAd").removeAttr("disabled")
            $("#ajaxForm .detailStatutSuiviAd").html("");
        }

        $(".block_info_chargement").hide();

        for (var i = 0; i < data.details.length; i++) {
            console.log(data.details[i].detail);
            // Fiche bénéficiaire
            if ($("#tabNews").length != 0) {
                // Formulaire news
                if (news == true) {
                    if (statut == 3 || statut == 5 || statut == 7) {
                    }
                    else {
                        if (i == 0) $("#newsForm .detailStatut").append("<option value=''>Choisissez</option>")
                    }
                    $("#newsForm .detailStatut").append("<option value=" + data.details[i].id + ">" + data.details[i].detail + "</option>")
                }
                // Formulaire suivi administratif
                else {
                    if (statut == 3 || statut == 5 || statut == 7) {
                    }
                    else {
                        if (i == 0) {
                            $("#suiviAdministratifNewForm .detailStatutSuiviAd").append("<option value=''>Choisissez</option>")
                            $("#suiviAdministratifEditForm .detailStatutSuiviAd").append("<option value=''>Choisissez</option>")

                        }
                    }

                    $("#suiviAdministratifNewForm .detailStatutSuiviAd").append("<option value=" + data.details[i].id + ">" + data.details[i].detail + "</option>")


                    // En modification : selectionne par défaut le detail statut qui était présent à la base
                    if (data.details[i].id == detailStatutSuiviAd) {
                        $("#suiviAdministratifEditForm .detailStatutSuiviAd").append("<option selected value=" + data.details[i].id + ">" + data.details[i].detail + "</option>")
                    }
                    else {
                        $("#suiviAdministratifEditForm .detailStatutSuiviAd").append("<option value=" + data.details[i].id + ">" + data.details[i].detail + "</option>")
                        $("#ajaxForm .detailStatutSuiviAd").append("<option value=" + data.details[i].id + ">" + data.details[i].detail + "</option>")
                    }
                }
            }
            // Home    
            else {
                // si statut est egal à RV1 à faire ou RV2, ne pas mettre le choisissez car la valeur du champ selectionner sera vide en detail statut
                if (statut == 3 || statut == 5 || statut == 7) {
                }
                else {
                    if (i == 0) {
                        if (typeof formDuBeneficiaire !== 'undefined') $(formDuBeneficiaire + " .detailStatut").append("<option value=''>Choisissez</option>")
                        $("#ajaxForm .detailStatutSuiviAd").append("<option value=''>Choisissez</option>")
                    }
                }
                if (typeof formDuBeneficiaire !== 'undefined') $(formDuBeneficiaire + " .detailStatut").append("<option value=" + data.details[i].id + ">" + data.details[i].detail + "</option>")
                $("#ajaxForm .detailStatutSuiviAd").append("<option value=" + data.details[i].id + ">" + data.details[i].detail + "</option>")
            }
        }
    });
}

/**
 * trier par ordre alphabetique ou recence
 * cette fonction attribue la valeur 0, 1 ou 2 à l'input hidder tri alpha du formulaire de recherche
 *
 *
 */
function tri(el) {
    id = $(el).attr('id');
    value = $(el).val();
    $('#recherche_beneficiaire_tri').val(value);
    $('#ajaxForm').submit();
}

/**
 * ajax pour la recherche dans la home + la pagination
 */
(function () {
    /**
     * Ajax formulaire de recherche dans la home et crée un block pour acceuil le nouveaux bloc de 50 resultats
     */
    function pagination(page, form) {
        $.ajax({
            url: Routing.generate('application_ajax_search_home'), // le nom du fichier indiqué dans le formulaire
            type: $(form).attr('method'), // la méthode indiquée dans le formulaire (get ou post)
            data: $(form).serialize(),
            cache: true,
            dataType: 'json',
            beforeSend: function () {
                var resultat = document.getElementById('section_home_page_' + page);
                resultat.innerHTML = "";
                resultat.innerHTML += '<div class="loading"></div>';
                $('#pagination').empty();
            },
            success: function (response) {
                html = '<div id="section_home_page_' + (page + 1) + '"></div>';
                template = response;
                $('#section_home_page_' + page).html(template);
                $('#section_home_page_' + page).parent().append(html);
                $('#recherche_beneficiaire_page').val(parseInt($('#recherche_beneficiaire_page').val()) + 1);
                if (parseInt($('#indication_page_' + page).attr('data-page')) < parseInt($('#indication_page_' + page).attr('data-total'))) {
                    $('#section_home_page_1').attr('data-page', '1');
                }
            }
        });
    }

    /**
     * a chaque submit on remet a 0 la page pour que ça fausse pas le resultant dans l'ajax
     */
    $("#ajaxForm").submit(function (e) {
        $('#recherche_beneficiaire_page').val(0);
        $('#section-tab-home-benef').empty().append('<div id="section_home_page_1" data-page="0"></div>');
        e.preventDefault();
        history.pushState(null, null, Routing.generate('application_plateforme_homepage'));
        pagination(parseInt($('#recherche_beneficiaire_page').val()) + 1, this);
    });

    /**
     * cette fonction appel l'ajax quand l'utilisateur arrive en bas de page et recharge les 50 autres nouveaux resultats
     *
     * l'attribut data-page sert a verifier si l'ajax est terminer ou pas pour eviter que si jamais l'utilisateur rescroll pour aller en bas de page
     * et ça renvoie une deuxieme fois l'ajax
     */
    $(window).scroll(function () {
        if (document.getElementById('resultat_recherche_ajax')) {
            if ($(window).height() + $(window).scrollTop() == $(document).height() && $('#section_home_page_1').attr('data-page') == '1') {
                $('#section_home_page_1').attr('data-page', '0');
                pagination(parseInt($('#recherche_beneficiaire_page').val()) + 1, $("#ajaxForm"));
            }
        }
        //console.log($(window).height() + $(window).scrollTop())
        //console.log($(document).height())
    });

    $("#excel").click(function () {
        document.search_beneficiaire.action = Routing.generate('application_csv_getListBeneficiaire');
        document.search_beneficiaire.submit();
        document.search_beneficiaire.action = Routing.generate('application_search_beneficiaire', {'page': 1});
    })
})();

$( function() {
    $( "#tabs_mission" ).tabs();
} );

(function(){
    function afficheMessageFlash() {
        var flash = document.getElementById("flashbag");
        if (flash != undefined) {
            flash.style.display = 'none';
        }
    }
    setTimeout(afficheMessageFlash,5000);
})();

/**
 * validation pour le news form
 *
 *
 */
$(function () {
    $("#newsForm").validate({
        rules: {
            "application_plateformebundle_news[statut]": {
                "required": true,
            },
            "application_plateformebundle_news[detailStatut]": {
                "required": true,
            },
            "application_plateformebundle_news[motif]": {
                "required": function(element){
                    return $("#application_plateformebundle_news_statut").val() == 12;
                }
            }
        },
        errorElement: 'div'
    })
});

/**
 * validation pour le news form
 *
 *
 */
$(function () {
    $('#recherche_beneficiaire_complementStatut').on('change', function () {
        if ($(this).val() == '>='){
            $('#recherche_beneficiaire_complementDetailStatut').prop('disabled', true)
            $('#recherche_beneficiaire_complementDetailStatut option[value=">="]').prop('selected', true)
        }else{
            $('#recherche_beneficiaire_complementDetailStatut').prop('disabled', false)
        }
    })
});

$(window).load(function() {
    $("#overlayB").fadeOut("1500");
    //console.log("Infos disponibles ");
});
$(function(){
    //console.log("Recupération de vos infos");
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

$('#form_declaration_activite').validate({
    rules: {
        "form[numDeclarationActivite]":{
            "required": true,
            "digits": true,
            "minlength": 9,
            "maxlength": 9,
        }
    },
    errorElement: 'div'
});


$(document).ready(function () {
    if(document.getElementById('account')) {
        rechercheVille($("#facturation_code_postal"), $("#facturation_ville"))
        $("#facturation_code_postal").on('keyup', function () {
            rechercheVille(this, $("#facturation_ville"))
        })

        formFacturation($("#facturation_type"))
        $("#facturation_type").on('change', function () {
            formFacturation(this)
        })

        $("form[name='facturation']").validate({
            errorElement: 'span',
            errorPlacement: function (error, element) {
            }
        })

        afficheAutreFonction($("#facturation_representantLegalFonction"))
        $("#facturation_representantLegalFonction").on('change', function () {
            afficheAutreFonction(this)
        })
    }
})

function afficheAutreFonction(el) {
    if ($(el).val() == 'autre'){
        $("#autre_fonction").css('display','inherit');
        $("#facturation_intitule").attr('required',true)
    }else{
        $("#autre_fonction").css('display','none');
        $("#facturation_intitule").attr('required',false)
    }
}

function rechercheVille(dpt, villeCible) {
    console.log(villeCible.val())
    cp = $(dpt).val();
    if (cp.length == 5) {
        $.ajax({
            type: 'get',
            url: Routing.generate("application_plateforme_get_ville_ajax", {departement: cp}),
            beforeSend: function () {
                villeCible.empty();
            }
        })
            .done(function (data) {
                for (var i = 0; i < data.villes.length; i++) {
                    $(villeCible).append("<option value=" + data.villes[i].id + ">" + data.villes[i].nom + "</option>")
                }
            });
    } else {
        villeCible.empty()
    }
}

function formFacturation(el) {
    id = $(el).attr('id')
    val = $('#' + id + ' option:selected').val()
    console.log(val)
    form = $(el).parents('form')
    champs = $(form).find('.modal-body').children()
    $(champs).each(function (i) {
        if ($(this).hasClass(val) || $(this).hasClass('facturation_type')){
            $(this).css('display', 'block')
            $(this).find(".form-control").attr('required', 'required')
            $(this).find(".not_required").attr('required', false)
        }else{
            $(this).css('display', 'none')
            $(this).find(".form-control").attr('required', false)
            $(this).find("input[type='file']").attr('required', false)
        }
    })
}