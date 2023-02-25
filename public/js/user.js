const form = document.querySelector(".form-search");
const searchBar = document.querySelector(".card .form-search .form-control");
const usersList = document.querySelector(".card .users-list");

if (form != null) {
    //éviter un erreur dans le page chat.blade.php
    form.onsubmit = (e) => {
        e.preventDefault();
    };

    searchBar.onkeyup = () => {
        let termeCherche = searchBar.value;

        if (termeCherche != "") {
            searchBar.classList.add("active");
        } else {
            searchBar.classList.remove("active");
        }

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/search", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    usersList.innerHTML = data;
                }
            }
        };
        // envoyer les données à partir d'ajax jusqu'à Controlleur de laravel
        let formData = new FormData(form); // création d'un nouveau object formData
        xhr.send(formData); //envoyer le form data au Controlleur de laravel
    };

    setInterval(() => {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "/users", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    if (!searchBar.classList.contains("active")) {
                        // si l'utilisateur ne fait pas un recherche
                        usersList.innerHTML = data;
                    }
                }
            }
        };
        xhr.send();
    }, 500);
}
