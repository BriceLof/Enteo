$(function () {

    //$( ".datePicker" ).datepicker({
        /*changeMonth: true,
        changeYear: true,
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
        weekHeader: 'Sem.',
        dateFormat: 'dd/mm/yy',*/
    //});

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