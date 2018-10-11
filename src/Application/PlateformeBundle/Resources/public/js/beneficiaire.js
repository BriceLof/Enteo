$(window).load(function () {
    console.log("Infos disponibles ");
});

/**
 * ajax pour gerer la ville dans la fiche bénéficiaire
 *
 */
$(function () {

    $('.dateTimePicker').datetimepicker({
        locale: 'fr',
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    //----------- Remplir ville en Ajax en indiquant le département

    // vider le premier champs qui est un vrai objet ville  (Uniquement sur la page de création d'un utilisateur)
    // (combine de ma part pour faire accepter mes villes ensuite via l'ajax)

    $(".villeAjaxBeneficiaire option:first-child").val("");
    $(".villeAjaxBeneficiaire option:first-child").text("");
    $(".villeAjaxBeneficiaire").attr("disabled", "disabled");


    /* Si lors de la validation du formulaire, erreur de validation d'un champs coté php, la page sera racharger en montrant le champs erroner.
     Cependant si les champs code postal et ville qui n'étaient pas vide lors de la validation, lors du reload de la page le champs ville charger en ajax sera vide mais on pourra valider malgré tout le form
     ce qui provoquera une erreur par la suite dans la fiche detail du benef.
     */

    getVilleByZipAjax()

    //-----------------------------------------------------------------------------------------------------------------------//
    //--------------------------  Page Ajout d'un bénéficiaire manuellement  ------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//

    // if($(".countryBeneficiaire").length > 0){
    //     $(".codePostalInputForAjaxBeneficiaire").attr("disabled", "disabled");
    // }

    $(".countryBeneficiaire").change(function () {
        $(".codePostalInputForAjaxBeneficiaire").removeAttr("disabled");
        country = $(this).children("option:selected").val()
        console.log(country)
        if (country == 'FR') {
            $(".codePostalInputForAjaxBeneficiaire").val("");
            $(".codePostalInputForAjaxBeneficiaire").attr("minlength", "5");
            $(".block_ville_fr").show()
            $(".block_ville_no_fr").hide()
            $(".block_ville_no_fr .villeNoFrBeneficiaire").removeAttr('required')

            getVilleByZipAjax()
        }
        else{
            $(".codePostalInputForAjaxBeneficiaire").removeAttr("minlength");
            $(".codePostalInputForAjaxBeneficiaire").off()
            $(".codePostalInputForAjaxBeneficiaire").val("");
            $(".block_ville_fr").hide()
            $(".block_ville_no_fr").show()
            $(".block_ville_no_fr .villeNoFrBeneficiaire").val("")
            $(".block_ville_no_fr .villeNoFrBeneficiaire").attr('required', 'required')
        }
    });

    $(".origineMerQui").change(function () {
        $origineMerQui = $(this).children("option:selected").val()
        if ($origineMerQui != '') {
            $(".origineMerComment").css('visibility', 'initial')
            $(".origineMerComment").attr('required', 'required')
        }
        else {
            $(".origineMerComment").css('visibility', 'hidden')
            $(".origineMerComment").removeAttr('required')
        }
    });

    $(".origineMerComment").change(function () {
        $origineMerComment = $(this).children("option:selected").val()
        if ($origineMerComment == 'payant') {
            $(".origineMerDetailComment").css('visibility', 'initial')
            $(".origineMerDetailComment").attr('required', 'required')
        } else {
            $(".origineMerDetailComment").css('visibility', 'hidden')
            $(".origineMerDetailComment").removeAttr('required')
        }
    });

    $(".countryBeneficiaire").change(function () {
        countryCode = $(this).children("option:selected").val()
        $.ajax({
            url: window.location.origin+"/web/file/country_phone_code.json",
            success: function(result){
                $(".indicatifTel").val(result[countryCode])
            }
        });
    });

    //-----------------------------------------------------------------------------------------------------------------------//
    //--------------------------------------------------  Page Bénéficiaire  ------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//

    $(".voirPlusNouvelle").click(function () {
        $("#afficheListNouvelle .hiddenNews").toggle('slow')
    });

    $( ".blockGenerationFacturePaiement" ).mouseenter(function() {
        if($(".blockGenerationFacturePaiement button").prop("disabled") == true){
            $(".disabledFacturationGeneration").show()
        }
        else{
            $(".disabledFacturationGeneration").hide()
        }
    });
    $( ".blockGenerationFacturePaiement" ).mouseleave(function() {
        $(".disabledFacturationGeneration").hide()
    });

    $(".histo_consultant a").click(function () {
        $(".histo_consultant p").toggle('slow')
        return false;
    });

});

/**
 * affiche un modal lors de la génération d'un pdf devis s'il y a des champs manquant
 *
 * ajax affichage de opca et opacif dans la partie projet bénéficiaire
 * ça fait exactement un doublon avec ce qu'il y a dans l'accompagnement
 */
(function () {
    if (document.getElementById('accompagnement')) {
        var proposition = document.getElementById('proposition_pdf');
        var adresse = document.getElementById('beneficiaire_proposition_adresse');
        var heure = document.getElementById('accompagnement_proposition_heure');
        var dateDebut = document.getElementById('accompagnement_proposition_dateDebut');
        var dateFin = document.getElementById('accompagnement_proposition_dateFin');
        var message = "champs manquant : <br>";
        var diplomeVise = document.getElementById('projet_diplomeVise');

        proposition.addEventListener('click', function (e) {
            if (adresse.value == "" || dateDebut.value == "0" || dateFin.value == "0" || heure.value == "" || diplomeVise.value == "") {
                if (adresse.value == "") {
                    message += '- l\'adresse du bénéficiaire <br>';
                }
                if (dateDebut.value == "0") {
                    message += '- la date de début de l\'accompagnement <br>';
                }
                if (dateFin.value == "0") {
                    message += '- la date de fin de l\'accompagnement <br>';
                }
                if (heure.value == "") {
                    message += '- le nombre d\'accompagnement en heures <br>';
                }
                if (diplomeVise.value == "") {
                    message += '- le diplome visé <br>';
                }
                //ici on ajoute un modal
                $('body').append('<div id="dataConfirmModal2" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                    '<div class="modal-dialog">' +
                    '<div class="modal-content">' +
                    '<div class="modal-header">' +
                    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de verifier</h3>' +
                    '</div>' +
                    '<div class="modal-body"><p>' + message + '</p></div>' +
                    '<div class="modal-footer">' +
                    '<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Fermer</button>' +
                    '</div></div></div></div>');
                $('#dataConfirmModal2').find('.modal-body').text($(this).attr('data-confirm'));
                $('#dataConfirmModal2').modal({show: true});
                e.preventDefault();
            } else {
            }
        });
    }

    $(function () {
        $a = 1;
        $('.contact_employeur').children().children().children().each(function () {
            if ($(this).get(0).tagName == 'LABEL') {
                $(this).css('display', 'none');
                $a++;
            }
        })
    });
})();

/**
 * Remplir ville en Ajax en indiquant le département
 *
 * partie brice
 */
$(function () {
    //----------- Remplir ville en Ajax en indiquant le département

    // vider le premier champs qui est un vrai objet ville  (Uniquement sur la page de création d'un utilisateur)
    // (combine de ma part pour faire accepter mes villes ensuite via l'ajax)

    $(".villeAjaxEmployeur option:first-child").val("");
    $(".villeAjaxEmployeur option:first-child").text("");
    $(".villeAjaxEmployeur").attr("disabled", "disabled");
    $(".codePostalInputForAjaxEmployeur").keyup(function () {
        $(".villeAjaxEmployeur").attr("disabled", "disabled");
        cp = $(this).val();
        if (cp.length == 5) {
            $.ajax({
                type: 'get',
                url: Routing.generate("application_plateforme_get_ville_ajax", {departement: cp}),
                beforeSend: function () {
                    $(".villeAjaxEmployeur option").remove();
                    //$(".block_info_chargement").show();
                }
            })
                .done(function (data) {
                    //$(".block_info_chargement").hide();
                    $(".villeAjaxEmployeur").removeAttr("disabled");
                    for (var i = 0; i < data.villes.length; i++) {
                        $(".villeAjaxEmployeur").append("<option value=" + data.villes[i].id + ">" + data.villes[i].nom + "</option>")
                    }
                });
        } else {
            $(".villeAjaxEmployeur option").remove();
            $(".villeAjaxEmployeur").removeAttr("disabled");
        }
    });

    // Pour la modification d'un employeur, on doit cette fois ci récupérer le code postal de sa ville
    if ($("#beneficiaire_employeur_codePostalHiddenEmployeur").val() != 0) {
        cp = $("#beneficiaire_employeur_codePostalHiddenEmployeur").val();
        idVille = $("#beneficiaire_employeur_idVilleHiddenEmployeur").val();
        if (cp.length == 5) {
            $.ajax({
                type: 'get',
                url: Routing.generate("application_plateforme_get_ville_ajax", {departement: cp}),
                beforeSend: function () {
                    // $(".block_info_chargement").show();
                }
            })
                .done(function (data) {
                    $(".block_info_chargement").hide();
                    $(".villeAjaxEmployeur option").remove();
                    $(".villeAjaxEmployeur").removeAttr("disabled");
                    for (var i = 0; i < data.villes.length; i++) {
                        if (idVille == data.villes[i].id)
                            $(".villeAjaxEmployeur").append("<option selected data-cp=" + data.villes[i].cp + " value=" + data.villes[i].id + ">" + data.villes[i].nom + "</option>")
                        else
                            $(".villeAjaxEmployeur").append("<option data-cp=" + data.villes[i].cp + " value=" + data.villes[i].id + ">" + data.villes[i].nom + "</option>")
                    }
                });
        }
    }
});

/**
 * la fonction qui change le input en select sur les champs organismes
 */
$(function () {
    $('.organisme').each(function () {
        var newElement = $('<select>');
        $.each(this.attributes, function (i, attrib) {
            $(newElement).attr(attrib.name, attrib.value);
        });
        $(this).replaceWith(newElement);
    });
});

/**
 * le on load des liste opca et opacif
 */
$(function () {
    changeOrganisme($('#projet_typeFinanceur'));
    $('#suivi_administratif_typeFinanceur option[value="'+ $('#suivi_administratif_typeFinanceur').attr('value') +'"]').prop('selected', true);
    changeOrganisme($('#suivi_administratif_typeFinanceur'));
    changeOrganisme($('#beneficiaire_employeur_typeFinanceur'));
});

/**
 * affichage dynamique du département du travail quand on change la région dans la fiche bénéficiaire
 */
(function () {
	//modification du champ departement du travail en select car c'est un input a la base
    //le select n'est pas pris en compte par symfony
    //peut etre qu'il y a un moyen mais pour l'instant je sais pas
    $('#beneficiaire_dptTravail').each(function () {
        var newElement = $('<select>');
        $.each(this.attributes, function (i, attrib) {
            $(newElement).attr(attrib.name, attrib.value);
        });
        $(this).replaceWith(newElement);
    });

    //modification du select en premier lieu au cas ou
    ajaxListDepartement($('#beneficiaire_regionTravail').val(), $('#beneficiaire_dptTravail'));

    //quand on change la region
    //on regenere une nouvelle liste de departement
    $('#beneficiaire_regionTravail').on('change', function () {
        ajaxListDepartement($(this).val(), $('#beneficiaire_dptTravail'));
    })
})();

/**
 * ajax qui génère la liste des départements
 * @param region
 * @param element
 */
function ajaxListDepartement(region, element) {
    $.ajax({
        url: Routing.generate('application_get_dpt_by_region_ville', {'region': region}), // le nom du fichier indiqué dans le formulaire
        cache: true,
        type: 'get',
        dataType: 'json',
        beforeSend: function () {
            element.empty();
        },
        success: function (data) {
            $.each(JSON.parse(data), function (i, item) {
                if ($(element).attr('value') == item.code) {
                    element.append("<option value=\"" + item.code + "\" selected = \"selected\">" + item.codeShow + ' ' + item.dpt + "</option>");
                } else {
                    element.append("<option value=\"" + item.code + "\">" + item.codeShow + ' ' + item.dpt + "</option>");
                }
            });
        }
    });
}

/**
 * la fonction qui permet d'afficher l'organisme quand on choisi OPCA ou OPACIF sur le
 * @param typeFinanceur
 */
function changeOrganisme(typeFinanceur) {
    parent = $(typeFinanceur).parent().parent().parent();
    tdElement = $(parent).find('#tdElement');
    element = parent.find('#organisme');
    if ($(typeFinanceur).val() == 'OPCA') {
        if ($(tdElement).prop("tagName") == 'TR'){
            tdElement.css('display', 'table-row');
        }else{
            tdElement.css('display', 'inline-block');
        }
        $name = 'OPCA';
        listeOpcaOpacifEmployeur($name, element);
    } else {
        if ($(typeFinanceur).val() == 'OPACIF') {
            if ($(tdElement).prop("tagName") == 'TR'){
                tdElement.css('display', 'table-row');
            }else{
                tdElement.css('display', 'inline-block');
            }
            $name = 'OPACIF';
            listeOpcaOpacifEmployeur($name, element);
        } else {
            tdElement.css('display', 'none');
            element.empty();
        }
    }
}

/**
 * Ajax pour recuperer la liste des opca ou opacif
 */
function listeOpcaOpacifEmployeur($name, element) {
    $.ajax({
        url: Routing.generate('application_opca_opacif_financeur', {'nom': $name}), // le nom du fichier indiqué dans le formulaire
        cache: true,
        type: 'get',
        dataType: 'json',
        beforeSend: function () {
            element.empty();
        },
        success: function (data) {
            $.each(JSON.parse(data), function (i, item) {
                if ($(element).attr('value') == item) {
                    element.append("<option value=\"" + item + "\" selected = \"selected\">" + item + "</option>");
                } else {
                    element.append("<option value=\"" + item + "\">" + item + "</option>");
                }
            });
        }
    });
}


function getVilleByZipAjax(){
    if ($(".codePostalInputForAjaxBeneficiaire").val().length == 5 && $(".countryBeneficiaire").val() == "FR") {
        cp = $(".codePostalInputForAjaxBeneficiaire").val()
        $.ajax({
            type: 'get',
            url: Routing.generate("application_plateforme_get_ville_ajax", {departement: cp}),
            beforeSend: function () {
                $(".villeAjaxBeneficiaire option").remove();
                $(".block_info_chargement").show();
            }
        })
            .done(function (data) {
                $(".block_info_chargement").hide();
                $(".villeAjaxBeneficiaire").removeAttr("disabled");
                for (var i = 0; i < data.villes.length; i++) {
                    if ($('#beneficiaire_idVilleHiddenBeneficiaire').attr('value') == data.villes[i].id) {
                        $(".villeAjaxBeneficiaire").append("<option value=" + data.villes[i].id + " selected>" + data.villes[i].nom + "</option>")
                    } else {
                        $(".villeAjaxBeneficiaire").append("<option value=" + data.villes[i].id + ">" + data.villes[i].nom + "</option>")
                    }
                }
            });
    }
    else if($(".codePostalInputForAjaxBeneficiaire").val().length > 1 && $(".countryBeneficiaire").val() != "FR"){
        cp = $(".codePostalInputForAjaxBeneficiaire").val()
        $.ajax({
            type: 'get',
            url: Routing.generate("application_plateforme_get_ville_ajax", {departement: cp}),
            beforeSend: function () {
                $(".villeAjaxBeneficiaire option").remove();
                $(".block_info_chargement").show();
            }
        })
            .done(function (data) {
                $(".block_info_chargement").hide();
                $(".villeAjaxBeneficiaire").removeAttr("disabled");
                for (var i = 0; i < data.villes.length; i++) {
                    if ($('#beneficiaire_idVilleHiddenBeneficiaire').attr('value') == data.villes[i].id) {
                        $(".villeAjaxBeneficiaire").append("<option value=" + data.villes[i].id + " selected>" + data.villes[i].nom + "</option>")
                    } else {
                        $(".villeAjaxBeneficiaire").append("<option value=" + data.villes[i].id + ">" + data.villes[i].nom + "</option>")
                    }
                }
            });
    }
    else{
        $(".villeAjaxBeneficiaire option").remove();
        $(".villeAjaxBeneficiaire").attr("disabled", "disabled");
    }

    $(".codePostalInputForAjaxBeneficiaire").keyup(function () {
        $(".villeAjaxBeneficiaire").attr("disabled", "disabled");
        cp = $(this).val();
        if (cp.length == 5) {
            $.ajax({
                type: 'get',
                url: Routing.generate("application_plateforme_get_ville_ajax", {departement: cp}),
                beforeSend: function () {
                    $(".villeAjaxBeneficiaire option").remove();
                    $(".block_info_chargement").show();
                }
            })
                .done(function (data) {
                    $(".block_info_chargement").hide();
                    $(".villeAjaxBeneficiaire").removeAttr("disabled");
                    for (var i = 0; i < data.villes.length; i++) {
                        $(".villeAjaxBeneficiaire").append("<option value=" + data.villes[i].id + ">" + data.villes[i].nom + "</option>")
                    }
                });
        } else {
            $(".villeAjaxBeneficiaire option").remove();
            $(".villeAjaxBeneficiaire").attr("disabled", "disabled");
        }
    });
}