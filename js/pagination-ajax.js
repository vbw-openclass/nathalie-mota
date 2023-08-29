jQuery(document).ready(function($) {

    var maxPages = 100;

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
                $('.container-photo-apparente').append(response);
                pageNum++;

                if (pageNum > maxPages) {
                    $('#load-more-container').hide();
                }
            },
            error: function() {
                console.error('Erreur lors du chargement des photos.');
            }
        });
    });
});