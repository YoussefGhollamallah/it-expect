document.addEventListener("DOMContentLoaded", () => {
    const burger = document.getElementById("burger");
    const nav = document.querySelector("nav");
    const autocompleteList = document.getElementById("autocompleteList"); // Assurez-vous que cet ID existe dans votre HTML.

    // Gestion du menu burger
    burger.addEventListener("click", () => {
        nav.classList.toggle("active");
    });

    // Fonction pour mettre à jour la liste d'autocomplétion
    function updateAutocompleteList(results) {
        if (!autocompleteList) return; // Sécurité si l'élément n'existe pas

        autocompleteList.innerHTML = ''; // Vider la liste existante

        results.slice(0, 10).forEach(result => { // Limiter à 10 résultats
            const li = document.createElement('li');
            const img = document.createElement('img');
            const span = document.createElement('span');

            // Utiliser l'image si elle existe, sinon une image par défaut
            img.src = result.poster_path
                ? `https://image.tmdb.org/t/p/w200${result.poster_path}`
                : 'https://via.placeholder.com/50x75?text=No+Image';
            img.alt = result.title || result.name || 'Image indisponible';
            img.loading = "lazy"; // Chargement différé pour les performances

            // Texte du titre (film ou série)
            span.textContent = result.title || result.name || 'Titre inconnu';

            li.appendChild(img);
            li.appendChild(span);

            // Ajouter l'événement pour rediriger
            li.addEventListener('click', () => {
                const id = result.id;
                const type = result.media_type === 'movie' ? 'film' : 'serie'; // Gestion des types
                window.location.href = `http://localhost/cinetech/detail/${id}/${type}`;
            });

            autocompleteList.appendChild(li);
        });
    }

    // Fonction pour récupérer les résultats via l'API TMDb
    async function fetchAutocompleteResults(query) {
        if (!query) {
            autocompleteList.innerHTML = ''; // Si la requête est vide, on vide la liste
            return;
        }

        const apiKey = "ea22993e5b3ec7acfb93c59ddf265f8c";
        const url = `https://api.themoviedb.org/3/search/multi?api_key=${apiKey}&query=${encodeURIComponent(query)}&language=fr-FR`;

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`Erreur API : ${response.statusText}`);
            }

            const data = await response.json();
            updateAutocompleteList(data.results); // Mettre à jour la liste avec les résultats
        } catch (error) {
            console.error("Erreur lors de la récupération des résultats :", error);
        }
    }

    // Gestion de l'événement sur le champ de recherche
    const searchInput = document.getElementById("searchInput"); // Assurez-vous que cet ID existe dans votre HTML
    if (searchInput) {
        searchInput.addEventListener("input", (event) => {
            const query = event.target.value.trim();
            fetchAutocompleteResults(query); // Lancer la recherche à chaque saisie
        });
    }
});
