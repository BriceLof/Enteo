$(function () {

    $(".tablesorter").tablesorter({
        dateFormat : "ddmmyyyy"
    });

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

    $(".beneficiaireSearchFactureField  ").keyup(function () {
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
                        console.log(tab[i])
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
});

function paiementFieldShowHidde(valeur)
{
    console.log(valeur)
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