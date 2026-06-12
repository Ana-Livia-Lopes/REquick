const form = document.getElementById("loginForm");
const email = document.getElementById("email");
const password = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

togglePassword.addEventListener("click", () => {

    const type =
        password.getAttribute("type") === "password"
            ? "text"
            : "password";

    password.setAttribute("type", type);

    togglePassword.innerHTML =
        type === "password"
            ? '<i class="fa-regular fa-eye"></i>'
            : '<i class="fa-regular fa-eye-slash"></i>';
});

form.addEventListener("submit", function(e){

    let errors = [];

    const emailRegex =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(!emailRegex.test(email.value)){
        errors.push("Digite um email válido.");
    }

    if(password.value.length < 6){
        errors.push(
            "A senha deve possuir pelo menos 6 caracteres."
        );
    }

    if(errors.length > 0){

        e.preventDefault();

        alert(errors.join("\n"));
    }

});