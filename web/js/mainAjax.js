$("document").ready(function () {
    //affichage de news dans la partie bénéficiaire
    $( function() {
        $( "#tabNews" ).tabs();
    } );

    $( function () {
        $(".form-control .error").css('border','1px solid red');
    });


    jQuery.extend(jQuery.validator.messages, {
        required: "Ce champs ne peut être vide",
        remote: "votre message",
        email: "adresse Email incorrect",
        url: "Url incorrect",
        date: "Date non valide ( format : dd/mm/yyyy )",
        dateISO: "votre message",
        number: "veuillez ne rentrer que des chiffres",
        digits: "longeur incorrect",
        creditcard: "Numéro de Carte incorrect",
        equalTo: "votre message",
        accept: "votre message",
        maxlength: jQuery.validator.format("la taille maximum est de {0} caractéres."),
        minlength: jQuery.validator.format("la faille minimum est de {0} caractéres."),
        rangelength: jQuery.validator.format("votre message  entre {0} et {1} caractéres."),
        range: jQuery.validator.format("votre message  entre {0} et {1}."),
        max: jQuery.validator.format("votre message  inférieur ou égal à {0}."),
        min: jQuery.validator.format("votre message  supérieur ou égal à {0}.")
    });



    //datepicker
    $( function() {
        $("form input.date").datepicker({
            dateFormat: 'dd/mm/yy',
            firstDay:1,
            changeMonth: true,
            changeYear: true,
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.'
        });
    });

    jQuery.validator.addMethod("greaterThan",
        function(value, element, params) {

            if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) > new Date($(params).val());
            }

            return isNaN(value) && isNaN($(params).val())
                || (Number(value) > Number($(params).val()));
        },'La date de début doit être inferieur à la date de fin');

    jQuery.validator.addMethod(
        "tarif",
        function(value, element, regexp) {
            if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
            else if (regexp.global)
                regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        },"erreur sur le tarif (format : xxx.xx )"
    );

    jQuery.validator.addMethod(
        "tel",
        function(value, element, regexp) {
            if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
            else if (regexp.global)
                regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        },"ce numéro de téléphone n'est pas valide"
    );

    jQuery.validator.addMethod(
        "numSecu",
        function(value, element, regexp) {
            if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
            else if (regexp.global)
                regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        },"ce numéro de sécurité sociale n'est pas valide"
    );



    jQuery.validator.addMethod(
        "dateBR",
        function(value, element, regexp) {
            if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
            else if (regexp.global)
                regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        },"Date non valide ( format : dd/mm/yyyy )"
    );

    $.validator.addMethod(
        "australianDate",
        function(value, element) {
            // put your own logic here, this is just a (crappy) example
            return value.match(/^(0?[1-9]|[12][0-9]|3[0-1])[/., -](0?[1-9]|1[0-2])[/., -](19|20)?\d{2}$/);
        },
        "Date non valide ( format : dd/mm/yyyy )."
    );

    jQuery.validator.methods["date"] = function (value, element) { return true; };

    jQuery.validator.addMethod(
        "texte",
        function(value, element, regexp) {
            if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
            else if (regexp.global)
                regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        },"Verifiez votre saisie"
    );

    //validation jquery accompagnement
    $( function() {
        $("#accompagnementEditForm").validate({
            rules: {
                "accompagnement[opcaOpacif]":{
                    "required": false
                },
                "accompagnement[heure]":{
                    "required": false,
                    "number" : true
                },
                "accompagnement[tarif]":{
                    "required": false,
                    "tarif" : /^[1-9][0-9]+(\.?([0-9]?[1-9]|[1-9][0-9]?))?$/
                },
                "accompagnement[dateDebut]": {
                    "dateBR" : /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/
                },
                "accompagnement[dateFin]": {
                    "dateBR" : /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/,
                    "greaterThan" : "accompagnement[dateDebut]"
                }
            },
            errorElement: 'div'
        })
    });

    //validation jquery
    $( function() {
        $("#suiviAdministratifEditForm").validate({
            rules: {
                "suivi_administratif[qui]":{
                    "required": true
                },
                "suivi_administratif[quoi]":{
                    "required": true
                }
            },
            errorElement: 'div'
        })
    });

    $( function() {
        $("#newDocumentsForm").validate({
            rules: {
                "espace_documentaire[documents][0][file]":{
                    'extension': "jpeg|png|gif|pdf|x-pdf|jpg"
                }
            }
        })
    });
    //validation jquery
    $( function() {
        $("#projetEditForm").validate({
            rules: {
                "projet[experience]":{
                    "required": false
                },
                "projet[heureDif]":{
                    "required": false,
                    "number": true
                },
                "projet[heureCpf]":{
                    "required": false,
                    "number": true
                }
            },
            errorElement: 'div'
        })
    });

    //espace documentaire validate
    $( function() {
        $("#newDocumentsForm").validate({
            rules: {
                "espace_documentaire_documents_0_description":{
                    "required": true
                },
                "suivi_administratif[quoi]":{
                    "required": true
                }
            },
            errorElement: 'div'
        })
    });


    //validation jquery de la fiche beneficiaire
    $( function() {
        $("#ficheBeneficiaireForm").validate({
            rules: {
                "beneficiaire[dateConfMer]":{
                    "required": true
                },
                "beneficiaire[civiliteConso]":{
                    "required": true
                },
                "beneficiaire[nomConso]":{
                    "required": true
                },
                "beneficiaire[prenomConso]":{
                    "required": true
                },
                "beneficiaire[poste]":{
                    "required": false
                },
                "beneficiaire[encadrement]":{
                    "required": false
                },
                "beneficiaire[telConso]":{
                    "required": true,
                    "tel": /^0[1-8][0-9]{8}$/
                },
                "beneficiaire[tel2]":{
                    "required": false,
                    "tel": /^0[1-8][0-9]{8}$/
                },
                "beneficiaire[emailConso]":{
                    "required": true,
                    "email": true
                },
                "beneficiaire[email2]":{
                    "required": false,
                    "email": true
                },
                "beneficiaire[adresse]":{
                    "required": false
                },
                "beneficiaire[ville][zip]":{
                    "required": true
                },
                "beneficiaire[ville][nom]":{
                    "required": true
                },
                "beneficiaire[pays]":{
                    "required": true,
                    "texte": /^([a-z ]-?,?)+$/i
                },
                "beneficiaire[numSecu]":{
                    "required": false,
                    "numSecu": /^[12][0-9]{14}$/
                },
                "beneficiaire[dateNaissance]":{
                    "required": false
                }
            },
            errorElement: 'div'
        })
    });

    $(".cp").keyup(function () {
        if ($(this).val().length === 5){
            $.ajax({
                type: 'get',
                url: Routing.generate('application_search_ville', {cp : $(this).val()}),
                beforeSend: function(){
                    console.log('ça charge !');
                },
                success: function (data) {
                    var id = $(".ville").attr("id");
                    var name = $(".ville").attr("name");
                    $(".ville").replaceWith('<select id='+id+' name='+name+' class="ville form-control">');
                    $.each(data.ville, function(index,value){
                        $(".ville").append($('<option>',{value: value, text: value}));
                    });

                    console.log('ville ok');
                }
            })
        }else{
            $(".ville").val('');
        }
    });

    //ajax rechercher dans la page home
    // desactivation de l'ajax par demande de philippe pour plus de visibilité
    /**
    $("#ajaxForm").submit(function (e) {

        e.preventDefault();
        $.ajax({
            url: Routing.generate('application_list_beneficiaire'), // le nom du fichier indiqué dans le formulaire
            type: $(this).attr('method'), // la méthode indiquée dans le formulaire (get ou post)
            data: $(this).serialize(),
            cache: true,
            dataType: 'json',
            beforeSend: function () {

                var resultat = document.getElementById('content');
                resultat.innerHTML = "";
                resultat.innerHTML += '<div class="loading"></div>';

            },
            success : function (data) {

                var resultat = document.getElementById('content');

                resultat.innerHTML = "";
                var table = document.createElement('table');
                table.setAttribute('class',"table table-bordered");
                var tr = document.createElement('tr');
                var thNom = document.createElement('th');
                var thPrenom = document.createElement('th');
                thNom.innerHTML = 'nom';
                thPrenom.innerHTML = 'prenom';
                tr.appendChild(thNom);
                tr.appendChild(thPrenom);
                resultat.appendChild(table).appendChild(tr);

                $.each(JSON.parse(data),function (i, item) {

                    var lien = Routing.generate('application_show_beneficiaire', {id: item.id});

                    var tr2 = document.createElement('tr');
                    var tdNom = document.createElement('td');
                    var tdprenom = document.createElement('td');
                    tdNom.innerHTML = '<a href="'+lien+'" target="_blank">'+item.nomConso;
                    tdprenom.innerHTML = item.prenomConso;
                    tr2.appendChild(tdNom);
                    tr2.appendChild(tdprenom);
                    resultat.appendChild(table).appendChild(tr2);
                });
            }
        });
    });
     */
    /**
     $("#ajaxForm").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: Routing.generate('application_ajaxSearch_beneficiaire'), // le nom du fichier indiqué dans le formulaire
            type: $(this).attr('method'), // la méthode indiquée dans le formulaire (get ou post)
            data: $(this).serialize(),
            cache: true,
            dataType: 'html',
            beforeSend: function () {
                var resultat = document.getElementById('content');
                resultat.innerHTML = "";
                resultat.innerHTML += '<div class="loading"></div>';
            },
            success: function (response) {
                template = response;
                $('#content').html(template.html);
            }
        });
    });*/


   
    // =========================================================================== //
    // ================= Autocompletion Nom et Prenom beneficiaire =============== //
    // =========================================================================== //
    var urlautocompletion;
    switch(true){
        case (location.pathname == '/web/app_dev.php/agenda/evenements'):
            urlautocompletion = 'http://'+location.hostname+location.pathname.replace("agenda/evenements", "autocompletion"); // on appelle le script JSON
            break;
        default:
            urlautocompletion = 'http://'+location.hostname+location.pathname.replace("agenda", "autocompletion"); // on appelle le script JSON
            break;
    }
    
    // Autocompletion ville
    $("#dpttest").autocomplete({
        source: function($request, reponse){
            $.ajax({
                url: urlautocompletion,
                data : {
                    term : $('#dpttest').val(),
                    sentinel: 2
                },
                dataType : 'json', // on spécifie bien que le type de données est en JSON
                success : function(donnee){
                    var obj = $.parseJSON(donnee);
                    reponse($.map(obj, function (item) {
                        return {
                            value: function ()
                            {
                              if ($(this).attr('id') == 'dpttest')
                              {
                                 if(item.nom != undefined){
                                    return item.nom;
                                 }
                              }
                              else
                              {
                                  if(item.nom != undefined){
                                      // On met à jour les champs ville, bureau
                                      $('#dpttest').val(item.departementId); // departement
                                      $('#bureauRdv').val(item.nombureau); // bureau
                                      $('.bureauselect').val(item.nombureau); // bureau selectionner
                                      $("#villeh").val(item.nom); // ville
                                      $('#adresse').val(item.adresse); // adresse
                                      $('#adresseh').val(item.adresse); // adresse 
                                      $('#zip').val(item.cp); // code postal
                                      $('#ziph').val(item.cp); // code postal
                                      $('#historique_Enregistrer').removeAttr('disabled');
                                      return item.nom;
                                  }
                                  else{
                                      // on reinitialise tous les champs
                                      $('#dpttest').val(''); // departement
                                      $('#bureauRdv').val(); // bureau
                                      $('.bureauselect').val(-1); // bureau selectionner
                                      $("#villeh").val(); // ville
                                      $("#ville").val(); // ville
                                      $('#adresse').val(); // adresse
                                      $('#adresseh').val(); // adresse 
                                      $('#zip').val(); // code postal
                                      $('#ziph').val(); // code postal
                                      $('#historique_Enregistrer').attr('disabled','true');
                                      return 'Aucun Bureau trouvé dans ce departement';
                                  }
                              }
                            }
                        }
                    }));
                }
            });
        }
    });
    
    // Autocompletion nom
    $("#nomb").autocomplete({
        source : function(requete, reponse){ // les deux arguments représentent les données nécessaires au plugin
            $.ajax({
                url : urlautocompletion, // on appelle le script JSON
                data : {
                    term : $('#nomb').val(),
                    sentinel: 1
                },
                dataType : 'json', // on spécifie bien que le type de données est en JSON
                success : function(donnee){
                    var obj = $.parseJSON(donnee);
                    reponse($.map(obj, function (item) {
                        return {
                            value: function ()
                            {
                              if ($(this).attr('id') == 'nomb')
                              {
                                 if(item.nomConso != undefined){
                                    return item.nomConso;
                                 }
                              }
                              else
                              {
                                  if(item.prenomConso != undefined){
                                      $('#nomb').val(item.nomConso);
                                      $('#prenomb').val(item.prenomConso); // input visible
                                      $('#prenombe').val(item.prenomConso); // input hidden
                                      $('#historique_Enregistrer').removeAttr('disabled');
                                      $('#idbeneficiaire').val(item.id);
                                      return item.nomConso;
                                  }
                                  else{
                                      $('#nomb').val('');
                                      $('#prenomb').val(''); // input visible
                                      $('#prenombe').val(''); // input hidden
                                      $('#historique_Enregistrer').attr('disabled','true');
                                      $('#idbeneficiaire').val(-1);
                                      return 'Aucun Beneficiaire trouvé';
                                  }
                              }
                            }
                        }
                    }));
                }
            });
        }
    });
	
    // ==================================================================================== //
    // ========== Gestion de la date de fin [datedebut == datefin] ======================== //
    // ==================================================================================== //
    $("#historique_dateDebut_day").change(function(){
        // On met à jour le jour de datefin
        $("#historique_dateFin_day").val($("#historique_dateDebut_day").val());
    });
    $("#historique_dateDebut_month").change(function(){
        // On met à jour le mois de datefin
        $("#historique_dateFin_month").val($("#historique_dateDebut_month").val());
    });
    $("#historique_dateDebut_year").change(function(){
        // On met à jour l'année de datefin
        $("#historique_dateFin_year").val($("#historique_dateDebut_year").val());
    });

    // ==================================================================================== //
    // ======= Affichage des bureau, ville, adresse en fonction du departement choisi ===== //
    // ==================================================================================== //
    $('input:radio[name="typeRdv"]').change(function(){
            if ($(this).is(':checked') && $(this).val() == 'distanciel') {
                // On masque les bureau et adresse
                $('.bureau_v').css('display', 'none');
                // On supprime le required dans le champ bureau
                $('#bureauRdv').removeAttr('required');
                $('.letyperdv').val($(this).val());
				$("#dpttest").val("-1"); // initialisation pour eviter le blocage de la soumission du formulaire lors d'un rdv distanciel
            }
            else{
                $('.letyperdv').val($(this).val());
                // On affiche les bureau et adresse
                $('.bureau_v').css('display', 'inline-block');
                // On ajoute le required dans le champ bureau
                $('#bureauRdv').attr('required','true');
            }
    });
    if($('.calendrierconsultant').val() != undefined){
        // On vire les heures < 7 et > 20 dans heure_debut et heure_fin
        selectheuresd = $('#historique_heureDebut_hour option'); // heure debut
        selectheuresf = $('#historique_heureFin_hour option'); // heure fin
        for(j=0; j<selectheuresd.length; j++){
            if(j<7 || j>20){
                selectheuresd[j].remove(); // heure debut
                selectheuresf[j].remove(); // heure fin
            }
        }
        // Affichage du nom du consultant
        $('.nameconsultant').text($('.consultantC').val());
        // Affichage du calendrier dans le bloc
        $('.blocacalendar').append($('.calendrierconsultant').val());
        $('#calendarTitle').css('display','none !important'); // on masque le titre du calendrier
        // Affichage du formulaire 
        $('.formPagenda').css('display', 'inline-block');
        // modification de la largeur du calendrier
        $('.blocacalendar iframe').attr('width', '1150');
        $('.blocacalendar iframe').attr('height', '780');
        // Positionnement du bouton
        $('.formPagenda button').css('margin-left', '59%');
        $('#agendaForm').css({
            padding: '6px',
            'margin-top': '45px'
        });
    }
    
    // ================================================================ //
    // ========== Gestion des Heures dans l'ajout d'un RDV ============ //
    // ================================================================ //
    $('#historique_heureDebut_hour').change(function(){
        // On initialise l'heure de fin en fonction de l'heure de debut
        heure_debut = parseInt($('#historique_heureDebut_hour').val()); // On recupère l'heure de debut
        minute_debut = parseInt($('#historique_heureDebut_minute').val()); // On recupère l'heure de debut
        majheuredebut = heure_debut+1;
        $('#historique_heureFin_hour').val(majheuredebut); // Maj de l'heure de fin
        $('#historique_heureFin_minute').val(minute_debut); // Maj de la minute de fin
    });
    $('#historique_heureDebut_minute').change(function(){
        minute_debut = parseInt($('#historique_heureDebut_minute').val()); // On recupère l'heure de debut
        $('#historique_heureFin_minute').val(minute_debut); // Maj de la minute de fin
    });
});


// ---------------------------------------------- //
// ------------- Affichage pour Admin ----------- //
// ---------------------------------------------- //
$('#consultantC').change(function(){
    emptyelement('blocacalendar', 'class'); // Vider le bloc du calendrier
    if($('#consultantC').val() != ""){
        // On vire les heures < 7 et > 20 dans heure_debut et heure_fin
        selectheuresd = $('#historique_heureDebut_hour option'); // heure debut
        selectheuresf = $('#historique_heureFin_hour option'); // heure fin
        for(j=0; j<selectheuresd.length; j++){
            if(j<7 || j>20){
                selectheuresd[j].remove(); // heure debut
                selectheuresf[j].remove(); // heure fin
            }
        }
        // On ajoute l'id dans l'action du formulaire Ajout Evenement
        var action = $('#agendaForm').attr('action'); // Recupère la valeur de l'attribut de l'action
        action = action.split("?"); // Vire l'ancienne valeur [userId]
        idconsultant = $("#consultantC option:selected").attr("id"); // On recupere l'id de l'option selectionner
        idconsultant = idconsultant.split('-');
        action = action[0]+"?userId="+idconsultant[0]+'&calendrierid='+idconsultant[1]; // String de l'action finale
        $('#agendaForm').attr('action',action) // Maj de l'action dans le formulaire
        // Affichage du nom du consultant
        $('.nameconsultant').text($('#consultantC option:selected').text());
        // Affichage du calendrier dans le bloc
        $('.blocacalendar').append($('#consultantC').val());
        $('#nomconsultant').css('display','inline');
        // Affichage du formulaire 
        $('.formPagenda').css('display', 'inline-block');
        // modification de la largeur du calendrier
        $('.blocacalendar iframe').attr('width', '1150');
        $('.blocacalendar iframe').attr('height', '780');
        // Positionnement du bouton
        $('.formPagenda button').css('margin-left', '59%');
        $('#agendaForm').css({
            padding: '6px',
            'margin-top': '45px'
        });
    }
    else{
        // On masque le formulaire
        $('.formPagenda').css('display', 'none');
        // On masque le nom du beneficiaire
        $('#nomconsultant').css('display','none');
    }

});

// vide un bloc
function emptyelement(element, type) {
    switch (type) {
        case 'id':
            $('#' + element).empty();
            break;
        case 'class':
            $('.' + element).empty();
            break;
    }
}

$(document).ready(function() {
    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $container = $('div#espace_documentaire_documents');
    // On ajoute un lien pour ajouter une nouvelle catégorie
    var $addLink = $('<a href="#" id="add_image" class="btn btn-default">Ajouter une image</a>');
    $container.append($addLink);
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        addImage($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });
    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find(':input').length;
    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
    } else {
        // Pour chaque catégorie déjà existante, on ajoute un lien de suppression
        $container.children('div').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire Image
    function addImage($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Image n°' + (index+1))
            .replace(/__name__/g, index));
        // On ajoute au prototype un lien pour pouvoir supprimer l'image
        addDeleteLink($prototype);
        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);
        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }

    // La fonction qui ajoute un lien de suppression d'une image
    function addDeleteLink($prototype) {
        // Création du lien
        $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');
        // Ajout du lien
        $prototype.append($deleteLink);
        // Ajout du listener sur le clic du lien
        $deleteLink.click(function(e) {
            $prototype.remove();
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }
});

(function(){

    var plusMoins = document.getElementById('plusMoins');
    if(plusMoins != undefined) {
        plusMoins.onclick = function () {
            if (plusMoins.innerHTML == 'voir plus +') {
                document.getElementById('rechercherPlus').style.display = 'block';
                plusMoins.innerHTML = 'voir moins -';
            } else {
                document.getElementById('rechercherPlus').style.display = 'none';
                plusMoins.innerHTML = 'voir plus +';
            }
        };
    }
    /**
     var ajouterNews = document.getElementById('OngletAjouterNews');
     var afficherNews = document.getElementById('OngletAfficherNews');
     ajouterNews.onclick = function (e) {
        e.preventDefault();
        ajouterNews.className='active';
        afficherNews.className = '';
        document.getElementById('afficheNews').style.display = 'block';
        document.getElementById('ajouteNews').style.display = 'none';
    };
     afficherNews.onclick = function (e) {
        e.preventDefault();
        ajouterNews.className = '';
        afficherNews.className = 'active';
        document.getElementById('afficheNews').style.display = 'none';
        document.getElementById('ajouteNews').style.display = 'block';
    };
     */

    var input = document.querySelectorAll('#editBeneficiaire input');
    var content = [];
    for(var i=0;i<input.length;i++) {
        content[i] = input[i].value;
        input[i].addEventListener('change',function(test){
            this.style.border = '1px solid #ccc';
            this.style.backgroundColor = "#c7c4c4";
            this.style.color = 'inherit';
        },true);
    }

    function afficheMessageFlash() {
        var flash = document.getElementById("flashbag");
        if (flash != undefined) {
            flash.style.display = 'none';
        }
    }
    setTimeout(afficheMessageFlash,5000);
})();

function plusMoins(element) {
    parent = element.parentNode.parentNode.parentNode;
    body = parent.getElementsByClassName('collapse')[0];
    span  = element.getElementsByClassName('glyphicon')[0];

    //alert(span);
    //alert(element.getElementsByClassName('glyphicon')[0].className);
    if(body.className == 'collapse in'){
        span.className = 'glyphicon glyphicon-plus';
    }else{
        span.className = 'glyphicon glyphicon-minus';
    }
}
