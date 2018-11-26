// Création d'un objet FormData
var identite = new FormData();
// Ajout d'information dans l'objet
identite.append("login", "Bob");
identite.append("password", "azerty");
// Création et configuration d'une requête HTTP POST vers le fichier post_form.php
var req = new XMLHttpRequest();
req.open("POST", "https://www.renatech.org/projet-dev/webService/post_form.php");
// Envoi de la requête en y incluant l'objet
req.send(identite);


var commande = new FormData();
commande.append("couleur", "rouge");
commande.append("pointure", "43");
// Envoi de l'objet FormData au serveur
ajaxPost("https://www.renatech.org/projet-dev/webService/post_form.php", commande,
    function (reponse) {
        // Affichage dans la console en cas de succès
        console.log("Commande envoyée au serveur");
    }
);

var form = document.querySelector("form");
// Gestion de la soumission du formulaire
form.addEventListener("submit", function (e) {
    e.preventDefault();
    // Récupération des champs du formulaire dans l'objet FormData
    var data = new FormData(form);
    // Envoi des données du formulaire au serveur
    // La fonction callback est ici vide
    ajaxPost("https://www.renatech.org/projet-dev/webService/post_form.php", data, function () {});
});

// Création d'un objet représentant un film
var film = {
    titre: "Zootopie",
    annee: "2016",
    realisateur: "Byron Howard et Rich Moore"
};
// Envoi de l'objet au serveur
ajaxPost("https://www.renatech.org/projet-dev/webService/post_json.php", film,
    function (reponse) {
        // Le film est affiché dans la console en cas de succès
        console.log(JSON.stringify(film));
    },
    true // Valeur du paramètre isJson
);