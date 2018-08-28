function showList(id) {
    $.ajax({
        url: Routing.generate('referent_show', { id: id }),
        cache: true,
        dataType: 'json',
        beforeSend: function () {
            $('#liste_consultant').empty()
        },
        success: function (response) {
            template = response;
            $('#liste_consultant').html(template)
        }
    });
}

function removeConsultant(idRef, idC){
    $.ajax({
        url: Routing.generate('referent_remove', { idReferent: idRef, idConsultant: idC }),
        cache: true,
        dataType: 'json',
        beforeSend: function () {

        },
        success: function (response) {
            showList(response)
        }
    });
}

$(document).on('submit', 'form#form_add_consultant_referent', function (e) {
    e.preventDefault()

    form = $('#form_add_consultant_referent')

    $.ajax({
        url: $(form).attr('action'), // le nom du fichier indiqué dans le formulaire
        type: $(form).attr('method'), // la méthode indiquée dans le formulaire (get ou post)
        data: $(form).serialize(),
        cache: true,
        dataType: 'json',
        beforeSend: function () {
            $('#addConsultantModal').modal('toggle');
        },
        success: function (response) {
            showList(response)
        }
    });
    return false
})