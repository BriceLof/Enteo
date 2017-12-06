$(function () {

    $( ".datePicker" ).datepicker({
        changeMonth: true,
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
        dateFormat: 'dd/mm/yy',
    });


    if($(".statutPaiementFacture").val() == "paid" || $(".statutPaiementFacture").val() == "partiel"){
        statePaiement = $(".statutPaiementFacture").val()
        paiementFieldShowHidde(statePaiement)
    }
    $(".statutPaiementFacture").change(function () {
        statePaiement = $(this).children("option:selected").val()
        paiementFieldShowHidde(statePaiement)
    });

});

function paiementFieldShowHidde(statePaiement)
{
    if (statePaiement == 'paid' || statePaiement == 'partiel' ) {
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
}