// On attend que le contenu du DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', (event) => {

    const modal = document.getElementById('contact-modal');
    const btns = document.getElementsByClassName('contact-link');
    const referencePhotoField = document.getElementById('reference'); // Champ de formulaire de référence

    // On ajoute un écouteur de clic à chaque bouton 'contact-link'
    for(let i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function(event){
            // On empêche le comportement par défaut du clic (qui serait de suivre le lien)
            event.preventDefault();

            if (referencePhotoField) {
                const referenceValue = this.dataset.reference;
                if (referenceValue) {
                    referencePhotoField.value = referenceValue;
                }
            } else {
                console.error("L'élément avec l'ID 'reference' n'a pas été trouvé.");
            }

            // On change le style de la modale pour qu'elle soit affichée
            modal.style.display = "block";
        });
    }

    // On ajoute un écouteur de clic pour toute la fenêtre
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // On ajoute un écouteur d'événements pour l'événement 'wpcf7mailsent', qui est déclenché lorsque un formulaire de Contact Form 7 est correctement soumis
    document.addEventListener( 'wpcf7mailsent', function( event ) {
        // On affiche une alerte indiquant que le message a été envoyé
        alert('Message envoyé !');

        // Après un délai de 3 secondes, on cache la modale
        setTimeout(function(){ 
            modal.style.display = "none"; 
        }, 3000);
    }, false );

    // On ajoute un écouteur d'événements pour l'événement 'wpcf7invalid', qui est déclenché lorsque un formulaire de Contact Form 7 est soumis avec des données invalides
    document.addEventListener( 'wpcf7invalid', function( event ) {
        // On affiche une alerte indiquant que tous les champs doivent être correctement remplis
        alert('Veuillez remplir tous les champs correctement !');
    }, false );
});

var pageNum = 2;  

// Cette requête AJAX sert à charger les catégories depuis un endpoint API.
jQuery(document).ready(function($) {
    // Charger les catégories
    $.ajax({
        url: 'http://localhost/nathalie-mota/wp-json/wp/v2/categorie',
        method: 'GET',
        success: function(data) {// Fonction à exécuter si la requête est un succès.
            // Pour chaque objet "categorie" dans les données retournées,
            // on ajoute une nouvelle option au menu déroulant "filtre-categorie".
            data.forEach(function(categorie) {
                $('#filtre-categorie').append('<option value="' + categorie.id + '">' + categorie.name + '</option>');
            });
        }
    });

    // Charger les formats
    $.ajax({
        url: 'http://localhost/nathalie-mota/wp-json/wp/v2/format',
        method: 'GET',
        success: function(data) {
            data.forEach(function(format) {
                $('#filtre-format').append('<option value="' + format.id + '">' + format.name + '</option>');
            });
        }
    });

    // Événement de changement pour les filtres
    $('#filtre-categorie, #filtre-format, #filtre-date').change(function() {
        pageNum = 2;  // Réinitialisez le numéro de la page seulement si un filtre est appliqué
        var categorie = $('#filtre-categorie').val();
        var format = $('#filtre-format').val();
        var annee = $('#filtre-date').val();



        $.ajax({
            url: my_ajax_object.ajax_url,
            method: 'POST',
            data: {
                action: 'load_more_photos',
                categorie: categorie,
                format: format,
                annee: annee
            },
            success: function(data) {
                // Mettre à jour le catalogue de photos
                $('.container-photo-apparente').html(data);
            }
        });
    });
});



