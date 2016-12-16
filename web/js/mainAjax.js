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
                    "required": true
                },
                "accompagnement[heure]":{
                    "required": true,
                    "number" : true
                },
                "accompagnement[tarif]":{
                    "required": true,
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
                    "required": true,
                    "texte": /^([a-z ]-?,?)+$/i
                },
                "beneficiaire[prenomConso]":{
                    "required": true,
                    "texte": /^([a-z ]-?,?)+$/i
                },
                "beneficiaire[poste]":{
                    "required": true,
                    "texte": /^([a-z ]-?,?)+$/i
                },
                "beneficiaire[encadrement]":{
                    "required": true
                },
                "beneficiaire[telConso]":{
                    "required": true,
                    "tel": /^0[1-8][0-9]{8}$/
                },
                "beneficiaire[tel2]":{
                    "required": true,
                    "tel": /^0[1-8][0-9]{8}$/
                },
                "beneficiaire[emailConso]":{
                    "required": true,
                    "email": true
                },
                "beneficiaire[email2]":{
                    "required": true,
                    "email": true
                },
                "beneficiaire[adresse]":{
                    "required": true
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
                    "required": true,
                    "numSecu": /^[12][0-9]{14}$/
                },
                "beneficiaire[dateNaissance]":{
                    "required": true
                }
            },
            errorElement: 'div'
        })
    });


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
});


// ---------------------------------------------- //
// --- Affichage calendrier d'un consultant ----- //
// ---------------------------------------------- //
$('#consultantC').change(function(){
    // vide le bloc
    emptyelement('blocacalendar', 'class');
    // Affichage du calendrier dans le bloc
    $('.blocacalendar').append($('#consultantC').val());
    // modification de la largeur du calendrier
    $('.blocacalendar iframe').attr('width', '100%')
});

// vide un bloc
function emptyelement(element, type){
    switch(type){
        case 'id':
            $('#'+element).empty();
            break;
        case 'class':
            $('.'+element).empty();
            break;
    }
}


(function(){
    var plusMoins = document.getElementById('plusMoins');
    plusMoins.onclick = function () {
        if(plusMoins.innerHTML == 'voir plus +') {
            document.getElementById('rechercherPlus').style.display = 'block';
            plusMoins.innerHTML = 'voir moins -';
        }else{
            document.getElementById('rechercherPlus').style.display = 'none';
            plusMoins.innerHTML = 'voir plus +';
        }
    };

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
        flash.style.display='none';
    }
    setTimeout(afficheMessageFlash,5000);
})();




