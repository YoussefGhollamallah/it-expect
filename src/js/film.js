document.addEventListener('DOMContentLoaded', function() {
    
    
    const filmUrl = `https://api.themoviedb.org/3/movie/popular?api_key=ea22993e5b3ec7acfb93c59ddf265f8c&language=fr-FR`;

    fetch(filmUrl)
        .then(response => response.json())
        .then(data => {
            console.log('Réponse de l\'API:', data); // Vérifiez la réponse de l'API
            if (data && data.results) {
                const filmDetailsContainer = document.querySelector('#film');
                filmDetailsContainer.innerHTML = ''; // Réinitialiser le conteneur avant de l'alimenter avec de nouveaux films

                // Afficher chaque film
                data.results.forEach(film => {
                    const imageUrl = film.poster_path ? `https://image.tmdb.org/t/p/w500/${film.poster_path}` : 'default-image.jpg';

                    // Créer l'élément pour chaque film
                    const filmElement = document.createElement('div');
                    filmElement.classList.add('film'); // Classe CSS pour personnaliser l'affichage

                    filmElement.innerHTML = `
                        <img src="${imageUrl}" alt="Affiche du film ${film.title}">
                        <h2>${film.title}</h2>
                        <button onclick="showDetail(${film.id})">Détail</button>
                    `;


                    // Ajouter l'élément du film au conteneur
                    filmDetailsContainer.appendChild(filmElement);
                });
            } else {
                console.error('Aucun film trouvé.');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des films populaires:', error);
        });
});

function showDetail(filmId) {
    window.location.href = `detail/${filmId}/film`;
}
