$(window).load(function() {
    console.log("Infos disponibles ");
});
$(function(){
    //----------- Remplir ville en Ajax en indiquant le département

    // vider le premier champs qui est un vrai objet ville  (Uniquement sur la page de création d'un utilisateur)
    // (combine de ma part pour faire accepter mes villes ensuite via l'ajax)

    $(".villeAjaxBeneficiaire option:first-child").val("")
    $(".villeAjaxBeneficiaire option:first-child").text("")
    $(".villeAjaxBeneficiaire").attr("disabled", "disabled")

    $(".codePostalInputForAjaxBeneficiaire").keyup(function(){
        $(".villeAjaxBeneficiaire").attr("disabled", "disabled")
        cp = $(this).val();
        if(cp.length == 5)
        {
            console.log("je lance l'ajax");
            $.ajax({
                type: 'get',
                url: Routing.generate("application_plateforme_get_ville_ajax", { departement: cp }),
                beforeSend: function(){
                    console.log('ça charge');
                    $(".villeAjaxBeneficiaire option").remove();
                    //$(".block_info_chargement").show();
                }
            })
            .done(function(data) {
                //$(".block_info_chargement").hide();
                $(".villeAjaxBeneficiaire").removeAttr("disabled");
                for(var i = 0; i < data.villes.length; i++)
                {
                    $(".villeAjaxBeneficiaire").append("<option value="+data.villes[i].id+">"+data.villes[i].nom+"</option>")
                }
            });
        }else{
            $(".villeAjaxBeneficiaire option").remove();
            $(".villeAjaxBeneficiaire").removeAttr("disabled");
        }
    });

    // Pour la modification d'un beneficiaire, on doit cette fois ci récupérer le code postal de sa ville
    if($("#beneficiaire_codePostalHiddenBeneficiaire").length == 1)
    {
        cp      = $("#beneficiaire_codePostalHiddenBeneficiaire").val();
        idVille = $("#beneficiaire_idVilleHiddenBeneficiaire").val();
        if(cp.length == 5)
        {
            console.log("je lance l'ajax");
            $.ajax({
                type: 'get',
                url: Routing.generate("application_plateforme_get_ville_ajax", { departement: cp }),
                beforeSend: function(){
                    console.log('ça charge');
                    $(".block_info_chargement").show();
                }
            })
                .done(function(data) {
                    $(".block_info_chargement").hide();
                    $(".villeAjaxBeneficiaire option").remove()
                    $(".villeAjaxBeneficiaire").removeAttr("disabled")
                    for(var i = 0; i < data.villes.length; i++)
                    {
                        if(idVille == data.villes[i].id)
                            $(".villeAjaxBeneficiaire").append("<option selected data-cp="+data.villes[i].cp+" value="+data.villes[i].id+">"+data.villes[i].nom+"</option>")
                        else
                            $(".villeAjaxBeneficiaire").append("<option data-cp="+data.villes[i].cp+" value="+data.villes[i].id+">"+data.villes[i].nom+"</option>")
                    }
                });
        }
    }
});

