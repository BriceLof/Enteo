$(window).load(function() {
    console.log("Infos disponibles ");
});
$(function(){    
    //----------- Remplir ville en Ajax en indiquant le département
    
    // vider le premier champs qui est un vrai objet ville  (Uniquement sur la page de création d'un utilisateur)
    // (combine de ma part pour faire accepter mes villes ensuite via l'ajax)

    $(".villeAjax option:first-child").val("")
    $(".villeAjax option:first-child").text("") 
    $(".villeAjax").attr("disabled", "disabled") 

    $(".codePostalInputForAjax").keyup(function(){
        $(".villeAjax").attr("disabled", "disabled") 
        cp = $(this).val();
        if(cp.length == 5)
        {   
            console.log("je lance l'ajax");
            $.ajax({
                type: 'get',
                url: Routing.generate("application_plateforme_get_ville_ajax", { departement: cp }),
                beforeSend: function(){
                    console.log('ça charge')
                    $(".block_info_chargement").show();
                }
            })
            .done(function(data) {
                $(".block_info_chargement").hide();
                $(".villeAjax option").remove()
                $(".villeAjax").removeAttr("disabled") 
                for(var i = 0; i < data.villes.length; i++)
                {
                    $(".villeAjax").append("<option value="+data.villes[i].id+">"+data.villes[i].nom+"</option>")
                }
            });
        }
    })
    
    // Pour la modification d'un bureau, on doit cette fois ci récupérer le code postal de sa ville 
    $(".modifierBureau").click(function(){
        // etant donné qu'il y a plusieurs form affiché, j'ai mis un data attribute bureau id pour les différencier 
        bureauID = $(this).attr('data-bureau-id')
        cp = $(".myModalBureauModifier"+bureauID+" #bureau_codePostalHidden").val();
        // rempli le champ code postal 
        $(".myModalBureauModifier"+bureauID+" #bureau_code_postal").val(cp);

        idVille = $(".myModalBureauModifier"+bureauID+" #bureau_idVilleHidden").val();
        if(cp.length == 5)
        {   
            console.log("je lance l'ajax");
            $.ajax({
                type: 'get',
                url: Routing.generate("application_plateforme_get_ville_ajax", { departement: cp }),
                beforeSend: function(){
                    console.log('ça charge')
                    $(".myModalBureauModifier"+bureauID+" .block_info_chargement").show();
                }
            })
            .done(function(data) {
                $(".myModalBureauModifier"+bureauID+" .block_info_chargement").hide();
                $(".myModalBureauModifier"+bureauID+" .villeAjax option").remove()
                $(".myModalBureauModifier"+bureauID+" .villeAjax").removeAttr("disabled") 
                for(var i = 0; i < data.villes.length; i++)
                {
                    if(idVille == data.villes[i].id)
                        $(".myModalBureauModifier"+bureauID+" .villeAjax").append("<option selected data-cp="+data.villes[i].cp+" value="+data.villes[i].id+">"+data.villes[i].nom+"</option>")
                    else
                        $(".myModalBureauModifier"+bureauID+" .villeAjax").append("<option data-cp="+data.villes[i].cp+" value="+data.villes[i].id+">"+data.villes[i].nom+"</option>")
                }
            });
        }
         
    })
});

