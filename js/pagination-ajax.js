jQuery(document).ready(function($) {

    // Cette fonction retourne les valeurs actuelles des filtres.
    function getCurrentFilters() {
        return {
            categorie: $('#filtre-categorie').val(),
            format: $('#filtre-format').val(),
            annee: $('#filtre-date').val()
        };
    }

    //Déclenchement de la fonction au clique du bouton charger plus
    $('#load-more-btn').click(function() {
        var currentFilters = getCurrentFilters();  // Récupérations des filtres actuels

        // Requête AJAX pour charger plus de photos
        $.ajax({
            url: myAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_photos',
                page: pageNum,
                categorie: currentFilters.categorie,
                format: currentFilters.format,
                annee: currentFilters.annee
            },
            success: function(response) {
                if (response.trim() === '') {  // vérifier si la réponse est vide
                    // Ajoutez un message pour informer qu'il n'y a plus de données à charger
                    $('.container-photo-apparente').append('<p>Pas de photos supplémentaires à charger.</p>');
                } else {
                    $('.container-photo-apparente').append(response); // ajouter les nouvelles données
                    pageNum++;
                }
            },
            error: function() {
                console.error('Erreur lors du chargement des photos.');
            }
        });
    });
});