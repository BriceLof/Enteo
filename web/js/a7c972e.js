<<<<<<< HEAD
;$(window).load(function(){console.log("Infos disponibles ")});$(function(){$(".villeAjax option:first-child").val("");$(".villeAjax option:first-child").text("");$(".villeAjax").attr("disabled","disabled");$(".codePostalInputForAjax").keyup(function(){$(".villeAjax").attr("disabled","disabled");cp=$(this).val();if(cp.length==5){console.log("je lance l'ajax");$.ajax({type:"get",url:Routing.generate("application_plateforme_get_ville_ajax",{departement:cp}),beforeSend:function(){console.log("ça charge");$(".block_info_chargement").show()}}).done(function(l){$(".block_info_chargement").hide();$(".villeAjax option").remove();$(".villeAjax").removeAttr("disabled");for(var e=0;e<l.villes.length;e++){$(".villeAjax").append("<option value="+l.villes[e].id+">"+l.villes[e].nom+"</option>")}})}});$(".modifierBureau").click(function(){bureauID=$(this).attr("data-bureau-id");cp=$(".myModalBureauModifier"+bureauID+" #bureau_codePostalHidden").val();$(".myModalBureauModifier"+bureauID+" #bureau_code_postal").val(cp);idVille=$(".myModalBureauModifier"+bureauID+" #bureau_idVilleHidden").val();if(cp.length==5){console.log("je lance l'ajax");$.ajax({type:"get",url:Routing.generate("application_plateforme_get_ville_ajax",{departement:cp}),beforeSend:function(){console.log("ça charge");$(".myModalBureauModifier"+bureauID+" .block_info_chargement").show()}}).done(function(l){$(".myModalBureauModifier"+bureauID+" .block_info_chargement").hide();$(".myModalBureauModifier"+bureauID+" .villeAjax option").remove();$(".myModalBureauModifier"+bureauID+" .villeAjax").removeAttr("disabled");for(var e=0;e<l.villes.length;e++){if(idVille==l.villes[e].id)$(".myModalBureauModifier"+bureauID+" .villeAjax").append("<option selected data-cp="+l.villes[e].cp+" value="+l.villes[e].id+">"+l.villes[e].nom+"</option>");else $(".myModalBureauModifier"+bureauID+" .villeAjax").append("<option data-cp="+l.villes[e].cp+" value="+l.villes[e].id+">"+l.villes[e].nom+"</option>")}})}})});
=======
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



>>>>>>> 34438da46a4f4e5bb494265bdaa5eb4c845f557c
