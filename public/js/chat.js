const formChat = document.querySelector(".typing-area");

if (formChat != null) {
    //éviter un erreur dans le page dashboard.blade.php

    inputField = formChat.querySelector(".form-control");
    sendBtn = formChat.querySelector("button");
    chatBox = document.querySelector("#chat-box");

    formChat.onsubmit = (e) => {
        e.preventDefault();
    };

    sendBtn.onclick = () => {
        let xhr = new XMLHttpRequest(); // création d'object XML
        xhr.open("POST", "/insertChat", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    inputField.value = ""; // vide le champ message lorsque les données sont  enregistrées dans la base de données
                    scrollBottom();
                }
            }
        };

        // envoyer les données à partir d'ajax jusqu'à Controlleur Laravel
        let formData = new FormData(formChat); // création d'un nouveau object formData
        xhr.send(formData); //envoyer le form data au Controlleur de laravel
    };

    chatBox.onmouseenter = () => {
        chatBox.classList.add("active");
    };
    chatBox.onmouseleave = () => {
        chatBox.classList.remove("active");
    };

    setInterval(() => {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/getChat", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    chatBox.innerHTML = data;

                    if (!chatBox.classList.contains("active")) {
                        // si le chatBox n'a pas un classe active alors scroll automatique
                        scrollBottom();
                    }
                }
            }
        };

        // envoyer les données à partir d'ajax jusqu'à Controlleur de laravel
        let formData = new FormData(formChat); // création d'un nouveau object formData
        xhr.send(formData); //envoyer le form data au Controlleur de laravel
    }, 500);

    function scrollBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
}
