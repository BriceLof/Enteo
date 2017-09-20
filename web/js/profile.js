/**
 * cette fonction permet de voir l'apercu d'une image avant de l'uploader
 * @param input
 */
function showImage(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image_profile_form').css({
                backgroundImage : 'url('+e.target.result+')'
            });
        };
        reader.readAsDataURL(input.files[0]);
    }
}
$('#application_usersbundle_image_image').on('change', function () {
    showImage(this);
});

/**
 * cette fonction permet d'ajouter un document
 * c'est gérer en js parce qu'on peut ajouter plusieurs documents
 *
 */
$(document).ready(function() {
    var html = '<div style="cursor: pointer" id="add_dates"><span>Ajouter une Date</span></div>';
    var container = $('div#application_usersbundle_statut_consultant_dates');
    container.append(html);
    var addLink = $('#add_dates');
    count = container.find(':input').length;

    addLink.click(function(e) {
        e.preventDefault();
        var newWidget = container.attr('data-prototype');
        newWidget = newWidget.replace(/__name__/g, count+1);
        count++;
        addLink.before(newWidget);
    });

    $(document).on('focus',".date", function() {
        $(this).datepicker({
            // showOn: "button",
            // buttonImage: "images/calendar.gif",
            buttonImageOnly: true,
            dateFormat: 'dd/mm/yy',
            yearRange: '+0:+5',
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
    })
});

/**
 * function qui permet d'afficher ou de supprimer dinamiquement les dates dans suivi de formation entheor
 *
 */
(function () {

    element = $('#application_usersbundle_statut_consultant_detail_2');
    inputDate = $('#application_usersbundle_statut_consultant_dates');

    suiviFormation(element, inputDate);

    element.on('change',function() {
        suiviFormation(this, $('#application_usersbundle_statut_consultant_dates'));
    });

    function suiviFormation(el, hide) {
        if($(el).is(':checked')) {
            hide.css('display', 'block');
        }else{
            hide.css('display', 'none')
        }
    }
})();