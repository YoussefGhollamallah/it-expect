document.addEventListener('DOMContentLoaded', () => { 
    const serieUrl = `https://api.themoviedb.org/3/tv/popular?api_key=ea22993e5b3ec7acfb93c59ddf265f8c&language=fr-FR`;
    const movieUrl = `https://api.themoviedb.org/3/movie/popular?api_key=ea22993e5b3ec7acfb93c59ddf265f8c&language=fr-FR`;

    // Fonction pour initialiser un slider avec 4 éléments visibles
    function initializeSlider(data, container, type) {
        let currentIndex = 0;
        const numberOfItems = 4; // Afficher 4 éléments à la fois

        // Fonction pour afficher 4 éléments
        function renderSlide() {
            container.innerHTML = ''; // Réinitialise le conteneur avant de l'afficher
            const itemsToShow = data.slice(currentIndex, currentIndex + numberOfItems); // Récupère 4 éléments

            // Afficher les 4 éléments
            itemsToShow.forEach(item => {
                const imageUrl = item.poster_path ? `https://image.tmdb.org/t/p/w500/${item.poster_path}` : 'default-image.jpg';
                const itemElement = document.createElement('div');
                itemElement.classList.add('slide-item');
                itemElement.innerHTML = `
                    <img src="${imageUrl}" alt="Affiche de ${item.name || item.title}">
                    <h2>${item.name || item.title}</h2>
                `;

                // Ajouter un gestionnaire d'événement pour rediriger vers la page de détails
                itemElement.addEventListener('click', function() {
                    const id = item.id;
                    const url = type === 'movie' ? `detail/${id}/film` : `detail/${id}/serie`;
                    window.location.href = url; // Redirige vers la page de détails
                });

                container.appendChild(itemElement);
            });
        }

        // Fonction pour passer au slide suivant (déplacer d'un élément)
        function showNextSlide(event) {
            event.preventDefault(); // Empêche le rechargement de la page
            currentIndex = (currentIndex + 1) % data.length; // Passe au film/série suivant
            if (currentIndex + numberOfItems > data.length) {
                currentIndex = 0; // Revient au début si on dépasse la liste
            }
            renderSlide();
        }

        // Fonction pour passer au slide précédent (déplacer d'un élément)
        function showPreviousSlide(event) {
            event.preventDefault(); // Empêche le rechargement de la page
            currentIndex = (currentIndex - 1 + data.length) % data.length; // Passe au film/série précédent
            renderSlide();
        }

        // Ajout des boutons pour contrôler le slider dans un conteneur centré
        const buttonsContainer = document.createElement('div');
        buttonsContainer.classList.add('slider-buttons');

        const nextButton = document.createElement('button');
        nextButton.textContent = 'Suivant';
        nextButton.addEventListener('click', showNextSlide);

        const prevButton = document.createElement('button');
        prevButton.textContent = 'Précédent';
        prevButton.addEventListener('click', showPreviousSlide);

        buttonsContainer.appendChild(prevButton);
        buttonsContainer.appendChild(nextButton);

        container.parentNode.appendChild(buttonsContainer);

        // Affiche le premier groupe de 4 films/séries
        renderSlide();

        // Slide automatique tous les 7 secondes (un film/série à la fois)
        setInterval(() => {
            showNextSlide(new Event('click')); // Déclenche le même comportement que le clic sur le bouton "Suivant"
        }, 7000); // Passe au film/série suivant tous les 7 secondes
    }

    // Fonction asynchrone pour récupérer et afficher les séries
    async function fetchSeries() {
        try {
            const response = await fetch(serieUrl);
            const data = await response.json();
            if (data && data.results) {
                const serieContainer = document.querySelector('#tv');
                initializeSlider(data.results, serieContainer, 'serie');
            } else {
                console.error('Aucun résultat pour les séries.');
            }
        } catch (error) {
            console.error('Erreur lors de la récupération des séries:', error);
        }
    }

    // Fonction asynchrone pour récupérer et afficher les films
    async function fetchMovies() {
        try {
            const response = await fetch(movieUrl);
            const data = await response.json();
            if (data && data.results) {
                const movieContainer = document.querySelector('#movie');
                initializeSlider(data.results, movieContainer, 'movie');
            } else {
                console.error('Aucun résultat pour les films.');
            }
        } catch (error) {
            console.error('Erreur lors de la récupération des films:', error);
        }
    }

    // Récupérer les séries populaires
    fetchSeries();

    // Récupérer les films populaires
    fetchMovies();
});

