$(document).ready(function () {
    if(document.getElementById('account')) {
        rechercheVille($("#facturation_code_postal"), $("#facturation_ville"))
        $("#facturation_code_postal").on('keyup', function () {
            rechercheVille(this, $("#facturation_ville"))
        })

        formFacturation($("#facturation_type"))
        $("#facturation_type").on('change', function () {
            formFacturation(this)
        })

        $("form[name='facturation']").validate({
            errorElement: 'span',
            errorPlacement: function (error, element) {
            }
        })

        afficheAutreFonction($("#facturation_representantLegalFonction"))
        $("#facturation_representantLegalFonction").on('change', function () {
            afficheAutreFonction(this)
        })
    }
})

function afficheAutreFonction(el) {
    if ($(el).val() == 'autre'){
        $("#autre_fonction").css('display','inherit');
        $("#facturation_intitule").attr('required',true)
    }else{
        $("#autre_fonction").css('display','none');
        $("#facturation_intitule").attr('required',false)
    }
}

function rechercheVille(dpt, villeCible) {
    console.log(villeCible.val())
    cp = $(dpt).val();
    if (cp.length == 5) {
        $.ajax({
            type: 'get',
            url: Routing.generate("application_plateforme_get_ville_ajax", {departement: cp}),
            beforeSend: function () {
                villeCible.empty();
            }
        })
            .done(function (data) {
                for (var i = 0; i < data.villes.length; i++) {
                    $(villeCible).append("<option value=" + data.villes[i].id + ">" + data.villes[i].nom + "</option>")
                }
            });
    } else {
        villeCible.empty()
    }
}

function formFacturation(el) {
    id = $(el).attr('id')
    val = $('#' + id + ' option:selected').val()
    console.log(val)
    form = $(el).parents('form')
    champs = $(form).find('.modal-body').children()
    $(champs).each(function (i) {
        if ($(this).hasClass(val) || $(this).hasClass('facturation_type')){
            $(this).css('display', 'block')
            $(this).find(".form-control").attr('required', 'required')
            $(this).find(".not_required").attr('required', false)
        }else{
            $(this).css('display', 'none')
            $(this).find(".form-control").attr('required', false)
            $(this).find("input[type='file']").attr('required', false)
        }
    })
}