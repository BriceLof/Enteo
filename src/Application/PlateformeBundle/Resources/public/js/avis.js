$(function () {
    // --------------------------- Page facture : filtre de recherche
    $(".beneficiaireSearchField").keyup(function () {
        nom = $(this).val();
        if (nom.length >= 3) {
            $.ajax({
                type: 'get',
                url: Routing.generate("application_search_ajax_beneficiaire", {string: nom}),
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
                        $("#select_beneficiaire_ajax").append("<option value=" + tab[i].id + ">" + tab[i].nom +" "+ tab[i].prenom + " ("+ tab[i].id +")</option>")
                    }
                });
        } else {
            $("#select_beneficiaire_ajax option").remove()
        }
    });


});



