// On attend que le contenu du DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', (event) => {

    // Récupération des éléments du DOM
    const modal = document.getElementById('contact-modal');
    const btns = document.getElementsByClassName('contact-link');
    const referencePhotoField = document.getElementById('reference'); // Champ de formulaire pour la référence de la photo

    // Ajout d'un écouteur de clic à chaque bouton 'contact-link'
    for(let i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function(event){
            // Empêche le comportement par défaut du clic (naviguer vers le lien)
            event.preventDefault();

            // Si le champ de référence est trouvé, on met à jour sa valeur
            if (referencePhotoField) {
                const referenceValue = this.dataset.reference;
                if (referenceValue) {
                    referencePhotoField.value = referenceValue;
                }
            } else {
                console.error("L'élément avec l'ID 'reference' n'a pas été trouvé.");
            }

            // Affiche la modale de contact
            modal.style.display = "block";
        });
    }

    // Ferme la modale si l'utilisateur clique en dehors de celle-ci
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Écouteur pour l'événement déclenché lorsque le formulaire de Contact Form 7 est soumis avec succès
    document.addEventListener( 'wpcf7mailsent', function( event ) {
        alert('Message envoyé !');
        setTimeout(function(){ 
            modal.style.display = "none"; 
        }, 3000);
    }, false );

    // Écouteur pour l'événement déclenché lorsque le formulaire de Contact Form 7 contient des erreurs
    document.addEventListener( 'wpcf7invalid', function( event ) {
        alert('Veuillez remplir tous les champs correctement !');
    }, false );
});

// Initialisation du numéro de la page pour la pagination
var pageNum = 2;  

// Utilisation de jQuery pour faire des requêtes AJAX
jQuery(document).ready(function($) {
    // Charger les catégories
    $.ajax({
        url: 'http://localhost/nathalie-mota/wp-json/wp/v2/categorie',
        method: 'GET',
        success: function(data) {
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

    // Écouteur pour le changement des filtres
    $('#filtre-categorie, #filtre-format, #filtre-date').change(function() {
        pageNum = 2; // Réinitialisation du numéro de la page si un filtre est appliqué
        var categorie = $('#filtre-categorie').val();
        var format = $('#filtre-format').val();
        var annee = $('#filtre-date').val();

        // Requête AJAX pour charger plus de photos en fonction des filtres
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
                // Met à jour le contenu de la grille de photos
                $('.container-photo-apparente').html(data);
            }
        });
    });
});

jQuery(document).ready(function($) {
    $('select').select2();

    $('.custom-select2-behavior').on('select2:open', function() {
        // Quand le sélecteur est ouvert
        var selectedValue = $(this).val();
        var placeholder = $(this).data('placeholder'); // Utiliser le data-placeholder pour dynamiser le texte

        if (selectedValue !== placeholder) {
            $(this).data('previous-value', selectedValue); // Stocker la valeur précédente
            $(this).val('').trigger('change.select2'); // Changer la valeur pour afficher le placeholder
        }
    }).on('select2:close', function() {
        // Quand le sélecteur est fermé
        var storedValue = $(this).data('previous-value');
        if (storedValue) {
            $(this).val(storedValue).trigger('change.select2'); // Restaurer la valeur précédente
        }
    });
});