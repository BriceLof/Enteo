$("document").ready(function (initDynamicContent) {
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

    //datepicker date de naissance
    $( function() {
        $("form input.date").datepicker({
            dateFormat: 'dd/mm/yy',
            yearRange: '1940:2016',
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
        }).on('input change', function (e) {
            if(this.classList.contains('modified')){
            }else{
                this.className += ' modified';
            }
        });
    });

    $( function() {
        $(".accompagnementDate").datepicker({
            dateFormat: 'dd/mm/yy',
            yearRange: '2017:2030',
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
        }).on('input change', function (e) {
            if(this.classList.contains('modified')){
            }else{
                this.className += ' modified';
            }
        });
    });

    jQuery.validator.addMethod("greaterThan",
        function (value, element) {
            if($('#accompagnement_dateDebut').val() != "" && value != "") {
                var startDate = $('#accompagnement_dateDebut').val().split("/");
                var valueEntered = value.split("/");
                if (valueEntered[2] > startDate[2]) {
                    return true;
                } else {
                    if (valueEntered[2] == startDate[2]) {
                        if (valueEntered[1] > startDate[1]) {
                            return true;
                        } else {
                            if (valueEntered[1] == startDate[1]) {
                                if (valueEntered[0] > startDate[0]) {
                                    return true;
                                } else {
                                    return false;
                                }
                            } else {
                                return false;
                            }
                        }
                    } else {
                        return false;
                    }
                }
            }
            return true;
        }, "vérifiez la date de fin"
    );

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
                    "required" : false,
                    "dateBR" : /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/,
                },
                "accompagnement[dateFin]": {
                    "dateBR" : /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/,
                    "greaterThan" : true,
                    "required" : false
                },
                "accompagnement[financeur][0][nom]":{
                    "required": function(){
                        return ($('#accompagnement_financeur_0_montant').val() != "" || $('#accompagnement_financeur_0_dateAccord').val() != "" );
                    }
                },
                "accompagnement[financeur][0][organisme]":{
                    "required": function(){
                        return ($('#accompagnement_financeur_0_nom').val() != 'OPCA' || $('#accompagnement_financeur_0_nom').val() != 'OPACIF' );
                    }
                },
                "accompagnement[financeur][0][montant]":{
                    "number" : true,
                    "required": function(){
                        return ($('#accompagnement_financeur_0_nom').val() != "" || $('#accompagnement_financeur_0_dateAccord').val() != "" );
                    }
                },
                "accompagnement[financeur][0][dateAccord]":{
                    "required": false
                },

                "accompagnement[financeur][1][nom]":{
                    "required": function(){
                        return ($('#accompagnement_financeur_1_montant').val() != "" || $('#accompagnement_financeur_1_dateAccord').val() != "" );
                    }
                },
                "accompagnement[financeur][1][organisme]":{
                    "required": function(){
                        return ($('#accompagnement_financeur_1_nom').val() != 'OPCA' || $('#accompagnement_financeur_1_nom').val() != 'OPACIF' );
                    }
                },
                "accompagnement[financeur][1][montant]":{
                    "number" : true,
                    "required": function(){
                        return ($('#accompagnement_financeur_1_nom').val() != "" || $('#accompagnement_financeur_1_dateAccord').val() != "" );
                    }
                },
                "accompagnement[financeur][1][dateAccord]":{
                    "required": false
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
                    "tel": /^0[1-8]([\s]?[0-9]{2}){4}$/
                },
                "beneficiaire[tel2]":{
                    "required": false,
                    "tel": /^0[1-8]([\s]?[0-9]{2}){4}$/
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
                    "numSecu": /^[12][0-9]{12}$/
                },
                "beneficiaire[numSecuCle]":{
                    "required": false,
                },
                "beneficiaire[dateNaissance]":{
                    "required": false
                }
            },
            errorElement: 'div'
        })
    });
    
    $(".cp").keyup(function () {
        if ($(this).val().length >= 2){
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
                        $(".ville").append("<option data-cp="+value.cp+" value="+value.id+">"+value.nom+"</option>")
                    });
                       
                    $("#recherche_beneficiaire_ville_nom").change(function()
                    {
                        villeSelected = $('option:selected',this).attr('data-cp');
                        $(".cp").val(villeSelected)
                    })
                }
            })
        }else{
            $(".ville").val('');
        }
    });
    
    // Si le champs ville est vide alors reinitialiser le champs code postal
    $('#autrebureau').keyup(function(){
        if($('#autrebureau').val() == ''){
            $('#ziph').val('');
            $('#zip').val('');
            $('#bureauotherid').val('-1'); // Pour la gestion du calendrier Bureau lors de la Modification d'un evenement
        }
    });
    
    $('#nomb').keyup(function(){
        if($('#nomb').val() == ''){
            $('#prenomb').val(''); // prenom disabled
            $('#prenombe').val(''); // prenom hidden
            $('#idbeneficiaire').val(''); // id beneficiaire hidden
        }
    });
    
    $('#dpttest').on('keyup', function(){
        if($('#dpttest').val() == ''){
            $('#bureauRdv').val('');
            // on reinitialise tous les champs
            $('#dpttest').val(''); // departement
            $('.namebureauselect').val(''); // bureau
            $('.bureauselect').val(-1); // bureau selectionner
            $("#villeh").val(''); // ville
            $("#ville").val(''); // ville
            $('#adresse').val(''); // adresse
            $('#adresseh').val(''); // adresse 
            $('#zip').val(''); // code postal
            $('#ziph').val(''); // code postal
            $('#bureauotherid').val('-1'); // Pour la gestion du calendrier Bureau lors de la Modification d'un evenement
        }
    });
    
    $('#autrebureau').on('keyup', function(){
        if($('#autrebureau').val() == ''){
            // on reinitialise tous les champs
            $('#autrebureau').val(''); // departement
            $('#bureauRdv').val(''); // Nom du bureau
            $("#villeh").val(''); // ville
            $("#ville").val(''); // ville
            $('#adresse').val(''); // adresse
            $('#adresseh').val(''); // adresse 
            $('#zip').val(''); // code postal
            $('#ziph').val(''); // code postal
        }
    });
    
    // =========================================================================== //
    // ================= Autocompletion Nom et Prenom beneficiaire =============== //
    // =========================================================================== //
    var urlautocompletion;
    switch(true){
        case (location.pathname == '/teo/web/app_dev.php/agenda/evenements'):
            urlautocompletion = 'http://'+location.hostname+location.pathname.replace("agenda/evenements", "autocompletion"); // on appelle le script JSON
            break;
        case (location.pathname == '/teo/teo/web/app_dev.php/agenda/evenements'):
            urlautocompletion = 'http://'+location.hostname+location.pathname.replace("agenda/evenements", "autocompletion"); // on appelle le script JSON
            break;
        default:
            urlautocompletion = 'http://'+location.hostname+location.pathname.replace("agenda", "autocompletion"); // on appelle le script JSON
            break;
    }

    // Lors de la modification: c'est pour distinguer bureau existant et autre bureau
    if($('#autrebureauc').is(':checked')){
            // On active les champs [Bureau, code.....] et on cache le champ ville et on l'inialise
            $('#autrebureau').css('display','inline');
            $('#bureauRdv').removeAttr('disabled');
            $('#adresse').removeAttr('disabled');
            $('#adresse').attr('required',true);
            $('#bureauRdv').attr('required',true);
            $('#ziph').attr('required',true);
            $("#dpttest").val('-1');
            //$("#dpttest").removeAttr('required');
            $("#dpttest").css('display', 'none');
            $('.bureauselect').val(-1); // bureau selectionner
     }
    // ======================================================================= //
    // ========= Activation des champs Bureau, code postal et adresse ======== //
    // ============== Lorsque la case Autre bureau est coché =================//
    // ======================================================================= //
    $('#autrebureauc').click(function(){
        if($('#autrebureauc').is(':checked')){
            // On active les champs [Bureau, code.....] et on cache le champ ville et on l'inialise
            $('#autrebureau').css('display','inline');
            $('#bureauRdv').removeAttr('disabled');
            $('#adresse').removeAttr('disabled');
            $('#adresse').attr('required',true);
            $('#bureauRdv').attr('required',true);
            $('#ziph').attr('required',true);
            // Reinitialisation
            $('#autrebureau').val('');
            $('#bureauRdv').val('');
            $('#adresse').val('');
            $('#zip').val('');
            $('#ziph').val('');
            $("#dpttest").val('-1');
            //$("#dpttest").removeAttr('required');
            $("#dpttest").css('display', 'none');
            $('.bureauselect').val(-1); // bureau selectionner
        }
        else{
            // On desactive les champs [Bureau, code.....] et on affiche le champ ville et on l'inialise
            $('#bureauRdv').attr('disabled', true);
            $('#adresse').attr('disabled', true);
            $('#adresse').removeAttr('required');
            $('#bureauRdv').removeAttr('required');
            $('#ziph').removeAttr('required');
            // Reinitialisation
            $('#autrebureau').val('');
            $('#ville_id').val('');
            $('#bureauRdv').val('');
            $('#adresse').val('');
            $('#zip').val('');
            $('#ziph').val('');
            $("#dpttest").val('');
            $("#dpttest").css('display', 'inline');
            $('.bureauselect').val(-1); // bureau selectionner
            $('#autrebureau').css('display','none');
        }
    });
    
    // Autocompletion pour champ #autrbureau 
    $("#autrebureau").autocomplete({
        source: function($request, reponse){
            // Si le length du code postal n'est pas = 5 alors pas de requete envoyé
            if($('#autrebureau').val().length >= 3){
                $.ajax({
                    url: urlautocompletion,
                    data : {
                        term : $('#autrebureau').val(),
                        sentinel: 3,
                    },
                    dataType : 'json', // on spécifie bien que le type de données est en JSON
                    success : function(donnee){
                        var obj = $.parseJSON(donnee);
                        reponse($.map(obj, function (item) {
                            return {
                                value: function ()
                                {
                                  if ($(this).attr('id') == 'autrebureau')
                                  {
                                     if(item.nom != undefined){
                                        $('#ville_id').val(item.id);
                                        $('#zip').val(item.cp); // code postal
                                        $('#ziph').val(item.cp); // code postal
                                        return item.nom;
                                     }
                                  }
                                  else
                                  {
                                      if(item.nom != undefined){
                                          return item.nom;
                                      }
                                      else{
                                          // on reinitialise tous les champs
                                          $('#ville_id').val('');
                                          $('#autrebureau').val(''); // departement
                                          $('#zip').val(''); // code postal
                                          $('#ziph').val(''); // code postal
                                          return 'Aucune ville trouvée';
                                      }
                                  }
                                }
                            }
                        }));
                    }
                });
            }
        }
    });

    // ======================================================================= //
    // ======= Affichage du champs historique_autreSummary lorsqu'on ========= //
    // ============= Selectionnera Autre dans Titre evenement ================ //
    // ======================================================================= //
    $('#historique_summary').change(function(){
        if($('#historique_summary').val() == "Autre"){
            // On affiche le champ Autre
            $('#historique_autreSummary').css({
                display:'inline',
                'margin-top': '7px'
            });
            $('#historique_autreSummary').val("");
        }
        else{
            // On cache le champs Autre
            if($('#historique_autreSummary').css("display") != "none"){
                $('#historique_autreSummary').css("display", 'none');
            }
            $('#historique_autreSummary').val(""); // Reinitialisation
            $('#historique_autreSummary').removeAttr("required");
        }
    });

    // ======================================================================= //
    // ========== Masquer le message apres 5 secondes d'affichage ============ //
    // ======================================================================= //
    setTimeout(function(){
        $('.msg-errors').css('display', 'none');
    }, 7000);
   
    // Autocompletion ville
    $("#dpttest").autocomplete({
        source: function($request, reponse){
            $.ajax({
                url: urlautocompletion,
                data : {
                    term : $('#dpttest').val(),
                    sentinel: 2,
                    idb : $('#idbeneficiaire').val()
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
                                      // $('#dpttest').val(item.departementId); // departement
                                      $('#bureauRdv').val(item.nombureau); // bureau
                                      $('.bureauselect').val(item.id); // bureau selectionner
                                      $('.namebureauselect').val(item.nombureau); // bureau
                                      $("#villeh").val(item.nom); // ville
                                      $('#adresse').val(item.adresse); // adresse
                                      $('#adresseh').val(item.adresse); // adresse 
                                      $('#zip').val(item.cp); // code postal
                                      $('#ziph').val(item.cp); // code postal
                                      // $('#historique_Enregistrer').removeAttr('disabled');
                                      return item.nom;
                                  }
                                  else{
                                      // on reinitialise tous les champs
                                      $('#dpttest').val(''); // departement
                                      $('#bureauRdv').val(''); // bureau
                                      $('.namebureauselect').val(''); // bureau
                                      $('.bureauselect').val(-1); // bureau selectionner
                                      $("#villeh").val(''); // ville
                                      $("#ville").val(''); // ville
                                      $('#adresse').val(''); // adresse
                                      $('#adresseh').val(''); // adresse 
                                      $('#zip').val(''); // code postal
                                      $('#ziph').val(''); // code postal
                                      // $('#historique_Enregistrer').attr('disabled','true');
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
                    sentinel: 1,
                    id: $('.consultantcalendrierid').val()
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
                                      // $('#historique_Enregistrer').removeAttr('disabled');
                                      $('#idbeneficiaire').val(item.id);
                                      console.log('beneficiaire id: '+item.id);

                                      return item.nomConso;
                                  }
                                  else{
                                      $('#nomb').val('');
                                      $('#prenomb').val(''); // input visible
                                      $('#prenombe').val(''); // input hidden
                                      // $('#historique_Enregistrer').attr('disabled','true');
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
    x=2012;
    datecourant = new Date();
    anneecourant = datecourant.getFullYear(); // année
    // 1- desactive les années precedentes à l'année en cour
    while(x<anneecourant){
        $('#historique_dateDebut_year option[value="'+x+'"]').attr('disabled', true); // activer
        x++;
    }
    $("#historique_dateDebut_day").change(function(){
        datecourant = new Date();
        heuretoday = datecourant.getHours(); // heure courante
        minutetoday = datecourant.getMinutes(); // minute en cour
        jourcourant = datecourant.getDate(); // jour
        moiscourant = datecourant.getMonth()+1; // mois
        anneecourant = datecourant.getFullYear(); // année
        jour = $("#historique_dateDebut_day").val(); // mois
        mois = $("#historique_dateDebut_month").val(); // mois
        an = $("#historique_dateDebut_year").val(); // Années
        selectheuresd = $('#historique_heureDebut_hour option'); // heure debut
        selectheuresf = $('#historique_heureFin_hour option'); // heure fin
        // On active les autres champs
        for(j=7; j<selectheuresd.length; j++){
           // 1- heure debut
           if(j<20){
               $('#historique_heureDebut_hour option[value="'+j+'"]').removeAttr('disabled');
           }
           // 2- heure fin
           if(j+1<21){
               $('#historique_heureFin_hour option[value="'+(j+1)+'"]').removeAttr('disabled');
           }
        }
        // Si la date choisi est inferieur à la date du jour alors on le positionne sur le mois suivant
        if(jour<jourcourant && mois<=moiscourant && anneecourant == an){
            // Passer au mois suivant si on est pas en decembre
            if(moiscourant < 12){
                $('#historique_dateDebut_month').val(moiscourant+1); // activer
                // Maj de la date de fin
                $('#historique_dateFin_month').val(moiscourant+1); // activer
            }
            else{
                // On passe aussi à l'année suivante
                $('#historique_dateDebut_month').val(1); // mois de janvier
                $("#historique_dateDebut_year").val(anneecourant+1);
                // Maj de la date de fin
                $('#historique_dateFin_month').val(1); // mois de janvier
                $("#historique_dateFin_year").val(anneecourant+1); 
            }
        }
        // On met à jour le jour de datefin
        $("#historique_dateFin_day").val($("#historique_dateDebut_day").val());
    });
    $("#historique_dateDebut_month").change(function(){
        selectheuresd = $('#historique_heureDebut_hour option'); // heure debut
        selectheuresf = $('#historique_heureFin_hour option'); // heure fin
        datecourant = new Date();
        jourcourant = datecourant.getDate(); // jour
        moiscourant = datecourant.getMonth()+1; // mois
        jour = $("#historique_dateDebut_day").val(); // mois
        mois = $("#historique_dateDebut_month").val(); // mois
        an = $("#historique_dateDebut_year").val(); // Années
        heuretoday = datecourant.getHours(); // heure courante
        minutetoday = datecourant.getMinutes(); // minute en cour
        // On active les autres champs
        for(j=7; j<selectheuresd.length; j++){
           // 1- heure debut
           if(j<20){
               $('#historique_heureDebut_hour option[value="'+j+'"]').removeAttr('disabled');
           }
           // 2- heure fin
           if(j+1<21){
               $('#historique_heureFin_hour option[value="'+(j+1)+'"]').removeAttr('disabled');
           }
        }
        // Si la date choisi est inferieur à la date du jour alors on le positionne sur le mois suivant
        if(mois<moiscourant && anneecourant == an){
            // On passe à l'année suivante
            $("#historique_dateDebut_year").val(anneecourant+1); // année
            // Maj de la date de fin
            $("#historique_dateFin_year").val(anneecourant+1); // année
        }
        else{
            // On passe à l'année suivante
            $("#historique_dateDebut_year").val(anneecourant); // année
            // Maj de la date de fin
            $("#historique_dateFin_year").val(anneecourant); // année
        }
        // On met à jour le mois de datefin
        $("#historique_dateFin_month").val($("#historique_dateDebut_month").val());
    });
    // Date
    datecourant = new Date();
    jourcourant = datecourant.getDate(); // jour
    moiscourant = datecourant.getMonth()+1; // mois
    $("#historique_dateDebut_year").change(function(){
        // On met à jour l'année de datefin
        $("#historique_dateFin_year").val($("#historique_dateDebut_year").val());
    });
    if($('.eventidupdate').val() != undefined && $('#historique_dateDebut_month').val() <= moiscourant){
        // On desactiver les dates passées
        dateencour = $('#historique_dateDebut_day').val();
        j = 1;
        while(j<dateencour){
            $('#historique_dateDebut_day option[value="'+j+'"]').attr('disabled', true); // activer
            j++;
        }
        
        // On desactive les heures de fin
        j = 7;
        heure_debut = parseInt($('#historique_heureFin_hour').val()); // On recupère l'heure de debut
        // Desactiver et Activer les heures en fonction de l'heure de debut
        while(j<=21){
            if(j<heure_debut){
                $('#historique_heureFin_hour option[value="'+j+'"]').attr('disabled', true); // activer
            }
            j++;
        }
    }
    // =================================================================================== //
    // ==================Pour la mise à jour de Rendez-vous ============================== // 
    // =================================================================================== //
    if($('input:radio[name="typeRdv"]').is(':checked') && $('input[type=radio][name=typeRdv]:checked').attr('value') == "distanciel" && $('input[type=radio][name=typeRdv]:checked').attr('value') != undefined){
        // On masque les bureau et adresse
        $('.bureau_v').css('display', 'none');
         // On supprime le required dans le champ bureau
         $('#bureauRdv').removeAttr('required');
         $('.letyperdv').val($(this).val());
         $("#dpttest").val("-1"); // initialisation pour eviter le blocage de la soumission du formulaire lors d'un rdv distanciel
         $('.checkotherbureau').css('display', 'none'); // autre bureau
         $('#ziph').removeAttr('required');
         $('#ville_id').val(''); 
         $('#autrebureau').removeAttr('required');
         $('#bureauRdv').removeAttr('required');
         $('#adresse').removeAttr('required');
         $('#bureauRdv').removeAttr('required');
         if($('#historique_summary').val() == "Autre"){
            // On affiche le champ Autre
            $('#historique_autreSummary').css({
                display:'inline',
                'margin-top': '7px'
            });
        }
        else{
            // On cache le champs Autre
            if($('#historique_autreSummary').css("display") != "none"){
                $('#historique_autreSummary').css("display", 'none');
            }
            $('#historique_autreSummary').val(""); // Reinitialisation
            $('#historique_autreSummary').removeAttr("required");
        }
    }
    else{
          $('.letyperdv').val($(this).val());
          // On affiche les bureau et adresse
          $('.bureau_v').css('display', 'inline-block');
          // On ajoute le required dans le champ bureau
          $('#bureauRdv').attr('required','true');
          $('.checkotherbureau').css('display', 'inline'); // autre bureau
          if($('#autrebureauc').is(':checked')){
             $('#ziph').attr('required', true);
          }
          else{
               // On active les champs [Bureau, code.....] et on cache le champ ville et on l'inialise
               $('#autrebureau').removeAttr('required');
               if($("#dpttest").val() == -1)
                    $("#dpttest").val('');
          }
          if($('#historique_summary').val() == "Autre"){
            // On affiche le champ Autre
            $('#historique_autreSummary').css({
                display:'inline',
                'margin-top': '7px'
            });
        }
        else{
            // On cache le champs Autre
            if($('#historique_autreSummary').css("display") != "none"){
                $('#historique_autreSummary').css("display", 'none');
            }
            $('#historique_autreSummary').val(""); // Reinitialisation
            $('#historique_autreSummary').removeAttr("required");
        }
    }
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
                $('.checkotherbureau').css('display', 'none'); // autre bureau
                $('#ziph').removeAttr('required');
                $('#ville_id').val(''); 
                $('#autrebureau').removeAttr('required');
                $('#bureauRdv').removeAttr('required');
                $('#adresse').removeAttr('required');
                $('#bureauRdv').removeAttr('required');
                $('#bureauotherid').val('-1'); // Pour la gestion du calendrier Bureau lors de la Modification d'un evenement
            }
            else{
                $('.letyperdv').val($(this).val());
                // On affiche les bureau et adresse
                $('.bureau_v').css('display', 'inline-block');
                // On ajoute le required dans le champ bureau
                $('#bureauRdv').attr('required','true');
                $('.checkotherbureau').css('display', 'inline'); // autre bureau
                if($('#autrebureauc').is(':checked')){
                    $('#ziph').attr('required', true);
                }
                else{
                    // On active les champs [Bureau, code.....] et on cache le champ ville et on l'inialise
                    $('#autrebureau').removeAttr('required');
                    if($("#dpttest").val() == -1)
                        $("#dpttest").val('');
                }
            }
    });
    if($('.calendrierconsultant').val() != undefined){
        datecourant = new Date();
        heuretoday = datecourant.getHours(); // heure courante
        minutetoday = datecourant.getMinutes(); // minute en cour
        selectheuresd = $('#historique_heureDebut_hour option'); // heure debut
        selectheuresf = $('#historique_heureFin_hour option'); // heure fin
        if($('.eventidupdate').val() == undefined){
            for(j=0; j<selectheuresd.length; j++){
                // On desactive toutes les heures passées
                // 1- heure debut
                if(heuretoday<20){
                    if(j<heuretoday || j>20){
                        $('#historique_heureDebut_hour option[value="'+j+'"]').attr('disabled', true);
                    }
                    // 2- heure fin
                    if(j<=heuretoday || j>21){
                        $('#historique_heureFin_hour option[value="'+j+'"]').attr('disabled', true);
                    }
                }
                else{
                    if(j<7 || j>20){
                        $('#historique_heureDebut_hour option[value="'+j+'"]').attr('disabled', true);
                    }
                    // 2- heure fin
                    if(j<8 || j>21){
                        $('#historique_heureFin_hour option[value="'+j+'"]').attr('disabled', true);
                    }
                }
            }
            // On reinitialise les 2 heures (debut et fin )
            if(heuretoday<20){
                $('#historique_heureDebut_hour').val(heuretoday);
                // $('#historique_heureDebut_minute').val(minutetoday);
            }
            if(heuretoday<21){
                $('#historique_heureFin_hour').val(heuretoday+1);
                // $('#historique_heureFin_minute').val(minutetoday);
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
        // k = parseInt($('#historique_heureFin_hour option').val());
        j = 8;
        heure_debut = parseInt($('#historique_heureDebut_hour').val()); // On recupère l'heure de debut
        minute_debut = parseInt($('#historique_heureDebut_minute').val()); // On recupère l'heure de debut
        // On supprime toutes les heures inferieures ou egale à l'heure de debut pour la coherence des données
        selectheuresf = $('#historique_heureFin_hour option'); // heure fin
        // Desactiver et Activer les heures en fonction de l'heure de debut
        while(j<=21){
            if(j<=heure_debut){
                $('#historique_heureFin_hour option[value="'+j+'"]').attr('disabled', true); // activer
            }
            else{
                $('#historique_heureFin_hour option[value="'+j+'"]').removeAttr('disabled'); // desactiver
            }
            j++;
        }
        majheuredebut = heure_debut+1;
        $('#historique_heureFin_hour').val(majheuredebut); // Maj de l'heure de fin
        $('#historique_heureFin_minute').val(minute_debut); // Maj de la minute de fin
    });
    $('#historique_heureDebut_minute').change(function(){
        minute_debut = parseInt($('#historique_heureDebut_minute').val()); // On recupère l'heure de debut
        $('#historique_heureFin_minute').val(minute_debut); // Maj de la minute de fin
    });
});

// Couleur Agenda pour Admin
$('.colorcalendar').css({
    'padding':'7px',
    'margin-bottom':'10px'
});

// ---------------------------------------------- //
// ------------- Affichage pour Admin ----------- //
// ---------------------------------------------- //
$('#consultantC').change(function(){
    emptyelement('blocacalendar', 'class'); // Vider le bloc du calendrier
    if($('#consultantC').val() != ""){
        datecourant = new Date();
        heuretoday = datecourant.getHours(); // heure courante
        minutetoday = datecourant.getMinutes(); // minute en cour
        // On vire les heures < 7 et > 20 dans heure_debut et heure_fin
        selectheuresd = $('#historique_heureDebut_hour option'); // heure debut
        selectheuresf = $('#historique_heureFin_hour option'); // heure fin
        for(j=0; j<selectheuresd.length; j++){
            // On desactive toutes les heures passées
            // 1- heure debut
            if(heuretoday<20){
                if(j<heuretoday || j>20){
                    $('#historique_heureDebut_hour option[value="'+j+'"]').attr('disabled', true);
                }
                // 2- heure fin
                if(j<=heuretoday || j>21){
                    $('#historique_heureFin_hour option[value="'+j+'"]').attr('disabled', true);
                }
            }
            else{
                if(j<7 || j>20){
                    $('#historique_heureDebut_hour option[value="'+j+'"]').attr('disabled', true);
                }
                // 2- heure fin
                if(j<8 || j>21){
                    $('#historique_heureFin_hour option[value="'+j+'"]').attr('disabled', true);
                }
            }
        }
        // On reinitialise les 2 heures (debut et fin )
        if(heuretoday<20){
            $('#historique_heureDebut_hour').val(heuretoday);
            // $('#historique_heureDebut_minute').val(minutetoday);
        }
        if(heuretoday<21){
            $('#historique_heureFin_hour').val(heuretoday+1);
            // $('#historique_heureFin_minute').val(minutetoday);
        }
        // On ajoute l'id dans l'action du formulaire Ajout Evenement
        var action = $('#agendaForm').attr('action'); // Recupère la valeur de l'attribut de l'action
        action = action.split("?"); // Vire l'ancienne valeur [userId]
        idconsultant = $("#consultantC option:selected").attr("id"); // On recupere l'id de l'option selectionner
        idconsultant = idconsultant.split('-');
        action = action[0]+"?userId="+idconsultant[0]+'&calendrierid='+idconsultant[1]; // String de l'action finale
        $('.consultantcalendrierid').val(idconsultant[0]); // In du consultant
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

// ================================================================= //
// ================ Suppression du rendez-vous ===================== //
// ================================================================= //
$('.btn_supp').click(function(){
    // fenetre modale
    $('.fenetre-modale').append(
          '<div class="modal fade" id="myModale" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
             '<div class="modal-dialog">'+
                '<div class="modal-content">'+
                   '<div class="modal-header my_header_modal">'+
                      '<button type="button" class="close" data-dismiss="modal">&times;</button>'+
                      '<center><h4 class="modal-title" style="font-size:22px;">Supprimer un rendez-vous</h4></center>'+
                    '</div>'+
                    '<div class="modal-body">'+
                        '<center><p style="font-size:20px; color: #668cd9;">Confirmer la suppression du rendez-vous !</p></center>'+
                        '<input type="hidden" id="evenement-supp" value="'+$(this).attr('id')+'">'+
                    '</div>'+
                    '<div class="modal-footer">'+
                      '<button type="button" class="btn btn-success" onclick="supprimer_evenement()" data-dismiss="modal">Oui</button>'+
                      '<button type="button" class="btn btn-primary" onclick="rien_faire_evenement()" data-dismiss="modal">Non</button>'+
                   '</div>'+
               '</div>'+
             '</div>'+
          '</div>'
    );
});

function rien_faire_evenement(){
    $('.msg-erreur').removeAttr('style');
    emptyelement('msg-erreur', 'class'); // on efface 
}

// Supprimer un evenement
function supprimer_evenement(){
    // On recupere l'id de l'evenement
    evtid = $('#evenement-supp').val();
    $('.msg-erreur').removeAttr('style');
    emptyelement('msg-erreur', 'class'); // on efface 
    $.ajax({
          type: 'get',
          url: Routing.generate('delete_evenement', {eventid : evtid}),
          success: function (data) {
             if(data == '1'){
                 // Archivage reussi
                 $('.'+evtid).remove();
                 $('.msg-erreur').css('background', '#dadada');
                 $('.msg-erreur').append(
                     '<div><strong>Le rendez-vous a été supprimé avec succes!</strong></div>'
                 ).slideUp(8000).delay(9000).fadeIn(9000);
                 emptyelement('fenetre-modale', 'class'); // on efface 
                 if($('#evenementcalandar tr').size() == 1){
                     // On recharge la page du beneficiaire
                     location.reload();
                 }
             }
          }
    });
}

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
    var $addLink = $('#add_document');

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
        var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Document n°' + (index+1))
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
        $deleteLink = $('<a href="#" style="padding: 0px;" class="btn btn-danger">Supprimer</a>');
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

if(document.getElementById('espace_documentaire_submit')) {
    (function () {
        var element = document.getElementById('espace_documentaire_submit');
        element.addEventListener('click', function () {
            document.getElementById("loading").style.width = "100%";
        }, true);
    })();
}


function urlParam(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
        return null;
    }else{
        return results[1] || 0;
    }
}

/*reglage numéro de téléphone*/
(function() {
    var tels = document.getElementsByClassName('telephoneConso');
    for (var i = 0; i < tels.length; i++) {
        tels[i].addEventListener('keypress', function () {

            var s = this.value.replace(/ /g, "");

            if (s.length % 2 == 0 && s.length < 10 && s.length > 1) {
                this.value += " ";
            }

        });
    }

    var form = document.querySelectorAll('#afficheBeneficiaire form');
    for (var k = 0; k < form.length; k++) {
        form[k].addEventListener('submit', function () {
            var tels = document.getElementsByClassName('telephoneConso');
            for (var i = 0; i < tels.length; i++) {
                tels[i].value = tels[i].value.replace(/ /g, "");
            }
            return true;
        });
    }

})();

(function(){
    window.onload = function () {
        var tels = document.getElementsByClassName('telephoneConso');
        for(var i=0;i<tels.length;i++) {
            if(tels[i].value.length > 14){
                tels[i].value = tels[i].value.replace(/ /g,"");
            }
            var result = "";
            var s = ""+tels[i].value;
            var t=0;
            for (var j = 0;j<s.length;j++){
                if(t == 2){
                    result+=" ";
                    result+=s[j];
                    t = 1;
                }
                else {
                    result = result+s[j];
                    t++;
                }
            }
            tels[i].value = result;

        }
    }
})();

//la fonction qui demande a l'utilisateur d'enregistrer au cas ou le bloc perd le focus sur la fiche bénéficiaire
(function(){
    var enfant = document.getElementsByClassName('fiche');
    var enfant2 = document.getElementsByClassName('projet');
    var enfant3 = document.getElementsByClassName('accompagnement');
    var enfant4 = document.getElementsByClassName('news');
    var enfant5 = document.getElementsByClassName('maj');
    var enfant6 = document.getElementsByClassName('suivi');

    ////////////////////FICHE BENEFICIAIRE ///////////////////////
    for(var i=0;i<enfant.length;i++){
        enfant[i].addEventListener('change',function () {
            if(this.classList.contains('modified')){

            }else{
                this.className += ' modified';
            }
        });
        enfant[i].addEventListener('click',function () {
            for(var i=0;i<enfant2.length;i++){
                if(enfant2[i].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal2" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur le Projet bénéficiaire</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK2">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal2').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal2').modal({show:true});
                    $('#dataConfirmOK2').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["projetEditForm"].submit();
                        }
                    );
                    for(var k=0;k<enfant2.length;k++) {
                        enfant2[k].classList.remove('modified');
                    }
                }
            }
            for(var l=0;l<enfant3.length;l++){
                if(enfant3[l].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal3" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur l\'accompagnement</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK3">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal3').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal3').modal({show:true});
                    $('#dataConfirmOK3').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["accompagnementEditForm"].submit();
                        }
                    );
                    for(var t=0;t<enfant3.length;t++) {
                        enfant3[t].classList.remove('modified');
                    }
                }
            }
            for(var m=0;m<enfant4.length;m++){
                if(enfant4[m].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal4" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur les News</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK4">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal4').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal4').modal({show:true});
                    $('#dataConfirmOK4').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["newsForm"].submit();
                        }
                    );
                    for(var o=0;o<enfant4.length;o++) {
                        enfant4[o].classList.remove('modified');
                    }
                }
            }
            for(var x=0;x<enfant6.length;x++){
                if(enfant6[x].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal6" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur le Suivi Administratif</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK6">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal6').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal6').modal({show:true});
                    $('#dataConfirmOK6').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["suiviAdministratifNewForm"].submit();
                        }
                    );
                    for(var p=0;p<enfant6.length;p++) {
                        enfant6[p].classList.remove('modified');
                    }
                }
            }
        })
    }

    ///////////////////////////PROJET BENEFICIAIRE///////////////////////////
    for(var j=0;j<enfant2.length;j++){
        enfant2[j].addEventListener('change',function () {
            if(this.classList.contains('modified')){
            }else{
                this.className += ' modified';
            }
        });
        enfant2[j].addEventListener('click',function () {
            for(var i=0;i<enfant.length;i++){
                if(enfant[i].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                                        '<div class="modal-dialog">' +
                                        '<div class="modal-content">' +
                                        '<div class="modal-header">' +
                                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                                        '</div>' +
                                        '<div class="modal-body"><p>Des modifications ont été faites sur la fiche bénéficiaire</p><p>Voulez vous les enregistrer ?</p></div>' +
                                        '<div class="modal-footer">' +
                                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                                        '<button class="btn btn-danger" id="dataConfirmOK">Enregistrer</button>' +
                                        '</div></div></div></div>');
                    $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal').modal({show:true});
                    $('#dataConfirmOK').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["ficheBeneficiaireForm"].submit();
                        }
                    );
                    for(var k=0;k<enfant.length;k++) {
                        enfant[k].classList.remove('modified');
                    }
                }
            }
            for(var l=0;l<enfant3.length;l++){
                if(enfant3[l].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal3" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur l\'accompagnement</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK3">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal3').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal3').modal({show:true});
                    $('#dataConfirmOK3').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["accompagnementEditForm"].submit();
                        }
                    );
                    for(var t=0;t<enfant3.length;t++) {
                        enfant3[t].classList.remove('modified');
                    }
                }
            }
            for(var m=0;m<enfant4.length;m++){
                if(enfant4[m].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal4" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur les News</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK4">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal4').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal4').modal({show:true});
                    $('#dataConfirmOK4').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["newsForm"].submit();
                        }
                    );
                    for(var o=0;o<enfant4.length;o++) {
                        enfant4[o].classList.remove('modified');
                    }
                }
            }
            for(var x=0;x<enfant6.length;x++){
                if(enfant6[x].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal6" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur le Suivi Administratif</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK6">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal6').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal6').modal({show:true});
                    $('#dataConfirmOK6').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["suiviAdministratifNewForm"].submit();
                        }
                    );
                    for(var p=0;p<enfant6.length;p++) {
                        enfant6[p].classList.remove('modified');
                    }
                }
            }
        })
    }

    ////////////////////////ACCOMPAGNEMENT/////////////////////////
    for(var k=0;k<enfant3.length;k++){
        enfant3[k].addEventListener('change',function () {
            if(this.classList.contains('modified')){
            }else{
                this.className += ' modified';
            }
        });
        enfant3[k].addEventListener('click',function () {
            for(var i=0;i<enfant.length;i++){
                if(enfant[i].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur la fiche bénéficiaire</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal').modal({show:true});
                    $('#dataConfirmOK').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["ficheBeneficiaireForm"].submit();
                        }
                    );
                    for(var k=0;k<enfant.length;k++) {
                        enfant[k].classList.remove('modified');
                    }
                }
            }
            for(var j=0;j<enfant2.length;j++){
                if(enfant2[j].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal2" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur le Projet bénéficiaire</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK2">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal2').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal2').modal({show:true});
                    $('#dataConfirmOK2').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["projetEditForm"].submit();
                        }
                    );
                    for(var t=0;t<enfant2.length;t++) {
                        enfant2[t].classList.remove('modified');
                    }
                }
            }
            for(var m=0;m<enfant4.length;m++){
                if(enfant4[m].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal4" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur les News</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK4">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal4').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal4').modal({show:true});
                    $('#dataConfirmOK4').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["newsForm"].submit();
                        }
                    );
                    for(var o=0;o<enfant4.length;o++) {
                        enfant4[o].classList.remove('modified');
                    }
                }
            }
            for(var x=0;x<enfant6.length;x++){
                if(enfant6[x].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal6" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur le Suivi Administratif</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK6">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal6').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal6').modal({show:true});
                    $('#dataConfirmOK6').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["suiviAdministratifNewForm"].submit();
                        }
                    );
                    for(var p=0;p<enfant6.length;p++) {
                        enfant6[p].classList.remove('modified');
                    }
                }
            }
        })
    }

    //////////////////NEWS////////////////////////////////////////
    for(var l=0;l<enfant4.length;l++){
        enfant4[l].addEventListener('change',function () {
            if(this.classList.contains('modified')){
            }else{
                this.className += ' modified';
            }
        });
        enfant4[l].addEventListener('click',function () {
            for(var i=0;i<enfant.length;i++){
                if(enfant[i].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur la fiche bénéficiaire</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal').modal({show:true});
                    $('#dataConfirmOK').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["ficheBeneficiaireForm"].submit();
                        }
                    );
                    for(var k=0;k<enfant.length;k++) {
                        enfant[k].classList.remove('modified');
                    }
                }
            }
            for(var j=0;j<enfant2.length;j++){
                if(enfant2[j].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal2" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur le Projet bénéficiaire</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK2">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal2').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal2').modal({show:true});
                    $('#dataConfirmOK2').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["projetEditForm"].submit();
                        }
                    );
                    for(var t=0;t<enfant2.length;t++) {
                        enfant2[t].classList.remove('modified');
                    }
                }
            }
            for(var l=0;l<enfant3.length;l++){
                if(enfant3[l].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal3" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur l\'accompagnement</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK3">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal3').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal3').modal({show:true});
                    $('#dataConfirmOK3').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["accompagnementEditForm"].submit();
                        }
                    );
                    for(var u=0;u<enfant3.length;u++) {
                        enfant3[u].classList.remove('modified');
                    }
                }
            }
            for(var x=0;x<enfant6.length;x++){
                if(enfant6[x].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal6" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur le Suivi Administratif</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK6">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal6').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal6').modal({show:true});
                    $('#dataConfirmOK6').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["suiviAdministratifNewForm"].submit();
                        }
                    );
                    for(var p=0;p<enfant6.length;p++) {
                        enfant6[p].classList.remove('modified');
                    }
                }
            }
        })
    }

    ///////////////BOUTON MAJ///////////////////////////
    for(var n=0;n<enfant5.length;n++){
        enfant5[n].addEventListener('change',function () {
            if(this.classList.contains('modified')){
            }else{
                this.className += ' modified';
            }
        });
        enfant5[n].addEventListener('click',function () {
            for(var i=0;i<enfant.length;i++){
                if(enfant[i].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal5" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur la fiche bénéficiaire</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK5">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal5').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal5').modal({show:true});
                    $('#dataConfirmOK5').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["ficheBeneficiaireForm"].submit();
                        }
                    );
                    for(var k=0;k<enfant.length;k++) {
                        enfant[k].classList.remove('modified');
                    }
                }
            }
            for(var j=0;j<enfant2.length;j++){
                if(enfant2[j].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal2" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur le Projet bénéficiaire</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK2">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal2').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal2').modal({show:true});
                    $('#dataConfirmOK2').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["projetEditForm"].submit();
                        }
                    );
                    for(var u=0;u<enfant2.length;u++) {
                        enfant2[u].classList.remove('modified');
                    }
                }
            }
            for(var l=0;l<enfant3.length;l++){
                if(enfant3[l].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal3" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur l\'accompagnement</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK3">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal3').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal3').modal({show:true});
                    $('#dataConfirmOK3').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["accompagnementEditForm"].submit();
                        }
                    );
                    for(var t=0;t<enfant3.length;t++) {
                        enfant3[t].classList.remove('modified');
                    }
                }
            }
            for(var m=0;m<enfant4.length;m++){
                if(enfant4[m].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal4" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur les News</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK4">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal4').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal4').modal({show:true});
                    $('#dataConfirmOK4').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["newsForm"].submit();
                        }
                    );
                    for(var o=0;o<enfant4.length;o++) {
                        enfant4[o].classList.remove('modified');
                    }
                }
            }
            for(var x=0;x<enfant6.length;x++){
                if(enfant6[x].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal6" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur le Suivi Administratif</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK6">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal6').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal6').modal({show:true});
                    $('#dataConfirmOK6').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["suiviAdministratifNewForm"].submit();
                        }
                    );
                    for(var p=0;p<enfant6.length;p++) {
                        enfant6[p].classList.remove('modified');
                    }
                }
            }
        })
    }

    ///////////////SUIVI ADMINISTRATIF///////////////////////////
    for(var a=0;a<enfant6.length;a++){
        enfant6[a].addEventListener('change',function () {
            if(this.classList.contains('modified')){
            }else{
                this.className += ' modified';
            }
        });
        enfant6[a].addEventListener('click',function () {
            for(var i=0;i<enfant.length;i++){
                if(enfant[i].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur la fiche bénéficiaire</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal').modal({show:true});
                    $('#dataConfirmOK').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["ficheBeneficiaireForm"].submit();
                        }
                    );
                    for(var k=0;k<enfant.length;k++) {
                        enfant[k].classList.remove('modified');
                    }
                }
            }
            for(var j=0;j<enfant2.length;j++){
                if(enfant2[j].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal2" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur le Projet bénéficiaire</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK2">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal2').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal2').modal({show:true});
                    $('#dataConfirmOK2').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["projetEditForm"].submit();
                        }
                    );
                    for(var u=0;u<enfant2.length;u++) {
                        enfant2[u].classList.remove('modified');
                    }
                }
            }
            for(var l=0;l<enfant3.length;l++){
                if(enfant3[l].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal3" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur l\'accompagnement</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK3">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal3').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal3').modal({show:true});
                    $('#dataConfirmOK3').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["accompagnementEditForm"].submit();
                        }
                    );
                    for(var t=0;t<enfant3.length;t++) {
                        enfant3[t].classList.remove('modified');
                    }
                }
            }
            for(var m=0;m<enfant4.length;m++){
                if(enfant4[m].classList.contains('modified')){
                    $('body').append('<div id="dataConfirmModal4" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Merci de confirmer</h3>' +
                        '</div>' +
                        '<div class="modal-body"><p>Des modifications ont été faites sur les News</p><p>Voulez vous les enregistrer ?</p></div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>' +
                        '<button class="btn btn-danger" id="dataConfirmOK4">Enregistrer</button>' +
                        '</div></div></div></div>');
                    $('#dataConfirmModal4').find('.modal-body').text($(this).attr('data-confirm'));
                    $('#dataConfirmModal4').modal({show:true});
                    $('#dataConfirmOK4').click(
                        function () {
                            var tels = document.getElementsByClassName('telephoneConso');
                            for (var i = 0; i < tels.length; i++) {
                                tels[i].value = tels[i].value.replace(/ /g, "");
                            }
                            window.document.forms["newsForm"].submit();
                        }
                    );
                    for(var o=0;o<enfant4.length;o++) {
                        enfant4[o].classList.remove('modified');
                    }
                }
            }
        })
    }
})();

if(document.getElementById('accompagnement')) {
//ajout de plusieurs financement
    $(function () {
        // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
        var $container = $('div#accompagnement_financeur');
        // On ajoute un lien pour ajouter une nouvelle catégorie
        //je mets en commentaire jusqu'à ce que je trouve une solution
        //var $addLink = $('<a href="#" id="add_financement_button" >Ajouter un co-financeur</a>');

        // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
        /**
         $container.on('click',$addLink,function(e) {
            addFinanceur($container);
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
         */

            // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
        var index = $container.find(':input').length;
        // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
        if (index == 0) {
            addTwoFirstFinanceur($container);
            addTwoFirstFinanceur($container);
        } else if (index == 1) {
            addTwoFirstFinanceur($container);
        }
        function addTwoFirstFinanceur($container) {
            var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, '- Financeur ' + (index + 1))
                .replace(/__name__/g, index));
            $container.append($prototype);
            // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
            index++;
        }

        // La fonction qui ajoute un formulaire Image
        function addFinanceur($container) {
            // Dans le contenu de l'attribut « data-prototype », on remplace :
            // - le texte "__name__label__" qu'il contient par le label du champ
            // - le texte "__name__" qu'il contient par le numéro du champ
            var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Financeur ' + (index + 1))
                .replace(/__name__/g, 'Financeur ' + (index + 1)));
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
            $deleteLink = $('<a href="#" style="padding: 0px;" class="btn btn-danger">Supprimer</a>');
            // Ajout du lien
            $prototype.append($deleteLink);
            // Ajout du listener sur le clic du lien
            $deleteLink.click(function (e) {
                $prototype.remove();
                e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                return false;
            });
        }

        $('.nom_organisme').on('change', function () {
            parent = $(this).parent().parent();
            element = parent.find('#' + parent.attr('id') + '_organisme');
            labelElement = parent.find('.organisme_organisme_label');
            if ($(this).val() == 'OPCA') {
                element.css('display', 'inline');
                labelElement.css('display', 'inline');
                $name = 'OPCA';
                listeOpcaOpacif($name, element);
            } else {
                if ($(this).val() == 'OPACIF') {
                    element.css('display', 'inline');
                    labelElement.css('display', 'inline');
                    $name = 'OPACIF';
                    listeOpcaOpacif($name, element);
                } else {
                    labelElement.css('display', 'none');
                    element.css('display', 'none');
                    element.empty();
                }
            }
        });


        //modification du champ input de l'organisme en select
        $(function () {
            $('.organisme_organisme').each(function () {
                parent = $(this).parent().parent();
                nomOrganisme = $(parent).find('#' + $(parent).attr('id') + '_nom');
                var newElement = $('<select>');
                $.each(this.attributes, function (i, attrib) {
                    $(newElement).attr(attrib.name, attrib.value);
                });
                $(this).replaceWith(newElement);
            });
        });

        $(function () {
            $a = 1;
            $('.widget_financeur').children().children().children().each(function () {
                if ($(this).get(0).tagName == 'LABEL') {
                    $(this).text('Financeur ' + $a);
                    $a++;
                }
            })
        });

        $(function () {
            $('select.organisme_organisme').each(function () {
                parent = $(this).parent().parent();
                nomOrganisme = $(parent).find('#' + $(parent).attr('id') + '_nom');
                labelElement = parent.find('.organisme_organisme_label');

                element = $(this);
                console.log($(this).val());
                if ($(nomOrganisme).val() == 'OPCA') {
                    element.css('display', 'inline');
                    labelElement.css('display', 'inline');
                    $name = 'OPCA';
                    listeOpcaOpacif($name, element);
                } else {
                    if ($(nomOrganisme).val() == 'OPACIF') {
                        element.css('display', 'inline');
                        labelElement.css('display', 'inline');
                        $name = 'OPACIF';
                        listeOpcaOpacif($name, element);
                    }
                }
            });
        });


        //Ajax pour recuperer la liste des opca ou opacif
        function listeOpcaOpacif($name, element) {
            $.ajax({
                url: Routing.generate('application_opca_opacif_financeur', {'nom': $name}), // le nom du fichier indiqué dans le formulaire
                cache: true,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    element.empty();
                    console.log('ça charge');
                },
                success: function (data) {
                    $.each(JSON.parse(data), function (i, item) {
                        console.log(item);
                        if ($(element).attr('value') == item) {
                            element.append("<option value=\"" + item + "\" selected = \"selected\">" + item + "</option>");
                        } else {
                            element.append("<option value=\"" + item + "\">" + item + "</option>");
                        }
                    });
                    console.log('fini');
                }
            });
        }

        $(function () {
            $('#accompagnement_financeur_0_nom').prop('disabled', true).css({
                'background-color': 'rgba(117, 115, 115, 0.48)',
                'cursor' : 'not-allowed',
                'border' : 'none'
            });
            $('#accompagnement_financeur_0_organisme').prop('disabled', true).css({
                'background-color': 'rgba(117, 115, 115, 0.48)',
                'cursor' : 'not-allowed',
                'border' : 'none'
            });
            $('.organisme_organisme').parent().css('margin-bottom','13px');
        });
    });

}

if(document.getElementById('beneficiaire_contactEmployeur')) {
    //ajouter supprimer dinamiqueme contact employeur
    $(document).ready(function () {
        // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
        var $container = $('div#beneficiaire_contactEmployeur');
        // On ajoute un lien pour ajouter une nouvelle catégorie
        //var $addLink = $('#add_contact_employeur');

        // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
        /*
         $addLink.click(function(e) {
         addImage($container);
         e.preventDefault(); // évite qu'un # apparaisse dans l'URL
         return false;
         });
         */
        // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
        var index = $container.find(':input').length;
        // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
        if (index == 0) {
            addFirstContact($container);
        } else {
            // Pour chaque catégorie déjà existante, on ajoute un lien de suppression
            /*
             $container.children('div').each(function() {
             addDeleteLink($(this));
             });
             */
        }

        function addFirstContact($container) {
            var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Contact n°' + (index + 1))
                .replace(/__name__/g, index));
            $container.append($prototype);
            index++;
        }

        // La fonction qui ajoute un formulaire Image
        function addContact($container) {
            // Dans le contenu de l'attribut « data-prototype », on remplace :
            // - le texte "__name__label__" qu'il contient par le label du champ
            // - le texte "__name__" qu'il contient par le numéro du champ
            var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Contact n°' + (index + 1))
                .replace(/__name__/g, 'Contact n°' + (index + 1)));
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
            $deleteLink = $('<a href="#" style="padding: 0px;" class="btn btn-danger">Supprimer</a>');
            // Ajout du lien
            $prototype.append($deleteLink);
            // Ajout du listener sur le clic du lien
            $deleteLink.click(function (e) {
                $prototype.remove();
                e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                return false;
            });
        }
    });
}
