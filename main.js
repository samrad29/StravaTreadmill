function setFormMessage(formElement, type, message) {
    const messageElement = formElement.querySelector(".form__message");

    messageElement.textContent = message;
    messageElement.classList.remove("form__message--success", "form__message--error");
    messageElement.classList.add(`form__message--${type}`);
}

function setInputError(inputElement, message) {
    inputElement.classList.add("form__input--error");
    inputElement.parentElement.querySelector(".form__input-error-message").textContent = message;
}

function clearInputError(inputElement) {
    inputElement.classList.remove("form__input--error");
    inputElement.parentElement.querySelector(".form__input-error-message").textContent = "";
}

document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.querySelector("#login");
    
    loginForm.addEventListener("submit", e => {
        e.preventDefault();
        var $uName = $('#loginUserName');
        var $pWord = $('#loginPassword');

        setFormMessage(loginForm, "error", "Invalid username/password combination");

        var loginInfo  = {
            email: $uName.val(),
            password: $pWord.val()
        };

        $.ajax({
            type: 'POST',
            url: 'API/login.php',
            data: loginInfo,
            success: function(response) {
                if (response.includes('Login successful')) {
                    window.location.href = 'treadmillForm.html'; // Redirect on success
                }
            },
            error: function() {
                alert('Error loggin in')
            }
        });
        
        

        setFormMessage(loginForm, "success", "something is working");
    });

    document.querySelectorAll(".form__input").forEach(inputElement => {
        inputElement.addEventListener("blur", e => {
            if (e.target.id === "signupUsername" && e.target.value.length > 0 && e.target.value.length < 10) {
                setInputError(inputElement, "Username must be at least 10 characters in length");
            }
        });

        inputElement.addEventListener("input", e => {
            clearInputError(inputElement);
        });
    });
});