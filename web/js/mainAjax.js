$("document").ready(function (initDynamicContent) {
    //affichage de news dans la partie bénéficiaire
    $(function () {
        $("#tabNews").tabs();
    });

    $(function () {
        $(".form-control .error").css('border', '1px solid red');
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
    $(function () {
        $("form input.date").datepicker({
            dateFormat: 'dd/mm/yy',
            yearRange: '1940:2016',
            firstDay: 1,
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
            if (this.classList.contains('modified')) {
            } else {
                this.className += ' modified';
            }
        });
    });

    $(function () {
        $(".accompagnementDate").datepicker({
            dateFormat: 'dd/mm/yy',
            yearRange: '2017:2030',
            firstDay: 1,
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
            if (this.classList.contains('modified')) {
            } else {
                this.className += ' modified';
            }
        });
    });

    jQuery.validator.addMethod("greaterThan",
        function (value, element) {
            if ($('#accompagnement_dateDebut').val() != "" && value != "") {
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
        function (value, element, regexp) {
            if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
            else if (regexp.global)
                regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        }, "erreur sur le tarif (format : xxx.xx )"
    );

    jQuery.validator.addMethod(
        "tel",
        function (value, element, regexp) {
            if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
            else if (regexp.global)
                regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        }, "ce numéro de téléphone n'est pas valide"
    );

    jQuery.validator.addMethod(
        "numSecu",
        function (value, element, regexp) {
            if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
            else if (regexp.global)
                regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        }, "ce numéro de sécurité sociale n'est pas valide"
    );

    jQuery.validator.addMethod(
        "dateBR",
        function (value, element, regexp) {
            if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
            else if (regexp.global)
                regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        }, "Date non valide ( format : dd/mm/yyyy )"
    );

    $.validator.addMethod(
        "australianDate",
        function (value, element) {
            // put your own logic here, this is just a (crappy) example
            return value.match(/^(0?[1-9]|[12][0-9]|3[0-1])[/., -](0?[1-9]|1[0-2])[/., -](19|20)?\d{2}$/);
        },
        "Date non valide ( format : dd/mm/yyyy )."
    );

    jQuery.validator.methods["date"] = function (value, element) {
        return true;
    };

    jQuery.validator.addMethod(
        "texte",
        function (value, element, regexp) {
            if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
            else if (regexp.global)
                regexp.lastIndex = 0;
            return this.optional(element) || regexp.test(value);
        }, "Verifiez votre saisie"
    );

    //validation jquery accompagnement
    $(function () {
        $("#accompagnementEditForm").validate({
            rules: {
                "accompagnement[opcaOpacif]": {
                    "required": false
                },
                "accompagnement[heure]": {
                    "required": false,
                    "number": true
                },
                "accompagnement[tarif]": {
                    "required": false,
                    "tarif": /^[1-9][0-9]+(\.?([0-9]?[1-9]|[1-9][0-9]?))?$/
                },
                "accompagnement[dateDebut]": {
                    "required": false,
                    "dateBR": /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/,
                },
                "accompagnement[dateFin]": {
                    "dateBR": /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/,
                    "greaterThan": true,
                    "required": false
                },
                "accompagnement[financeur][0][nom]": {
                    "required": function () {
                        return ($('#accompagnement_financeur_0_montant').val() != "" || $('#accompagnement_financeur_0_dateAccord').val() != "" );
                    }
                },
                "accompagnement[financeur][0][organisme]": {
                    "required": function () {
                        return ($('#accompagnement_financeur_0_nom').val() != 'OPCA' || $('#accompagnement_financeur_0_nom').val() != 'OPACIF' );
                    }
                },
                "accompagnement[financeur][0][montant]": {
                    "number": true,
                    "required": function () {
                        return ($('#accompagnement_financeur_0_nom').val() != "" || $('#accompagnement_financeur_0_dateAccord').val() != "" );
                    }
                },
                "accompagnement[financeur][0][dateAccord]": {
                    "required": false
                },

                "accompagnement[financeur][1][nom]": {
                    "required": function () {
                        return ($('#accompagnement_financeur_1_montant').val() != "" || $('#accompagnement_financeur_1_dateAccord').val() != "" );
                    }
                },
                "accompagnement[financeur][1][organisme]": {
                    "required": function () {
                        return ($('#accompagnement_financeur_1_nom').val() != 'OPCA' || $('#accompagnement_financeur_1_nom').val() != 'OPACIF' );
                    }
                },
                "accompagnement[financeur][1][montant]": {
                    "number": true,
                    "required": function () {
                        return ($('#accompagnement_financeur_1_nom').val() != "" || $('#accompagnement_financeur_1_dateAccord').val() != "" );
                    }
                },
                "accompagnement[financeur][1][dateAccord]": {
                    "required": false
                }
            },
            errorElement: 'div'
        })
    });

    //validation jquery
    $(function () {
        $("#suiviAdministratifEditForm").validate({
            rules: {
                "suivi_administratif[qui]": {
                    "required": true
                },
                "suivi_administratif[quoi]": {
                    "required": true
                }
            },
            errorElement: 'div'
        })
    });

    $(function () {
        $("#newDocumentsForm").validate({
            errorElement: 'div'
        })
    });
    //validation jquery
    $(function () {
        $("#projetEditForm").validate({
            rules: {
                "projet[experience]": {
                    "required": false
                },
                "projet[heureDif]": {
                    "required": false,
                    "number": true
                },
                "projet[heureCpf]": {
                    "required": false,
                    "number": true
                }
            },
            errorElement: 'div'
        })
    });

    //espace documentaire validate
    $(function () {
        $("#newDocumentsForm").validate({
            rules: {
                "espace_documentaire_documents_0_description": {
                    "required": true
                },
                "suivi_administratif[quoi]": {
                    "required": true
                }
            },
            errorElement: 'div'
        })
    });

    //validation jquery de la fiche beneficiaire
    $(function () {
        $("#ficheBeneficiaireForm").validate({
            rules: {
                "beneficiaire[dateConfMer]": {
                    "required": true
                },
                "beneficiaire[civiliteConso]": {
                    "required": true
                },
                "beneficiaire[nomConso]": {
                    "required": true
                },
                "beneficiaire[prenomConso]": {
                    "required": true
                },
                "beneficiaire[poste]": {
                    "required": false
                },
                "beneficiaire[encadrement]": {
                    "required": false
                },
                "beneficiaire[telConso]": {
                    "required": true,
                    "tel": /^0[1-8]([\s]?[0-9]{2}){4}$/
                },
                "beneficiaire[tel2]": {
                    "required": false,
                    "tel": /^0[1-8]([\s]?[0-9]{2}){4}$/
                },
                "beneficiaire[emailConso]": {
                    "required": true,
                    "email": true
                },
                "beneficiaire[email2]": {
                    "required": false,
                    "email": true
                },
                "beneficiaire[adresse]": {
                    "required": false
                },
                "beneficiaire[ville][zip]": {
                    "required": true
                },
                "beneficiaire[ville][nom]": {
                    "required": true
                },
                "beneficiaire[pays]": {
                    "required": true,
                    "texte": /^([a-z ]-?,?)+$/i
                },
                "beneficiaire[numSecu]": {
                    "required": false,
                    "numSecu": /^[12][0-9]{12}$/
                },
                "beneficiaire[numSecuCle]": {
                    "required": false,
                },
                "beneficiaire[dateNaissance]": {
                    "required": false
                },
                "beneficiaire[dptTravail]": {
                    "required": function () {
                        return ($('#beneficiaire_regionTravail').val() != "" );
                    },
                    "texte": /^[0-9]{2}$/
                }
            },
            errorElement: 'div'
        })
    });

    $(".cp").keyup(function () {
        if ($(this).val().length >= 2) {
            $.ajax({
                type: 'get',
                url: Routing.generate('application_search_ville', {cp: $(this).val()}),
                beforeSend: function () {
                    console.log('ça charge !');
                },
                success: function (data) {
                    var id = $(".ville").attr("id");
                    var name = $(".ville").attr("name");
                    $(".ville").replaceWith('<select id=' + id + ' name=' + name + ' class="ville form-control">');

                    $.each(data.ville, function (index, value) {
                        $(".ville").append("<option data-cp=" + value.cp + " value=" + value.id + ">" + value.nom + "</option>")
                    });

                    $("#recherche_beneficiaire_ville_nom").change(function () {
                        villeSelected = $('option:selected', this).attr('data-cp');
                        $(".cp").val(villeSelected)
                    })
                }
            })
        } else {
            $(".ville").val('');
        }
    });


    //ajax formulaire de recherche dans la home
    $("#ajaxForm").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: Routing.generate('application_ajax_search_home'), // le nom du fichier indiqué dans le formulaire
            type: $(this).attr('method'), // la méthode indiquée dans le formulaire (get ou post)
            data: $(this).serialize(),
            cache: true,
            dataType: 'json',
            beforeSend: function () {
                var resultat = document.getElementById('section_home');
                resultat.innerHTML = "";
                resultat.innerHTML += '<div class="loading"></div>';
                $('#pagination').empty();
            },
            success: function (response) {
                template = response;
                $('#section_home').html(template);
            }
        });
    });
});

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
