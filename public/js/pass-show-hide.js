const pswrField = document.querySelector('.form-group input[type="password"]');
const pswrFieldConfirm = document.querySelector(
    '#confirm input[type="password"]'
);
toggleBtn = document.querySelector(".form-group i");
toggleBtnConfirm = document.querySelector("#confirm i");

if (toggleBtn != null) {
    toggleBtn.onclick = () => {
        if (pswrField.type == "password") {
            pswrField.type = "text";
            toggleBtn.classList.add("active");
        } else {
            pswrField.type = "password";
            toggleBtn.classList.remove("active");
        }
    };

    if (toggleBtnConfirm != null) {
        toggleBtnConfirm.onclick = () => {
            if (pswrFieldConfirm.type == "password") {
                pswrFieldConfirm.type = "text";
                toggleBtnConfirm.classList.add("active");
            } else {
                pswrFieldConfirm.type = "password";
                toggleBtnConfirm.classList.remove("active");
            }
        };
    }
}
