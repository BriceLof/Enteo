function getClient() {
    $.ajax({
        url: Routing.generate('application_get_client_evenement'),
        beforeSend: function () {
        },
        success: function (data) {
            console.log("client chargé")
        }
    });
    return true;
}

(function () {
    div = $('#iframe');
    var iframe = document.querySelector('#iframe iframe');
    if(iframe != undefined){
        iframe.width = div.width();
    }
})();


(function () {
    var consultants = document.getElementById('admin_calendar_consultant');
    consultants.addEventListener('change',function () {
        $.ajax({
            url: Routing.generate('user_ajax_get_user', { id : $(this).children("option:selected").val()}),
            cache : true,
            dataType : 'json',
            beforeSend: function () {
                //à rajouter un chargement
            },
            success: function (data) {
                console.log(data.data[0]);
                $('#nom_consultant').text('Agenda de '+ data.data[0].nom +' '+data.data[0].prenom);
                $('#agenda_consultant').html(data.data[0].calendrieruri);
                var iframe = document.querySelector('#iframe iframe');
                iframe.width = div.width();
            }
        });
    })
})();


//autocompletion beneficiaire et bureau
(function () {
    $('#admin_calendar_nom').autocomplete({
        source : function(requete, reponse) {
            $.ajax({
                url: Routing.generate('application_list_beneficiaire'), // le nom du fichier indiqué dans le formulaire
                cache: true,
                data: {
                    nom : $('#admin_calendar_nom').val(),
                    prenom : $('#admin_calendar_prenom').val()
                },
                dataType: 'json',
                beforeSend: function () {

                },
                success : function (data) {
                    var beneficiaire = $.parseJSON(data);
                    reponse($.map(beneficiaire, function (item) {
                        console.log(item);
                        return{
                            value : function(){
                                $('#admin_calendar_beneficiaire').val(item.id);
                                $('#admin_calendar_prenom').val(item.prenomConso);
                                return item.nomConso;
                            }
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {

        }
    });

    $('#admin_calendar_ville').autocomplete({
        source : function(requete, reponse) {
            $.ajax({
                url: Routing.generate('application_ajax_search_bureau'), // le nom du fichier indiqué dans le formulaire
                cache: true,
                data: {
                    nomVille : $('#admin_calendar_ville').val()
                },
                dataType: 'json',
                beforeSend: function () {

                },
                success : function (data) {
                    var bureaux = $.parseJSON(data);
                    console.log(bureaux);
                    reponse($.map(bureaux,function (item) {
                        return{
                            value : function(){
                                $('#admin_calendar_adresseBureau').val(item.adresse);
                                $('#admin_calendar_cpBureau').val(item.cp);
                                $('#admin_calendar_nomBureau').val(item.nom);
                                $('#admin_calendar_bureau').val(item.id);
                                return item.ville;
                            }
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {

        }
    })
})();

(function () {
    var rad = document.getElementsByName('admin_calendar[typerdv]');
    var champBureau = document.getElementById('champ_bureau');
    var prev = null;
    for(var i = 0; i < rad.length; i++) {
        rad[i].onclick = function() {
            if(this !== prev) {
                prev = this;
            }
            console.log(this.value);
            if(this.value == 'distantiel'){
                champBureau.style.display = 'none';
            }else{
                champBureau.style.display = 'block';
            }
        };
    }
})();