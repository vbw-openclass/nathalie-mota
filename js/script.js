// On attend que le contenu du DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', (event) => {

    const modal = document.getElementById('contactModal');
    const btns = document.getElementsByClassName('contact-link');

    // On ajoute un écouteur de clic à chaque bouton 'contact-link'
    for(let i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function(event){
            // On empêche le comportement par défaut du clic (qui serait de suivre le lien)
            event.preventDefault();

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

