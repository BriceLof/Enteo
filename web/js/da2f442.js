<<<<<<< HEAD
;$(function(){$(".tablesorter").tablesorter({dateFormat:"ddmmyyyy"});$(".lienModalPaiement").click(function(){nomLien=$(this).attr("data-target");statePaiement=$(nomLien).find(".statutPaiementFacture").val();paiementFieldShowHidde(statePaiement);modePaiement=$(nomLien).find(".modePaiementFactureField").val();if(modePaiement=="cheque"){$(".banqueFacture").css("display","block");$(".banqueFactureField").attr("required","required")}
else{$(".banqueFactureField").removeAttr("required");$(".banqueFacture").css("display","none")}});$(".formulaire_paiement_facture").submit(function(e){form=$(this);form.find(".error").hide();val=controleMontant(form);if(val==!1){e.preventDefault()}
else{$("button[type='submit']",this).attr("disabled","disabled")}});$(".statutPaiementFacture").change(function(){statePaiement=$(this).children("option:selected").val();paiementFieldShowHidde(statePaiement)});$(".modePaiementFactureField").change(function(){modePaiement=$(this).children("option:selected").val();if(modePaiement=="cheque"){$(".banqueFacture").css("display","block");$(".banqueFactureField").attr("required","required")}
else{$(".banqueFactureField").removeAttr("required");$(".banqueFacture").css("display","none")}});$(".beneficiaireSearchFactureField").keyup(function(){nom=$(this).val();if(nom.length>=3){$.ajax({type:"get",url:Routing.generate("application_search_facture_ajax_beneficiaire",{string:nom}),beforeSend:function(){$(".block_info_chargement").show()}}).done(function(t){$(".block_info_chargement").hide();tab=JSON.parse(t);console.log(tab);console.log(tab.length);$("#select_beneficiaire_ajax option").remove();for(var e=0;e<tab.length;e++){$("#select_beneficiaire_ajax").append("<option value="+tab[e].id+">"+tab[e].nom+" "+tab[e].prenom+"</option>")}})}
else{$("#select_beneficiaire_ajax option").remove()}});$("#btn_filtre_facture").click(function(){if($("#filtre_facture").hasClass("open")){$("#filtre_facture").removeClass("open");$("#filtre_facture").hide().addClass("closed");$(this).html("Afficher les filtres")}
else{$("#filtre_facture").removeClass("closed");$("#filtre_facture").show().addClass("open");$(this).html("Cacher les filtres")}});$(".dateFactureField").change(function(){date=$(this).val();if(date.length==0){$(".dateFactureField").removeAttr("required min max ")}
else{$(".dateFactureField").attr("required","required");if($(this).hasClass("dateFactureStartField")){$(".dateFactureEndField").attr("min",date)}
else{$(".dateFactureStartField").attr("max",date)}}});$("#export_csv_facture").click(function(){document.formulaire_filtre_facture.action=Routing.generate("application_csv_getListFacture");document.formulaire_filtre_facture.submit();document.formulaire_filtre_facture.action=Routing.generate("application_search_facture")})});function paiementFieldShowHidde(e){if(e=="paid"||e=="partiel"){$(".montantPayerFacture").css("display","block");$(".montantPayerFactureField").attr("required","required");$(".modePaiementFacture").css("display","block");$(".modePaiementFactureField").attr("required","required");$(".datePaiementFacture").css("display","block");$(".datePaiementFactureField").attr("required","required")}
else{$(".montantPayerFacture").css("display","none");$(".montantPayerFactureField").removeAttr("required");$(".modePaiementFacture").css("display","none");$(".modePaiementFactureField").removeAttr("required");$(".datePaiementFacture").css("display","none");$(".datePaiementFactureField").removeAttr("required")};if(e=="partiel"||e=="perte et profit"||e=="error"){$(".commentaireFactureField").attr("required","required");$(".label_commentaire_facture").text("Commentaire *")}
else{$(".commentaireFactureField").removeAttr("required");$(".label_commentaire_facture").text("Commentaire")}};function controleMontant(e){statePaiement=e.find(".statutPaiementFacture").val();montantSubmit=e.find(".montantPayerFactureField").val();if(montantSubmit.indexOf(",")!=-1)montantSubmit=montantSubmit.replace(",",".");montantDejaRegler=e.find(".montantFactureDejaReglerHidden").val();montantAReglerTotal=e.find(".montantFactureHidden").val();valid=!0;if(statePaiement=="paid"){if((Number(montantSubmit)+Number(montantDejaRegler))!=Number(montantAReglerTotal)){e.find(".error").html("Le montant total réglé est différent du montant de la facture").show();valid=!1}
else{e.find(".error").hide()}}
else if(statePaiement=="partiel"){if((Number(montantSubmit)+Number(montantDejaRegler))>Number(montantAReglerTotal)){e.find(".error").html("Le montant total réglé est supérieur au montant de la facture").show();valid=!1}
else{if((Number(montantSubmit)+Number(montantDejaRegler))==Number(montantAReglerTotal)){e.find(".error").html("Ce règlement solde la facture. Merci de l'enregistrer en statut \"Paid\"").show();valid=!1}
else{e.find(".error").hide()}}};return valid};
=======
$(function () {
    $(".tablesorter").tablesorter({
        dateFormat : "ddmmyyyy"
    });

    // --------------------------- FIN Page facture : Formulaire de paiement
    $(".lienModalPaiement").click(function(){
        nomLien = $(this).attr("data-target")

        statePaiement = $(nomLien).find('.statutPaiementFacture').val()
        paiementFieldShowHidde(statePaiement)

        modePaiement = $(nomLien).find('.modePaiementFactureField').val()
        if(modePaiement == 'cheque'){
            $(".banqueFacture").css('display', 'block')
            $(".banqueFactureField").attr('required', 'required')
        }
        else{
            $(".banqueFactureField").removeAttr('required')
            $(".banqueFacture").css('display', 'none')
        }
    })

    // Vérification que le montant du paiement total soit bien égale à la valeur de la facture
    $(".formulaire_paiement_facture").submit(function(event){
        form = $(this)
        form.find(".error").hide()
        val = controleMontant(form)
        // si montant total n'est pas valide, on ne submit pas le form
        if(val == false){
            event.preventDefault();
        }else{
            // Bloquer les multiples submit de form durant le submitting
            $("button[type='submit']", this).attr('disabled', 'disabled');
        }
    })

    $(".statutPaiementFacture").change(function () {
        statePaiement = $(this).children("option:selected").val()
        paiementFieldShowHidde(statePaiement)
    });

    $(".modePaiementFactureField").change(function () {
        modePaiement = $(this).children("option:selected").val()
        if(modePaiement == 'cheque'){
            $(".banqueFacture").css('display', 'block')
            $(".banqueFactureField").attr('required', 'required')
        }
        else{
            $(".banqueFactureField").removeAttr('required')
            $(".banqueFacture").css('display', 'none')
        }
    });
    // --------------------------- FIN Page facture : Formulaire de paiement

    // --------------------------- Page facture : filtre de recherche



    $(".beneficiaireSearchFactureField").keyup(function () {
        nom = $(this).val();
        if (nom.length >= 3) {
            $.ajax({
                type: 'get',
                url: Routing.generate("application_search_facture_ajax_beneficiaire", {string: nom}),
                beforeSend: function () {
                    $(".block_info_chargement").show();
                }
            })
                .done(function (data) {
                    $(".block_info_chargement").hide();
                    tab = JSON.parse(data)
                    console.log(tab)
                    console.log(tab.length)
                    $("#select_beneficiaire_ajax option").remove()
                    for (var i = 0; i < tab.length; i++) {
                        $("#select_beneficiaire_ajax").append("<option value=" + tab[i].id + ">" + tab[i].nom +" "+ tab[i].prenom +"</option>")
                    }
                });
        } else {
            $("#select_beneficiaire_ajax option").remove()
        }
    });

    $("#btn_filtre_facture").click(function(){
        if($("#filtre_facture").hasClass("open")){
            $("#filtre_facture").removeClass("open")
            $("#filtre_facture").hide().addClass("closed")
            $(this).html("Afficher les filtres")
        }else{
            $("#filtre_facture").removeClass("closed")
            $("#filtre_facture").show().addClass("open")
            $(this).html("Cacher les filtres")
        }
    })

    // Gestion des dates debut et fin, pour que les 2 champs soient obligatoires, si l'un est rempli
    $(".dateFactureField").change(function () {
        date = $(this).val();
        if(date.length == 0) {
            $(".dateFactureField").removeAttr('required min max ')
        }
        else{
            $(".dateFactureField").attr('required', 'required')
            if($(this).hasClass("dateFactureStartField")){
                $(".dateFactureEndField").attr('min', date)
            }else{
                $(".dateFactureStartField").attr('max', date)
            }
        }
    });


    $("#export_csv_facture").click(function () {
        document.formulaire_filtre_facture.action = Routing.generate('application_csv_getListFacture');
        document.formulaire_filtre_facture.submit();
        // je rechange l'action du formulaire pour que je puisse de nouveau faire une recherche sinon ça relance l'export d'un fichier et non le resubmit du form
        document.formulaire_filtre_facture.action = Routing.generate('application_search_facture');
    })
    // --------------------------- FIN Page facture : filtre de recherche

});

function paiementFieldShowHidde(valeur)
{
    //console.log(valeur)
    if (valeur == 'paid' || valeur == 'partiel' ) {
        $(".montantPayerFacture").css('display', 'block')
        $(".montantPayerFactureField").attr('required', 'required')
        $(".modePaiementFacture").css('display', 'block')
        $(".modePaiementFactureField").attr('required', 'required')
        $(".datePaiementFacture").css('display', 'block')
        $(".datePaiementFactureField").attr('required', 'required')
    }
    else {
        $(".montantPayerFacture").css('display', 'none')
        $(".montantPayerFactureField").removeAttr('required')
        $(".modePaiementFacture").css('display', 'none')
        $(".modePaiementFactureField").removeAttr('required')
        $(".datePaiementFacture").css('display', 'none')
        $(".datePaiementFactureField").removeAttr('required')
    }
    /////////////////////////////////////////////////////////////////////////////////////
    if (valeur == 'partiel' || valeur == 'perte et profit' || valeur == 'error' ) {
        $(".commentaireFactureField").attr('required', 'required')
        $(".label_commentaire_facture").text("Commentaire *")
    }
    else{
        $(".commentaireFactureField").removeAttr('required')
        $(".label_commentaire_facture").text("Commentaire")
    }
}

function controleMontant(form){
    statePaiement = form.find(".statutPaiementFacture").val()
    montantSubmit = form.find(".montantPayerFactureField").val()
    if(montantSubmit.indexOf(",") != -1)
         montantSubmit = montantSubmit.replace(',', '.')

    montantDejaRegler = form.find(".montantFactureDejaReglerHidden").val()
    montantAReglerTotal = form.find(".montantFactureHidden").val()
    valid = true
    if(statePaiement == 'paid') {
        if ((Number(montantSubmit) + Number(montantDejaRegler)) != Number(montantAReglerTotal)) {
            form.find(".error").html("Le montant total réglé est différent du montant de la facture").show()
            valid = false;
        }else{
            form.find(".error").hide()
        }
    }
    else if(statePaiement == 'partiel'){
        if ((Number(montantSubmit) + Number(montantDejaRegler)) > Number(montantAReglerTotal) ) {
            form.find(".error").html("Le montant total réglé est supérieur au montant de la facture").show()
            valid = false;
        }else{
            if((Number(montantSubmit) + Number(montantDejaRegler)) == Number(montantAReglerTotal)){
                form.find(".error").html("Ce règlement solde la facture. Merci de l'enregistrer en statut \"Paid\"").show()
                valid = false;
            }
            else{
                form.find(".error").hide()
            }

        }
    }
    return valid
}

>>>>>>> 34438da46a4f4e5bb494265bdaa5eb4c845f557c
