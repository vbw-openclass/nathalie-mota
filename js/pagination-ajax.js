jQuery(document).ready(function($) {

    var pageNum = 2;  // Car la première page est déjà affichée
    var maxPages = 100;

    $('#load-more-btn').click(function() {
        $.ajax({
            url: myAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_photos',
                page: pageNum
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