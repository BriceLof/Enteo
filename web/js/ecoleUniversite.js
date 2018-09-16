/**
 * datepicker sur les input date
 */
$(function () {
    $(".accompagnementDate").datepicker({
        dateFormat: 'dd/mm/yy',
        yearRange: '2016:2025',
        firstDay: 1,
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
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.'
    });
});

$("#form_date_ecole_universite").submit(function (e) {
    e.preventDefault();
    $.ajax({
        url: Routing.generate("ecole_universite_show"),
        type: $(this).attr('method'),
        data: $(this).serialize(),
        dataType: 'json',
        beforeSend: function () {
            $('#ecole_universite_body').empty();
        },
        success: function (data) {
            template = data;

            $('#ecole_universite_body').html(template);
        }
    });
})

function exportCsv() {
    document.export_ecole_universite.action = Routing.generate('ecole_universite_export');
    document.export_ecole_universite.submit();
    document.export_ecole_universite.action = Routing.generate('ecole_universite_show');
}

