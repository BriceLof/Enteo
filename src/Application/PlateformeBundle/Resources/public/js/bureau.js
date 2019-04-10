$(window).load(function () {
    getVille($("#bureau_code_postal"), $("#bureau_ville_select"));
    showEntheorField($("#bureau_enabledEntheor"));
    $("form[name='bureau']").validate({
        rules: {
            "bureau[code_postal]": {
                minlength: 5
            },
            "bureau[banner_image]": {
                required: false,
                accept: "image/*"
            },
            "bureau[first_image]": {
                required: false
            },
            "bureau[second_image]": {
                required: false
            },
            "bureau[third_image]": {
                required: false
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

    jQuery.extend(jQuery.validator.messages, {
        required: "Ce champs ne peut être vide.",
        remote: "Please fix this field.",
        email: "Please enter a valid email address.",
        url: "Please enter a valid URL.",
        date: "Please enter a valid date.",
        dateISO: "Please enter a valid date (ISO).",
        number: "Please enter a valid number.",
        digits: "Please enter only digits.",
        creditcard: "Please enter a valid credit card number.",
        equalTo: "Please enter the same value again.",
        accept: "Ce fichier n'est pas valide.",
        maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
        minlength: jQuery.validator.format("Please enter at least {0} characters."),
        rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
        range: jQuery.validator.format("Please enter a value between {0} and {1}."),
        max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
        min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
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

/**
 * cette fonction permet de voir l'apercu d'une image avant de l'uploader
 * @param input
 */
function showImage(input, target) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(target).css({
                backgroundImage : 'url('+e.target.result+')'
            });
        };
        reader.readAsDataURL(input.files[0]);
    }
}




