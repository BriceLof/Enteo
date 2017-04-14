function getClient() {
    $.ajax({
        url: Routing.generate('application_get_client_evenement'),
        beforeSend: function () {
        },
        success: function (data) {
            console.log("client chargé")
        }
    });
}

(function () {
    document.addEventListener('click',function () {
        $('#error_slot_busy').css("display","none");
    })
    function afficheMessageFlash() {
        var flash = document.getElementById("flashbag");
        if (flash != undefined) {
            flash.style.display = 'none';
        }
    }
    setTimeout(afficheMessageFlash,5000);
})();

(function () {
    div = $('#iframe');
    var iframe = document.querySelector('#iframe iframe');
    if(iframe != undefined){
        iframe.width = div.width();
    }
})();

if(document.getElementById('admin_calendar_consultant_consultant')) {
    (function () {
        console.log($('#numero_consultant').val());
        if ($('#numero_consultant').val() != undefined){
            $('#admin_calendar_consultant_consultant option[value="'+$('#numero_consultant').val()+'"]').prop('selected','selected');
            $('#admin_calendar_consultant option[value="'+$('#numero_consultant').val()+'"]').prop('selected','selected');
            $('#admin_calendar').css('display','block');
            var iframe = document.querySelector('#iframe iframe');
            iframe.width = div.width();
        }

        $('#admin_calendar_consultant option[value="'+$('#admin_calendar_consultant_consultant').val()+'"]').prop('selected','selected');
        var consultants = document.getElementById('admin_calendar_consultant_consultant');
            consultants.addEventListener('change', function () {
            if($(this).val() == "" ){
            }else {
                $.ajax({
                    url: Routing.generate('user_ajax_get_user', {id: $(this).children("option:selected").val()}),
                    cache: true,
                    dataType: 'json',
                    beforeSend: function () {
                        //à rajouter un chargement
                        $('#admin_calendar_nom').val("");
                        $('#admin_calendar_prenom').val("");
                        $('#admin_calendar_beneficiaire').val(null);
                        $('#admin_formulaire_calendar').find("div.error").each(function (index) {
                            $(this).remove();
                        })
                    },
                    success: function (data) {
                        $('#nom_consultant').html('Agenda de <span style="color: #668cd9;">' + data.data[0].nom + ' ' + data.data[0].prenom + '</span>');
                        $('#agenda_consultant').html(data.data[0].calendrieruri);
                        $('#admin_calendar_consultant option[value="'+data.data[0].id+'"]').prop('selected','selected');
                        var iframe = document.querySelector('#iframe iframe');
                        iframe.width = div.width();
                    }
                });
            }
        })
    })();
}


//autocompletion beneficiaire et bureau
(function () {
    $('#admin_calendar_nom').autocomplete({
        source : function(requete, reponse) {
            $.ajax({
                url: Routing.generate('application_list_beneficiaire'), // le nom du fichier indiqué dans le formulaire
                cache: true,
                data: {
                    nom : $('#admin_calendar_nom').val(),
                    consultant : $('#admin_calendar_consultant_consultant').val()
                },
                dataType: 'json',
                beforeSend: function () {

                },
                success : function (data) {
                    var beneficiaire = $.parseJSON(data);
                    reponse($.map(beneficiaire, function (item) {
                        return{
                            value : function(){
                                $('#admin_calendar_prenom').val(item.prenomConso);
                                id = item.id;
                                prenom = item.prenomConso;
                                return item.nomConso;
                            }
                        }
                    }));

                }
            });
        },
        select: function (event, ui) {
            $('#admin_calendar_beneficiaire').val(id);
            $('#admin_calendar_prenom').val(prenom);
        },
        change: function (event, ui) {
            if (ui.item) {
                $('#admin_calendar_beneficiaire').val(id);
                $('#admin_calendar_prenom').val(prenom);
            }else{
                $('#admin_calendar_beneficiaire').val(null);
                $('#admin_calendar_prenom').val("");
            }
        },
    });

    $('#admin_calendar_autreBureau').on('change',function () {
        if( $('#admin_calendar_autreBureau').is(':checked')){
            $('#admin_calendar_nomBureau').prop('disabled', false).val("");
            $('#admin_calendar_adresseBureau').prop('disabled', false).val("");
            $('#admin_calendar_ville').prop('disabled', false).val("");
            $('#admin_calendar_bureau').val("");
        }else{
            $('#admin_calendar_nomBureau').prop('disabled', true).empty();
            $('#admin_calendar_adresseBureau').prop('disabled', true).empty();
            $('#admin_calendar_ville').empty();
            $('#admin_calendar_cpBureau').prop('disabled', true).empty();
            $('#admin_calendar_bureau').val("");
        }
    });
    $('#admin_calendar_ville').autocomplete({
        source : function(requete, reponse) {
            if( $('#admin_calendar_autreBureau').is(':checked')) {
                $.ajax({
                    url: Routing.generate('application_get_ville'), // le nom du fichier indiqué dans le formulaire
                    cache: true,
                    data: {
                        nomVille: $('#admin_calendar_ville').val()
                    },
                    dataType: 'json',
                    beforeSend: function () {
                    },
                    success: function (data) {
                        var ville = $.parseJSON(data);
                        reponse($.map(ville, function (item) {
                            return {
                                value: function () {
                                    cp = (item.cp);
                                    return item.nom;
                                }
                            }
                        }));
                    }
                });
            }else {
                $.ajax({
                    url: Routing.generate('application_ajax_search_bureau'), // le nom du fichier indiqué dans le formulaire
                    cache: true,
                    data: {
                        nomVille: $('#admin_calendar_ville').val()
                    },
                    dataType: 'json',
                    beforeSend: function () {

                    },
                    success: function (data) {
                        var bureaux = $.parseJSON(data);
                        reponse($.map(bureaux, function (item) {
                            return {
                                value: function () {
                                    // $('#admin_calendar_adresseBureau').val(item.adresse);
                                    bureau = item.adresse;
                                    $('#admin_calendar_cpBureau').val(item.cp);
                                    $('#admin_calendar_adresseBureau').val(item.adresse);
                                    $('#admin_calendar_nomBureau').val(item.nom);
                                    $('#admin_calendar_bureau').val(item.id);
                                    cp = (item.cp);
                                    return item.ville;
                                }
                            }
                        }));
                    }
                });
            }
        },
        select: function (event, ui) {
            $('#admin_calendar_cpBureau').val(cp);
        },
        change: function(event, ui) {
            if (ui.item) {
                $('#admin_calendar_cpBureau').val(cp);
            } else {
                $('#admin_calendar_nomBureau').val("");
                $('#admin_calendar_adresseBureau').val("");
                $('#admin_calendar_cpBureau').val("");
                $('#admin_calendar_bureau').val("");
                if ($('#admin_calendar_ville').hasClass("testError")){
                }else{
                    $('#admin_calendar_ville').addClass("testError");
                }
            }
            console.log("this.value: " + this.value);
        }
    })
})();

(function () {
    var rad = document.getElementsByName('admin_calendar[typerdv]');
    var champBureau = document.getElementById('champ_bureau');
    var prev = null;
    if ($('#admin_calendar_typerdv_1').is(':checked')){
        champBureau.style.display = 'none';
    }

    for(var i = 0; i < rad.length; i++) {
        rad[i].onclick = function() {
            if(this !== prev) {
                prev = this;
            }
            console.log(this.value)
            if(this.value == 'distantiel'){
                champBureau.style.display = 'none';
            }else{
                champBureau.style.display = 'block';
            }
        };
    }

    if(document.getElementById('admin_calendar_summary')){
        var summary = document.getElementById('admin_calendar_summary');
        var autreSummary = document.getElementById('autre_summary');
        summary.addEventListener('change',function () {
            if(summary.options[summary.selectedIndex].value === 'Autre'){
                autreSummary.style.display = 'block';
            }else{
                autreSummary.style.display = 'none';
            }
        });
    }

    var tableOnglet = document.getElementById('admin_calendar');
    $('#admin_calendar_consultant_consultant').on('change',function () {
        if($('#admin_calendar_consultant_consultant').val() == "" ){
            tableOnglet.style.display = 'none';
        }else{
            tableOnglet.style.display = 'block';
        }
    });


})();

(function () {
    jQuery.validator.addMethod("noBeneficiaire",
        function (value, element) {
            var beneficiaire = $('#admin_calendar_beneficiaire');
            console.log(beneficiaire.val())
            if(value != null && beneficiaire.val() == null){
                return false;
            }else{
                return true;
            }
        }, "Aucun bénéficiaire trouvé"
    );

    jQuery.validator.addMethod(
        "villeEntheor",
        function(value, element) {
            if( $('#admin_calendar_autreBureau').is(':checked')) {
                if($(element).hasClass('testError')){
                    $(element).removeClass('testError')
                    return false;
                }
                else{
                    return true;
                }
            }else{
                var bureau = $('#admin_calendar_nomBureau');
                if(value != null && bureau.val() == ""){
                    return false;
                }else{
                    return true;
                }
            }
        },"Veuillez choisir la ville"
    );

    //validation jquery
    $( function() {
        $("#form_admin_event").validate({
            rules: {
                "admin_calendar[consultant]":{
                    "required": true
                },
                "admin_calendar[nom]":{
                    "required": true,
                    "noBeneficiaire": true
                },
                "admin_calendar[prenom]":{
                    "required": true
                },
                "admin_calendar[ville]":{
                    "villeEntheor": true
                },
                "admin_calendar[nomBureau]":{
                    "required": function () {
                        return ($('#admin_calendar_autreBureau').is(':checked'))
                    }
                },
                "admin_calendar[adresseBureau]":{
                    "required": function () {
                        return ($('#admin_calendar_autreBureau').is(':checked'))
                    }
                },
                "admin_calendar_cpBureau":{
                    "required": function () {
                        return ($('#admin_calendar_autreBureau').is(':checked'))
                    }
                },
            },
            errorElement: 'div',
            submitHandler: function (form) {
                $.ajax({
                    url: Routing.generate('application_ajax_busy_slot_evenement'),
                    type: $(form).attr('method'),
                    cache: true,
                    data: $(form).serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        $('#admin_calendar_submit').prop('disabled',true)
                    },
                    success: function (data) {
                        if(JSON.parse(data) == true){
                            form.submit();
                        }else{
                            $('#error_slot_busy').css("display","block");
                            $('#admin_calendar_submit').prop('disabled',false)
                        }
                    }
                });
            }
        })
    });
})();

//date et heure de rdv
(function () {
    //mise à jour de la date
    dateCourant = new Date();
    jourCourant = dateCourant.getDate(); // jour
    moisCourant = dateCourant.getMonth()+1; // mois +1 (parce que le mois.val commence par 1)
    anneeCourant = dateCourant.getFullYear(); // année
    //mise a jour de la date on change
    $('#admin_calendar_dateDebut select').on('change', function () {
        if($('#admin_calendar_dateDebut_year').val() == anneeCourant){
            //si le jour est inferieure a la date du jour, on augment le mois
            if($('#admin_calendar_dateDebut_month').val() <= moisCourant && $('#admin_calendar_dateDebut_day').val() < jourCourant){
                if($(this).attr('id') == 'admin_calendar_dateDebut_month'){
                    value = anneeCourant + 1;
                    $('#admin_calendar_dateDebut_year option[value="'+value+'"]').prop('selected', true)
                }else{
                    value = moisCourant+1;
                    $('#admin_calendar_dateDebut_month option[value="'+value+'"]').prop('selected', true)
                }
            }
        }
    });
    //mise à jour de l'heure et la minute
    $('#admin_calendar_heureDebut_hour').on('change', function () {
        var value = $(this).val();
        value++;
        $('#admin_calendar_heureFin_hour option[value="'+value+'"]').prop('selected', true);
        $('#admin_calendar_heureFin_hour option').each(function () {
            if($(this).val() < value -1 ){
                $(this).attr('disabled', 'disabled');
            }
        })
    });
    $('#admin_calendar_heureDebut_hour option').each(function () {
        if($(this).val()>20 || $(this).val()< 7){
            $(this).attr('disabled', 'disabled');
        }
    });
    $('#admin_calendar_heureFin_hour option').each(function () {
        if($(this).val()>21 || $(this).val()< 8){
            $(this).attr('disabled', 'disabled');
        }
    });
    $('#admin_calendar_heureDebut_minute').on('change', function () {
        $('#admin_calendar_heureFin_minute option[value="'+$(this).val()+'"]').prop('selected', true)
    });
})();

//disponibilités
(function () {
    $('#application_plateformebundle_disponibilites_villeNom').autocomplete({
        source : function(requete, reponse) {
            $.ajax({
                url: Routing.generate('application_get_ville'), // le nom du fichier indiqué dans le formulaire
                cache: true,
                data: {
                    nomVille: $('#application_plateformebundle_disponibilites_villeNom').val()
                },
                dataType: 'json',
                beforeSend: function () {
                },
                success: function (data) {
                    var ville = $.parseJSON(data);
                    reponse($.map(ville, function (item) {
                        return {
                            value: function () {
                                id = (item.id);
                                return item.nom;
                            }
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            $('#application_plateformebundle_disponibilites_villeId').val(id);
        },
        change: function(event, ui) {
            if (ui.item) {
                $('#application_plateformebundle_disponibilites_villeId').val(id);
                $('#application_plateformebundle_disponibilites_villeNom').removeClass("villeError")
            } else {
                $('#application_plateformebundle_disponibilites_villeId').val(null);
                if ($('#application_plateformebundle_disponibilites_villeNom').hasClass("villeError")){
                }else{
                    $('#application_plateformebundle_disponibilites_villeNom').addClass("villeError");
                }
            }
        }
    });

    jQuery.validator.addMethod("noVille",
        function (value, element) {
            if($('#application_plateformebundle_disponibilites_villeNom').hasClass("villeError")){
                return false;
            }else{
                return true;
            }
        }, "Veuillez choisir la ville"
    );

    //validation jquery
    $("#form_dispo").validate({
        rules: {
            "application_plateformebundle_disponibilites[villeNom]":{
                "noVille": true
            }
        }
    });

    dateCourant = new Date();
    jourCourant = dateCourant.getDate(); // jour
    moisCourant = dateCourant.getMonth()+1; // mois +1 (parce que le mois.val commence par 1)
    anneeCourant = dateCourant.getFullYear(); // année
    //mise a jour de la date on change
    $('#application_plateformebundle_disponibilites_date select').on('change', function () {
        if($('#application_plateformebundle_disponibilites_date_year').val() == anneeCourant){
            //si le jour est inferieure a la date du jour, on augment le mois
            if($('#application_plateformebundle_disponibilites_date_month').val() <= moisCourant && $('#application_plateformebundle_disponibilites_date_day').val() < jourCourant){
                if($(this).attr('id') == 'application_plateformebundle_disponibilites_date_month'){
                    value = anneeCourant + 1;
                    $('#application_plateformebundle_disponibilites_date_year option[value="'+value+'"]').prop('selected', true)
                }else{
                    value = moisCourant+1;
                    $('#application_plateformebundle_disponibilites_date_month option[value="'+value+'"]').prop('selected', true)
                }
            }
        }
    });
    //mise à jour de l'heure et la minute
    $('#application_plateformebundle_disponibilites_dateDebuts_hour').on('change', function () {
        var value = $(this).val();
        value ++;
        if($('#application_plateformebundle_disponibilites_dateFins_hour option:selected').val() < value){
            $('#application_plateformebundle_disponibilites_dateFins_hour option[value="'+value+'"]').prop('selected', true);
        }
        $('#application_plateformebundle_disponibilites_dateFins_hour option').each(function () {
            if($(this).val() < value ){
                $(this).attr('disabled', 'disabled');
            }
        })
    });
    $('#application_plateformebundle_disponibilites_dateDebuts_hour option').each(function () {
        if($(this).val()>20 || $(this).val()< 7){
            $(this).attr('disabled', 'disabled');
        }
    });
    $('#application_plateformebundle_disponibilites_dateFins_hour option').each(function () {
        if($(this).val()>21 || $(this).val()< 8){
            $(this).attr('disabled', 'disabled');
        }
    });
    $('#application_plateformebundle_disponibilites_dateDebuts_minute').on('change', function () {
        $('#application_plateformebundle_disponibilites_dateFins_minute option[value="'+$(this).val()+'"]').prop('selected', true)
    });
})();


//bureau
(function () {
    $('#form_villeBureau').autocomplete({
        source : function(requete, reponse) {
            $.ajax({
                url: Routing.generate('application_ajax_search_bureau'), // le nom du fichier indiqué dans le formulaire
                cache: true,
                data: {
                    nomVille: $('#form_villeBureau').val(),
                    isBureauCalendar : 'yes'    //cette valeur est juste la pour differencier celui la avec le formulaire dans adminAgenda
                },
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (data) {
                    console.log(data);
                    var bureaux = $.parseJSON(data);
                    reponse($.map(bureaux, function (item) {
                        return {
                            value: function () {
                                idVille = item.idVille;
                                return item.ville;
                            }
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            console.log(idVille);
            $.ajax({
                url: Routing.generate('application_show_bureau'), // le nom du fichier indiqué dans le formulaire
                cache: true,
                data: {
                    id: idVille
                },
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (data) {

                    $('#iframe iframe').remove();
                    $('#liste_bureau_ville div').remove();
                    $('#liste_autre_bureau_ville').css('display','none');
                    $('#liste_autre_bureau_ville div').remove();


                    var resultat = $.parseJSON(data);
                    //mise en place de l'iframe
                    $('#iframe').html(resultat.iframe);
                    var iframe = document.querySelector('#iframe iframe');
                    iframe.width = div.width();

                    //mise en place de la liste des villes
                    for(var i=0; i<resultat.tabs.length;i++){
                        if(resultat.tabs[i].sentinel == 1){
                            html = '<div><span class="glyphicon glyphicon-stop" style="color:'+ resultat.tabs[i].color +';margin-right: 10px"></span>'+
                                   '<span style="margin-right: 10px">'+ resultat.tabs[i].nomBureau +'</span><input type="checkbox" id="bureau_'+ i +'" data-id="'+ resultat.tabs[i].calendrierId +'" data-color ="'+ resultat.tabs[i].googleColor +'" onclick="addRemove(this)" checked></div>';
                            $('#liste_bureau_ville').append(html);
                        }else{
                            $('#liste_autre_bureau_ville').css('display','block');
                            html = '<div><span class="glyphicon glyphicon-stop" style="color:'+ resultat.tabs[i].color +';margin-right: 10px"></span>'+
                                '<span style="margin-right: 10px">'+ resultat.tabs[i].nomBureau +' ('+resultat.tabs[i].ville+')</span><input type="checkbox" id="bureau_'+ i +'" data-id="'+ resultat.tabs[i].calendrierId +'" data-color ="'+ resultat.tabs[i].googleColor +'"  onclick="addRemove(this)"></div>';
                            $('#liste_autre_bureau_ville').append(html);
                        }
                    }
                }
            });
        },
        change: function(event, ui) {
            console.log("this.value: " + this.value);
        }
    });

})();

function addRemove(el) {
    iframe = $('#iframe');
    calendar = "src=" + $(el).attr('data-id') + "&amp;color=" + $(el).attr('data-color') + "&amp;";
    html = iframe.html();

    if (html.includes(calendar)){
        newHtml = html.replace(calendar,"");
    }else{
        newHtml = html.slice(0,144)+ calendar + html.slice(144,html.length)
    }
    iframe.html(newHtml);
}