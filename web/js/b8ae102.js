;$(function(){$('.btn_formulaire_add_news').click(function(){beneficiaire_id=$(this).attr('id');$('.modal-dialog form').remove();formDuBeneficiaire='#block_formulaire_ajout_new_'+beneficiaire_id;$(formDuBeneficiaire+' .modal-content').append($('#block_formulaire_add_news').html());$(formDuBeneficiaire+' #hidden_beneficiaire_id').val(beneficiaire_id);var t=$(formDuBeneficiaire+' .statutIDCurrent').val();if(t==6)var i=Number(t)+Number(4);else var i=Number(t)+Number(1);$(formDuBeneficiaire+' .statut').val(i);ajaxFormNews(i,!0);$(formDuBeneficiaire+' .statut').change(function(){var t=$('option:selected').val();$('.detailStatut').attr('disabled','disabled');ajaxFormNews(t,!0)})});if($('#tabNews').length!=0){$('.link_formulaire_add_news').click(function(){$('.detailStatut').attr('disabled','disabled');var t=$('#statutIDCurrentBlockStatut').val();$('#statut #newsForm .statut').val(t);ajaxFormNews(t,!0)});$('#newsForm .statut').change(function(){var t=$(this).children('option:selected').val();$('.detailStatut').attr('disabled','disabled');ajaxFormNews(t,!0)});var t=$('.statutIDCurrentSuiviAd').val();$('#suiviAdministratifNewForm .statutSuiviAd').val(t);ajaxFormNews(t,!1);$('.updateSuivi').click(function(){var t=$(this).attr('data-boucle');$('#myModalSuiviAdministratifModify'+t+' .detailStatutSuiviAd').attr('disabled','disabled');var e=$('.statutIDCurrentSuiviAdUpdate'+t).val(),i=$('.detailStatutIDCurrentSuiviAdUpdate'+t).val();ajaxFormNews(e,!1,i)});$('#suiviAdministratifNewForm .statutSuiviAd').change(function(){var t=$(this).children('option:selected').val();$('#suiviAdministratifNewForm .detailStatutSuiviAd').attr('disabled','disabled');ajaxFormNews(t)});$('#suiviAdministratifEditForm .statutSuiviAd').change(function(){var t=$(this).children('option:selected').val();$('#suiviAdministratifEditForm .detailStatutSuiviAd').attr('disabled','disabled');ajaxFormNews(t)})};url=window.location.href;console.log($('.typeUtilisateur a').text());if(url.indexOf('user/type/admin')!=-1){$('.typeUtilisateur a').removeClass('active');$('.typeUtilisateur .itemAdmin').addClass('active')}
else if(url.indexOf('user/type/commercial')!=-1){$('.typeUtilisateur a').removeClass('active');$('.typeUtilisateur .itemCommercial').addClass('active')}
else if(url.indexOf('user/type/gestion')!=-1){$('.typeUtilisateur a').removeClass('active');$('.typeUtilisateur .itemGestion').addClass('active')}
else if(url.indexOf('user/type')!=-1){$('.typeUtilisateur a').removeClass('active');$('.typeUtilisateur .itemConsultant').addClass('active')}});function ajaxFormNews(t,i,e){$.ajax({type:'get',url:Routing.generate('application_plateforme_detail_statut',{idStatut:t}),beforeSend:function(){console.log('ça charge');$('.block_info_chargement').show()}}).done(function(a){if(i==!0){$('.detailStatut').removeAttr('disabled');$('.detailStatut').html('')}
else{$('#suiviAdministratifNewForm .detailStatutSuiviAd').removeAttr('disabled');$('#suiviAdministratifEditForm .detailStatutSuiviAd').removeAttr('disabled');$('#suiviAdministratifNewForm .detailStatutSuiviAd').html('');$('#suiviAdministratifEditForm .detailStatutSuiviAd').html('')};$('.block_info_chargement').hide();for(var t=0;t<a.details.length;t++){console.log(a.details[t].detail);if($('#tabNews').length!=0){if(i==!0){$('#newsForm .detailStatut').append('<option value='+a.details[t].id+'>'+a.details[t].detail+'</option>')}
else{$('#suiviAdministratifNewForm .detailStatutSuiviAd').append('<option value='+a.details[t].id+'>'+a.details[t].detail+'</option>');if(a.details[t].id==e)$('#suiviAdministratifEditForm .detailStatutSuiviAd').append('<option selected value='+a.details[t].id+'>'+a.details[t].detail+'</option>');else $('#suiviAdministratifEditForm .detailStatutSuiviAd').append('<option value='+a.details[t].id+'>'+a.details[t].detail+'</option>')}}
else{$(formDuBeneficiaire+' .detailStatut').append('<option value='+a.details[t].id+'>'+a.details[t].detail+'</option>')}}})};
;$(window).load(function(){$("#overlayB").fadeOut("1500");console.log("Infos disponibles ")});$(function(){console.log("Recupération de vos infos");typeUtilisateur=$("#application_usersbundle_users_typeUserHidden").val();if(typeUtilisateur!="Administrateur")$(".block_form_role_user").hide();$(".block_form_format_presence").hide();$(".role_user").click(function(){console.log($(this).val());switch($(this).val()){case"ROLE_CONSULTANT":$(".calendar_uri")[this.checked?"show":"hide"]();$(".calendar_id")[this.checked?"show":"hide"]();$(".block_form_format_presence")[this.checked?"show":"hide"]();break}});$(".villeAjax option:first-child").val("");$(".villeAjax option:first-child").text("");$(".villeAjax").attr("disabled","disabled");$(".departementInputForAjax").keyup(function(){$(".villeAjax").attr("disabled","disabled");nombreOptions=$(".villeAjax  option").length;dpt=$(this).val();if(dpt.length==2){console.log("je lance l'ajax");$.ajax({type:"get",url:Routing.generate("application_plateforme_get_ville_ajax",{departement:dpt}),beforeSend:function(){console.log("ça charge");$(".block_info_chargement").show()}}).done(function(l){$(".block_info_chargement").hide();$(".villeAjax option").remove();$(".villeAjax").removeAttr("disabled");for(var e=0;e<l.villes.length;e++){$(".villeAjax").append("<option value="+l.villes[e].id+">"+l.villes[e].nom+"</option>")}})}});if($("#application_usersbundle_users_codePostalHidden").length==1){cp=$("#application_usersbundle_users_codePostalHidden").val();idVille=$("#application_usersbundle_users_idVilleHidden").val();if(cp.length==5){console.log("je lance l'ajax");$.ajax({type:"get",url:Routing.generate("application_plateforme_get_ville_ajax",{departement:cp.substr(0,2)}),beforeSend:function(){console.log("ça charge");$(".block_info_chargement").show()}}).done(function(l){$(".block_info_chargement").hide();$(".villeAjax option").remove();$(".villeAjax").removeAttr("disabled");for(var e=0;e<l.villes.length;e++){if(idVille==l.villes[e].id)$(".villeAjax").append("<option selected data-cp="+l.villes[e].cp+" value="+l.villes[e].id+">"+l.villes[e].nom+"</option>");else $(".villeAjax").append("<option data-cp="+l.villes[e].cp+" value="+l.villes[e].id+">"+l.villes[e].nom+"</option>")}})}}});