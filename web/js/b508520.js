;$(window).load(function(){console.log('Infos disponibles ')});$(function(){$('.villeAjaxBeneficiaire option:first-child').val('');$('.villeAjaxBeneficiaire option:first-child').text('');$('.villeAjaxBeneficiaire').attr('disabled','disabled');$('.codePostalInputForAjaxBeneficiaire').keyup(function(){$('.villeAjaxBeneficiaire').attr('disabled','disabled');cp=$(this).val();if(cp.length==5){console.log('je lance l\'ajax');$.ajax({type:'get',url:Routing.generate('application_plateforme_get_ville_ajax',{departement:cp}),beforeSend:function(){console.log('ça charge');$('.villeAjaxBeneficiaire option').remove()}}).done(function(e){$('.villeAjaxBeneficiaire').removeAttr('disabled');for(var i=0;i<e.villes.length;i++){$('.villeAjaxBeneficiaire').append('<option value='+e.villes[i].id+'>'+e.villes[i].nom+'</option>')}})}
else{$('.villeAjaxBeneficiaire option').remove();$('.villeAjaxBeneficiaire').removeAttr('disabled')}});if($('#beneficiaire_codePostalHiddenBeneficiaire').length==1){cp=$('#beneficiaire_codePostalHiddenBeneficiaire').val();idVille=$('#beneficiaire_idVilleHiddenBeneficiaire').val();if(cp.length==5){console.log('je lance l\'ajax');$.ajax({type:'get',url:Routing.generate('application_plateforme_get_ville_ajax',{departement:cp}),beforeSend:function(){console.log('ça charge');$('.block_info_chargement').show()}}).done(function(e){$('.block_info_chargement').hide();$('.villeAjaxBeneficiaire option').remove();$('.villeAjaxBeneficiaire').removeAttr('disabled');for(var i=0;i<e.villes.length;i++){if(idVille==e.villes[i].id)$('.villeAjaxBeneficiaire').append('<option selected data-cp='+e.villes[i].cp+' value='+e.villes[i].id+'>'+e.villes[i].nom+'</option>');else $('.villeAjaxBeneficiaire').append('<option data-cp='+e.villes[i].cp+' value='+e.villes[i].id+'>'+e.villes[i].nom+'</option>')}})}}});(function(){var o=document.getElementById('proposition_pdf'),l=document.getElementById('beneficiaire_proposition_adresse'),t=document.getElementById('accompagnement_proposition_heure'),n=document.getElementById('accompagnement_proposition_dateDebut'),a=document.getElementById('accompagnement_proposition_dateFin'),e='champs manquant : <br>';o.addEventListener('click',function(i){if(l.value==''||n.value=='0'||a.value=='0'||t.value==''){if(l.value==''){e+='- l\'adresse du bénéficiaire <br>'};if(n.value=='0'){e+='- la date de début de l\'accompagnement <br>'};if(a.value=='0'){e+='- la date de fin de l\'accompagnement <br>'};if(t.value==''){e+='- le nombre d\'accompagnement en heures <br>'};$('body').append('<div id="dataConfirmModal2" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de verifier</h3></div><div class="modal-body"><p>'+e+'</p></div><div class="modal-footer"><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Fermer</button></div></div></div></div>');$('#dataConfirmModal2').find('.modal-body').text($(this).attr('data-confirm'));$('#dataConfirmModal2').modal({show:!0});i.preventDefault()}
else{}});$('.type_employeur').on('change',function(){tdElement=$('#tdElement');element=tdElement.find('#beneficiaire_employeur_organisme');if($(this).val()=='OPCA'){tdElement.css('display','table-row');$name='OPCA';i($name,element)}
else{if($(this).val()=='OPACIF'){tdElement.css('display','table-row');$name='OPACIF';i($name,element)}
else{tdElement.css('display','none');element.empty()}}});function i(e,i){$.ajax({url:Routing.generate('application_opca_opacif_financeur',{'nom':e}),cache:!0,type:'get',dataType:'json',beforeSend:function(){i.empty();console.log('ça charge')},success:function(e){$.each(JSON.parse(e),function(a,e){if($(i).attr('value')==e){i.append('<option value='+e+' selected = "selected">'+e+'</option>')}
else{i.append('<option value='+e+'>'+e+'</option>')}});console.log('fini')}})};$(function(){$('#beneficiaire_employeur_organisme').each(function(){var e=$('<select>');$.each(this.attributes,function(i,a){$(e).attr(a.name,a.value)});$(this).replaceWith(e)})});$(function(){$a=1;$('.contact_employeur').children().children().children().each(function(){if($(this).get(0).tagName=='LABEL'){$(this).css('display','none');$a++}})});$(function(){$('select#beneficiaire_employeur_organisme').each(function(){tdElement=$('#tdElement');element=$(this);console.log($(this).val());if($('#beneficiaire_employeur_type').val()=='OPCA'){tdElement.css('display','table-row');$name='OPCA';i($name,element)}
else{if($('#beneficiaire_employeur_type').val()=='OPACIF'){tdElement.css('display','table-row');$name='OPACIF';i($name,element)}}})})})();