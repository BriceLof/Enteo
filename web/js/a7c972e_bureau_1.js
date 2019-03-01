$(window).load(function () {
    getVille($("#bureau_code_postal"), $("#bureau_ville_select"));
    showEntheorField($("#bureau_enabledEntheor"));
    $("form[name='bureau']").validate({
        rules: {
            "bureau[code_postal]": {
                minlength: 5
            },
        },
        messages: {
            "bureau[code_postal]": {
                minlength: function () {
                    return ['Erreur sur le Code Postal'];

                }
            }
        }
    });
});
function getVille(el, target) {
    cp = $(el).val();
    if (cp.length == 5) {
        $.ajax({
            type: 'get',
            url: Routing.generate("application_plateforme_get_ville_ajax", {departement: cp}),
            beforeSend: function () {
                $(target).empty();
            },
            success: function (data) {
                for (var i = 0; i < data.villes.length; i++) {
                    $(target).append($("<option/>", {
                        value: data.villes[i].id,
                        text: data.villes[i].nom
                    }));
                }
            }
        })
    }
}

function showEntheorField(el) {
    if ($(el).val() == 0){
        $(".site_entheor").hide();
    }else{
        $(".site_entheor").show();
    }
}



