$(function(){
    
    // HOME : formulaire ajout d'une news
    $(".btn_formulaire_add_news").click(function(){
        
        beneficiaire_id = $(this).attr('id')
        //console.log(beneficiaire_id);
        if($("#form_add_news_"+beneficiaire_id).css('display')== "block"){
            $("#form_add_news_"+beneficiaire_id+" form").remove();
            $("#form_add_news_"+beneficiaire_id).css('display','none');
        }
        else{
            $("#form_add_news_"+beneficiaire_id).css('display','block');
            $("#form_add_news_"+beneficiaire_id+" .hidden_beneficiaire_id").val(beneficiaire_id)
            $("#block_formulaire_add_news #hidden_beneficiaire_id").val(beneficiaire_id)
            $("#form_add_news_"+beneficiaire_id).append($("#block_formulaire_add_news").html())
            
            // Ajax pour récupérer les details status dans l'ajout d'une news
            $(".statut").change(function(){
                var statutId = $("option:selected").val()
                
                $.ajax({
                    type: 'get',
                    url: Routing.generate("application_plateforme_detail_statut", { idStatut: statutId }),
                    beforeSend: function(){
                        console.log('ça charge')
                        //$(".detailStatut").append(
                    }
                }).done(function(data) {
                    console.log(data)
                    $(".detailStatut").html("");
                    for(var i = 0; i < data.details.length; i++)
                    {
                        console.log(data.details[i].detail);
                       
                        $(".detailStatut").append("<option value="+data.details[i].id+">"+data.details[i].detail+"</option>")
                    }
                });
            });
        }
    });

    // Page gestion utilisateur 
    /*$(".list-group-item a").click(function(){
        $(this).addClass("active");
    });*/
});

