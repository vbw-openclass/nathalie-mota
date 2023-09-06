document.addEventListener('DOMContentLoaded', function() {
    const lightbox = document.getElementById('lightbox');
    const img = document.getElementById('lightbox-img');
    const prevButton = document.getElementById('prev-button');
    const nextButton = document.getElementById('next-button');

    let allImagesForLightbox = [];

    function loadAllImagesForLightbox() {
        fetch('http://localhost/nathalie-mota/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=load_all_photos_for_lightbox',
        })
        .then(response => response.json())
        .then(data => {
            allImagesForLightbox = data;
        })
        .catch((error) => {
            console.error('Erreur lors du chargement de toutes les photos pour la lightbox:', error);
        });
    }

    function openLightbox(imageSrc, reference, category) {
        img.src = imageSrc;
        lightbox.classList.remove('hidden');
        loadAllImagesForLightbox(); // Charger toutes les images
    
        // Trouver ou créer les éléments DOM pour afficher 'reference' et 'category'
        let referenceElement = document.querySelector('.lightbox-reference');
        let categoryElement = document.querySelector('.lightbox-category');
    
        // Si l'élément pour 'reference' n'existe pas, le créer
        if (!referenceElement) {
            referenceElement = document.createElement('span');
            referenceElement.classList.add('lightbox-reference');
            lightbox.appendChild(referenceElement);
        }
    
        // Si l'élément pour 'category' n'existe pas, le créer
        if (!categoryElement) {
            categoryElement = document.createElement('span');
            categoryElement.classList.add('lightbox-category');
            lightbox.appendChild(categoryElement);
        }
    
        // Mettre à jour les textes
        referenceElement.innerText = reference;
        categoryElement.innerText = category;
    }

    function navigate(direction) {
        const currentSrc = img.src;
        let currentIndex;

        allImagesForLightbox.forEach(function(imageData, index) {
            if (imageData.url === currentSrc) {
                currentIndex = index;
            }
        });

        let newIndex;
        if (direction === 'prev') {
            newIndex = currentIndex + 1;
        } else {
            newIndex = currentIndex - 1;
        }

        if (newIndex >= 0 && newIndex < allImagesForLightbox.length) {
            const newImgData = allImagesForLightbox[newIndex];
            const newImgSrc = newImgData.url;
            const newReference = newImgData.reference;
            const newCategory = newImgData.category;
            openLightbox(newImgSrc, newReference, newCategory);
        }
    }

    document.querySelectorAll('.fullscreen-icon').forEach(function(icon, index) {
        icon.addEventListener('click', function(event) {
            const imgElement = event.target.closest('.img-apparente').querySelector('img');
            const imgSrc = imgElement.src;
            
            let clickedImage;
            allImagesForLightbox.forEach(function(imageData) {
                if (imageData.url === imgSrc) {
                    clickedImage = imageData;
                }
            });
    
            if (clickedImage) {
                openLightbox(clickedImage.url, clickedImage.reference, clickedImage.category);
            }
        });
    });

    prevButton.addEventListener('click', function() {
        navigate('prev');
    });

    nextButton.addEventListener('click', function() {
        navigate('next');
    });

    lightbox.addEventListener('click', function(event) {
        if (event.target === lightbox || event.target === img) {
            lightbox.classList.add('hidden');
        }
    });
    loadAllImagesForLightbox();
});