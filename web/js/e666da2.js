$(function(){
   $(".btn_formulaire_add_news").click(function(){
       beneficiaire_id = $(this).attr('id')
       if($("#form_add_news_"+beneficiaire_id).css('display')== "block")
            $("#form_add_news_"+beneficiaire_id).hide()
       else
            $("#form_add_news_"+beneficiaire_id).show();
       $("#form_add_news_"+beneficiaire_id+" .hidden_beneficiaire_id").val(beneficiaire_id)
       $("#block_formulaire_add_news #hidden_beneficiaire_id").val(beneficiaire_id)
       $("#form_add_news_"+beneficiaire_id).append($("#block_formulaire_add_news").html())
   })
});

