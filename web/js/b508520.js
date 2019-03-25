<<<<<<< HEAD
;$(window).load(function(){console.log('Infos disponibles ')});$(function(){$('.dateTimePicker').datetimepicker({locale:'fr',format:'YYYY-MM-DD HH:mm:ss'});$('.villeAjaxBeneficiaire option:first-child').val('');$('.villeAjaxBeneficiaire option:first-child').text('');$('.villeAjaxBeneficiaire').attr('disabled','disabled');$('.villeAjaxEmployeur').attr('disabled','disabled');getVilleByZipAjax('Beneficiaire');if($('.codePostalInputForAjaxEmployeur').length>0){getVilleByZipAjax('Employeur')};if($('.countryBeneficiaire option:selected').val()!='FR'&&$('.countryBeneficiaire option:selected').val()!=''){showHideVille('Beneficiaire')};if($('.countryEmployeur option:selected').val()!='FR'&&$('.countryEmployeur option:selected').val()!=''){showHideVille('Employeur')};$('.country').change(function(){type=$(this).data('country-type');$('.codePostalInputForAjax'+type).removeAttr('disabled');country=$(this).children('option:selected').val();console.log(country);if(country=='FR'){$('.codePostalInputForAjax'+type).val('');$('.codePostalInputForAjax'+type).attr('minlength','5');$('.block_ville_fr'+type).show();$('.block_ville_no_fr'+type).hide();$('.block_ville_no_fr'+type+' .villeNoFr'+type).removeAttr('required');getVilleByZipAjax(type)}
else{$('.codePostalInputForAjax'+type).removeAttr('minlength');$('.codePostalInputForAjax'+type).off();$('.codePostalInputForAjax'+type).val('');$('.block_ville_fr'+type).hide();$('.block_ville_no_fr'+type).show();$('.block_ville_no_fr'+type+' .villeNoFr'+type).val('');$('.block_ville_no_fr'+type+' .villeNoFr'+type).attr('required','required')}});$('.origineMerQui').change(function(){$origineMerQui=$(this).children('option:selected').val();if($origineMerQui!=''){$('.origineMerComment').css('visibility','initial');$('.origineMerComment').attr('required','required')}
else{$('.origineMerComment').css('visibility','hidden');$('.origineMerComment').removeAttr('required')}});$('.origineMerComment').change(function(){$origineMerComment=$(this).children('option:selected').val();if($origineMerComment=='payant'){$('.origineMerDetailComment').css('visibility','initial');$('.origineMerDetailComment').attr('required','required')}
else{$('.origineMerDetailComment').css('visibility','hidden');$('.origineMerDetailComment').removeAttr('required')}});$('.country').change(function(){countryCode=$(this).children('option:selected').val();console.log(countryCode);$.ajax({url:window.location.origin+'/web/file/country_phone_code.json',success:function(e){$('.indicatifTel').val(e[countryCode])}})});$('.voirPlusNouvelle').click(function(){$('#afficheListNouvelle .hiddenNews').toggle('slow')});$('.blockGenerationFacturePaiement').mouseenter(function(){if($('.blockGenerationFacturePaiement button').prop('disabled')==!0){$('.disabledFacturationGeneration').show()}
else{$('.disabledFacturationGeneration').hide()}});$('.blockGenerationFacturePaiement').mouseleave(function(){$('.disabledFacturationGeneration').hide()});$('.histo_consultant a').click(function(){$('.histo_consultant p').toggle('slow');return!1})});(function(){if(document.getElementById('accompagnement')){var o=document.getElementById('proposition_pdf'),n=document.getElementById('beneficiaire_proposition_adresse'),l=document.getElementById('accompagnement_proposition_heure'),a=document.getElementById('accompagnement_proposition_dateDebut'),t=document.getElementById('accompagnement_proposition_dateFin'),e='champs manquant : <br>',i=document.getElementById('projet_diplomeVise');o.addEventListener('click',function(o){if(n.value==''||a.value=='0'||t.value=='0'||l.value==''||i.value==''){if(n.value==''){e+='- l\'adresse du bénéficiaire <br>'};if(a.value=='0'){e+='- la date de début de l\'accompagnement <br>'};if(t.value=='0'){e+='- la date de fin de l\'accompagnement <br>'};if(l.value==''){e+='- le nombre d\'accompagnement en heures <br>'};if(i.value==''){e+='- le diplome visé <br>'};$('body').append('<div id="dataConfirmModal2" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de verifier</h3></div><div class="modal-body"><p>'+e+'</p></div><div class="modal-footer"><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Fermer</button></div></div></div></div>');$('#dataConfirmModal2').find('.modal-body').text($(this).attr('data-confirm'));$('#dataConfirmModal2').modal({show:!0});o.preventDefault()}
else{}})};$(function(){$a=1;$('.contact_employeur').children().children().children().each(function(){if($(this).get(0).tagName=='LABEL'){$(this).css('display','none');$a++}})})})();$(function(){$('.villeAjaxEmployeur option:first-child').val('');$('.villeAjaxEmployeur option:first-child').text('');$('.villeAjaxEmployeur').attr('disabled','disabled');$('.codePostalInputForAjaxEmployeur').keyup(function(){$('.villeAjaxEmployeur').attr('disabled','disabled');cp=$(this).val();if(cp.length==5){$.ajax({type:'get',url:Routing.generate('application_plateforme_get_ville_ajax',{departement:cp}),beforeSend:function(){$('.villeAjaxEmployeur option').remove()}}).done(function(e){$('.villeAjaxEmployeur').removeAttr('disabled');for(var i=0;i<e.villes.length;i++){$('.villeAjaxEmployeur').append('<option value='+e.villes[i].id+'>'+e.villes[i].nom+'</option>')}})}
else{$('.villeAjaxEmployeur option').remove();$('.villeAjaxEmployeur').removeAttr('disabled')}});if($('#beneficiaire_employeur_codePostalHiddenEmployeur').val()!=0){cp=$('#beneficiaire_employeur_codePostalHiddenEmployeur').val();idVille=$('#beneficiaire_employeur_idVilleHiddenEmployeur').val();if(cp.length==5){$.ajax({type:'get',url:Routing.generate('application_plateforme_get_ville_ajax',{departement:cp}),beforeSend:function(){}}).done(function(e){$('.block_info_chargement').hide();$('.villeAjaxEmployeur option').remove();$('.villeAjaxEmployeur').removeAttr('disabled');for(var i=0;i<e.villes.length;i++){if(idVille==e.villes[i].id)$('.villeAjaxEmployeur').append('<option selected data-cp='+e.villes[i].cp+' value='+e.villes[i].id+'>'+e.villes[i].nom+'</option>');else $('.villeAjaxEmployeur').append('<option data-cp='+e.villes[i].cp+' value='+e.villes[i].id+'>'+e.villes[i].nom+'</option>')}})}}});$(function(){$('.organisme').each(function(){var e=$('<select>');$.each(this.attributes,function(i,t){$(e).attr(t.name,t.value)});$(this).replaceWith(e)})});$(function(){changeOrganisme($('#projet_typeFinanceur'));$('#suivi_administratif_typeFinanceur option[value="'+$('#suivi_administratif_typeFinanceur').attr('value')+'"]').prop('selected',!0);changeOrganisme($('#suivi_administratif_typeFinanceur'));changeOrganisme($('#beneficiaire_employeur_typeFinanceur'))});(function(){$('#beneficiaire_dptTravail').each(function(){var e=$('<select>');$.each(this.attributes,function(i,t){$(e).attr(t.name,t.value)});$(this).replaceWith(e)});ajaxListDepartement($('#beneficiaire_regionTravail').val(),$('#beneficiaire_dptTravail'));$('#beneficiaire_regionTravail').on('change',function(){ajaxListDepartement($(this).val(),$('#beneficiaire_dptTravail'))})})();function ajaxListDepartement(e,i){$.ajax({url:Routing.generate('application_get_dpt_by_region_ville',{'region':e}),cache:!0,type:'get',dataType:'json',beforeSend:function(){i.empty()},success:function(e){$.each(JSON.parse(e),function(e,t){if($(i).attr('value')==t.code){i.append('<option value="'+t.code+'" selected = "selected">'+t.codeShow+' '+t.dpt+'</option>')}
else{i.append('<option value="'+t.code+'">'+t.codeShow+' '+t.dpt+'</option>')}})}})};function changeOrganisme(e){parent=$(e).parent().parent().parent();tdElement=$(parent).find('#tdElement');element=parent.find('#organisme');if($(e).val()=='OPCA'){if($(tdElement).prop('tagName')=='TR'){tdElement.css('display','table-row')}
else{tdElement.css('display','block')};$name='OPCA';listeOpcaOpacifEmployeur($name,element)}
else{tdElement.css('display','none');element.empty()};if($(e).attr('id')=='suivi_administratif_typeFinanceur'){if($(e).val()!=''){$('#financeur_information').css('display','block')}
else{$('#financeur_information').css('display','none')}}};$('#suivi_administratif_detailStatut').change(function(){$('#suivi_administratif_detailStatutHidden').val($(this).val())});function listeOpcaOpacifEmployeur(e,i){$.ajax({url:Routing.generate('application_opca_opacif_financeur',{'nom':e}),cache:!0,type:'get',dataType:'json',beforeSend:function(){i.empty()},success:function(e){$.each(JSON.parse(e),function(e,t){if($(i).attr('value')==t){i.append('<option value="'+t+'" selected = "selected">'+t+'</option>')}
else{i.append('<option value="'+t+'">'+t+'</option>')}})}})};function getVilleByZipAjax(e){console.log(e);console.log($('.codePostalInputForAjax'+e).val());if($('.codePostalInputForAjax'+e).val().length==5&&$('.country'+e).val()=='FR'){cp=$('.codePostalInputForAjax'+e).val();$.ajax({type:'get',url:Routing.generate('application_plateforme_get_ville_ajax',{departement:cp}),beforeSend:function(){$('.villeAjax'+e+' option').remove();$('.block_info_chargement').show()}}).done(function(i){$('.block_info_chargement').hide();$('.villeAjax'+e).removeAttr('disabled');for(var t=0;t<i.villes.length;t++){if($('#beneficiaire_idVilleHiddenBeneficiaire').attr('value')==i.villes[t].id){$('.villeAjax'+e).append('<option value='+i.villes[t].id+' selected>'+i.villes[t].nom+'</option>')}
else{$('.villeAjax'+e).append('<option value='+i.villes[t].id+'>'+i.villes[t].nom+'</option>')}}})}
else if($('.codePostalInputForAjax'+e).val().length>1&&$('.country'+e).val()!='FR'){cp=$('.codePostalInputForAjax'+e).val();$.ajax({type:'get',url:Routing.generate('application_plateforme_get_ville_ajax',{departement:cp}),beforeSend:function(){$('.villeAjax'+e+' option').remove();$('.block_info_chargement').show()}}).done(function(i){$('.block_info_chargement').hide();$('.villeAjax'+e).removeAttr('disabled');for(var t=0;t<i.villes.length;t++){if($('#beneficiaire_idVilleHiddenBeneficiaire').attr('value')==i.villes[t].id){$('.villeAjax'+e).append('<option value='+i.villes[t].id+' selected>'+i.villes[t].nom+'</option>')}
else{$('.villeAjax'+e).append('<option value='+i.villes[t].id+'>'+i.villes[t].nom+'</option>')}}})}
else{$('.villeAjax'+e+' option').remove();$('.villeAjax'+e).attr('disabled','disabled')};$('.codePostalInputForAjax'+e).keyup(function(){$('.villeAjax'+e).attr('disabled','disabled');cp=$(this).val();if(cp.length==5){$.ajax({type:'get',url:Routing.generate('application_plateforme_get_ville_ajax',{departement:cp}),beforeSend:function(){$('.villeAjax'+e+' option').remove();$('.block_info_chargement').show()}}).done(function(i){$('.block_info_chargement').hide();$('.villeAjax'+e).removeAttr('disabled');for(var t=0;t<i.villes.length;t++){$('.villeAjax'+e).append('<option value='+i.villes[t].id+'>'+i.villes[t].nom+'</option>')}})}
else{$('.villeAjax'+e+' option').remove();$('.villeAjax'+e).attr('disabled','disabled')}})};function showHideVille(e){$('.codePostalInputForAjax'+e).removeAttr('disabled');$('.codePostalInputForAjax'+e).removeAttr('minlength');$('.block_ville_fr'+e).hide();$('.block_ville_no_fr'+e).show();$('.block_ville_no_fr'+e+' .villeNoFr'+e).attr('required','required')};$(function(){$('#planning_previsionnel_form').validate({rules:{'planning_previsionnel[dateLivret1]':{'required':!0,},'planning_previsionnel[dateLivret2]':{'required':!0,},'planning_previsionnel[dateJury]':{'required':!0,},'planning_previsionnel[statutLivret1]':{'required':!0,'realise1':!0},'planning_previsionnel[statutLivret2]':{'required':!0,'realise2':!0},'planning_previsionnel[statutJury]':{'required':!0,'realise3':!0}},errorElement:'div'});jQuery.validator.addMethod('realise1',function(e,i){date=$('#planning_previsionnel_dateLivret1').val();parts=date.split('/');var t=new Date(parts[2],parts[1]-1,parts[0]);if($(i).val()=='realise'&&t>new Date()){return!1};return!0},'');jQuery.validator.addMethod('realise2',function(e,i){date=$('#planning_previsionnel_dateLivret2').val();parts=date.split('/');var t=new Date(parts[2],parts[1]-1,parts[0]);if($(i).val()=='realise'&&t>new Date()){return!1};return!0},'');jQuery.validator.addMethod('realise3',function(e,i){date=$('#planning_previsionnel_dateJury').val();parts=date.split('/');var t=new Date(parts[2],parts[1]-1,parts[0]);if($(i).val()=='realise'&&t>new Date()){return!1};return!0},'')});
=======
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
    $(".villeAjaxEmployeur").attr("disabled", "disabled");

    /* Si lors de la validation du formulaire, erreur de validation d'un champs coté php, la page sera racharger en montrant le champs erroner.
     Cependant si les champs code postal et ville qui n'étaient pas vide lors de la validation, lors du reload de la page le champs ville charger en ajax sera vide mais on pourra valider malgré tout le form
     ce qui provoquera une erreur par la suite dans la fiche detail du benef.
     */

    getVilleByZipAjax("Beneficiaire")

    if ($(".codePostalInputForAjaxEmployeur").length > 0) {
        getVilleByZipAjax("Employeur")
    }


    //-----------------------------------------------------------------------------------------------------------------------//
    //--------------------------  Page Ajout d'un bénéficiaire manuellement  ------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//

    if ($(".countryBeneficiaire option:selected").val() != "FR" && $(".countryBeneficiaire option:selected").val() != "") {
        showHideVille("Beneficiaire")
    }
    ;

    if ($(".countryEmployeur option:selected").val() != "FR" && $(".countryEmployeur option:selected").val() != "") {
        showHideVille("Employeur")
    }
    ;

    $(".country").change(function () {
        type = $(this).data("country-type")
        $(".codePostalInputForAjax" + type).removeAttr("disabled");
        country = $(this).children("option:selected").val()
        console.log(country)
        if (country == 'FR') {
            $(".codePostalInputForAjax" + type).val("");
            $(".codePostalInputForAjax" + type).attr("minlength", "5");
            $(".block_ville_fr" + type).show()
            $(".block_ville_no_fr" + type).hide()
            $(".block_ville_no_fr" + type + " .villeNoFr" + type).removeAttr('required')

            getVilleByZipAjax(type)
        } else {
            $(".codePostalInputForAjax" + type).removeAttr("minlength");
            $(".codePostalInputForAjax" + type).off()
            $(".codePostalInputForAjax" + type).val("");
            $(".block_ville_fr" + type).hide()
            $(".block_ville_no_fr" + type).show()
            $(".block_ville_no_fr" + type + " .villeNoFr" + type).val("")
            $(".block_ville_no_fr" + type + " .villeNoFr" + type).attr('required', 'required')
        }
    });

    $(".origineMerQui").change(function () {
        $origineMerQui = $(this).children("option:selected").val()
        if ($origineMerQui != '') {
            $(".origineMerComment").css('visibility', 'initial')
            $(".origineMerComment").attr('required', 'required')
        } else {
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

    $(".country").change(function () {
        countryCode = $(this).children("option:selected").val()
        console.log(countryCode)
        $.ajax({
            url: window.location.origin + "/web/file/country_phone_code.json",
            success: function (result) {
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

    $(".blockGenerationFacturePaiement").mouseenter(function () {
        if ($(".blockGenerationFacturePaiement button").prop("disabled") == true) {
            $(".disabledFacturationGeneration").show()
        } else {
            $(".disabledFacturationGeneration").hide()
        }
    });
    $(".blockGenerationFacturePaiement").mouseleave(function () {
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
    $('#suivi_administratif_typeFinanceur option[value="' + $('#suivi_administratif_typeFinanceur').attr('value') + '"]').prop('selected', true);
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
        if ($(tdElement).prop("tagName") == 'TR') {
            tdElement.css('display', 'table-row');
        } else {
            tdElement.css('display', 'block');
        }
        $name = 'OPCA';
        listeOpcaOpacifEmployeur($name, element);
    } else {
        tdElement.css('display', 'none');
        element.empty();
    }

    if ($(typeFinanceur).attr('id') == 'suivi_administratif_typeFinanceur') {
        if ($(typeFinanceur).val() != '') {
            $('#financeur_information').css('display', 'block')
        } else {
            $('#financeur_information').css('display', 'none')
        }
    }
}

$('#suivi_administratif_detailStatut').change(function () {
    $("#suivi_administratif_detailStatutHidden").val($(this).val())
})

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

// Ajax pour recuperer la ville en fonction du CP
function getVilleByZipAjax(type) {
    console.log(type)
    console.log($(".codePostalInputForAjax" + type).val())
    if ($(".codePostalInputForAjax" + type).val().length == 5 && $(".country" + type).val() == "FR") {
        cp = $(".codePostalInputForAjax" + type).val()
        $.ajax({
            type: 'get',
            url: Routing.generate("application_plateforme_get_ville_ajax", {departement: cp}),
            beforeSend: function () {
                $(".villeAjax" + type + " option").remove();
                $(".block_info_chargement").show();
            }
        })
            .done(function (data) {
                $(".block_info_chargement").hide();
                $(".villeAjax" + type).removeAttr("disabled");
                for (var i = 0; i < data.villes.length; i++) {
                    if ($('#beneficiaire_idVilleHiddenBeneficiaire').attr('value') == data.villes[i].id) {
                        $(".villeAjax" + type).append("<option value=" + data.villes[i].id + " selected>" + data.villes[i].nom + "</option>")
                    } else {
                        $(".villeAjax" + type).append("<option value=" + data.villes[i].id + ">" + data.villes[i].nom + "</option>")
                    }
                }
            });
    } else if ($(".codePostalInputForAjax" + type).val().length > 1 && $(".country" + type).val() != "FR") {
        cp = $(".codePostalInputForAjax" + type).val()
        $.ajax({
            type: 'get',
            url: Routing.generate("application_plateforme_get_ville_ajax", {departement: cp}),
            beforeSend: function () {
                $(".villeAjax" + type + " option").remove();
                $(".block_info_chargement").show();
            }
        })
            .done(function (data) {
                $(".block_info_chargement").hide();
                $(".villeAjax" + type).removeAttr("disabled");
                for (var i = 0; i < data.villes.length; i++) {
                    if ($('#beneficiaire_idVilleHiddenBeneficiaire').attr('value') == data.villes[i].id) {
                        $(".villeAjax" + type).append("<option value=" + data.villes[i].id + " selected>" + data.villes[i].nom + "</option>")
                    } else {
                        $(".villeAjax" + type).append("<option value=" + data.villes[i].id + ">" + data.villes[i].nom + "</option>")
                    }
                }
            });
    } else {
        $(".villeAjax" + type + " option").remove();
        $(".villeAjax" + type).attr("disabled", "disabled");
    }

    $(".codePostalInputForAjax" + type).keyup(function () {
        $(".villeAjax" + type).attr("disabled", "disabled");
        cp = $(this).val();
        if (cp.length == 5) {
            $.ajax({
                type: 'get',
                url: Routing.generate("application_plateforme_get_ville_ajax", {departement: cp}),
                beforeSend: function () {
                    $(".villeAjax" + type + " option").remove();
                    $(".block_info_chargement").show();
                }
            })
                .done(function (data) {
                    $(".block_info_chargement").hide();
                    $(".villeAjax" + type).removeAttr("disabled");
                    for (var i = 0; i < data.villes.length; i++) {
                        $(".villeAjax" + type).append("<option value=" + data.villes[i].id + ">" + data.villes[i].nom + "</option>")
                    }
                });
        } else {
            $(".villeAjax" + type + " option").remove();
            $(".villeAjax" + type).attr("disabled", "disabled");
        }
    });
}

// Sert à afficher ou cacher le champ ville en fonction du pays (France ou un pays etranger). Le champ ville sera un select ou simple champ texte
function showHideVille(type) {
    $(".codePostalInputForAjax" + type).removeAttr("disabled");
    $(".codePostalInputForAjax" + type).removeAttr("minlength");
    $(".block_ville_fr" + type).hide()
    $(".block_ville_no_fr" + type).show()
    $(".block_ville_no_fr" + type + " .villeNoFr" + type).attr('required', 'required')
}

/**
 * validation pour le news form
 *
 *
 */
$(function () {
    $("#planning_previsionnel_form").validate({
        rules: {
            "planning_previsionnel[dateLivret1]": {
                "required": true,
            },
            "planning_previsionnel[dateLivret2]": {
                "required": true,
            },
            "planning_previsionnel[dateJury]": {
                "required": true,
            },
            "planning_previsionnel[statutLivret1]": {
                "required": true,
                "realise1": true
            },
            "planning_previsionnel[statutLivret2]": {
                "required": true,
                "realise2": true
            },
            "planning_previsionnel[statutJury]": {
                "required": true,
                "realise3": true
            }
        },
        errorElement: 'div'
    });

    jQuery.validator.addMethod("realise1", function (value, element) {
        date = $("#planning_previsionnel_dateLivret1").val()
        parts = date.split("/")
        var myDate = new Date(parts[2], parts[1] - 1, parts[0]);

        if ($(element).val() == 'realise' && myDate > new Date()) {
            return false
        }
        return true;
    }, "");

    jQuery.validator.addMethod("realise2", function (value, element) {
        date = $("#planning_previsionnel_dateLivret2").val()
        parts = date.split("/")
        var myDate = new Date(parts[2], parts[1] - 1, parts[0]);

        if ($(element).val() == 'realise' && myDate > new Date()) {
            return false
        }
        return true;
    }, "");

    jQuery.validator.addMethod("realise3", function (value, element) {
        date = $("#planning_previsionnel_dateJury").val()
        parts = date.split("/")
        var myDate = new Date(parts[2], parts[1] - 1, parts[0]);

        if ($(element).val() == 'realise' && myDate > new Date()) {
            return false
        }
        return true;
    }, "");
});
>>>>>>> 34438da46a4f4e5bb494265bdaa5eb4c845f557c
