const loginForm = document.querySelector("#loginForm");

(() => {
    'use strict'

    loginForm.addEventListener("submit", event => {
        event.preventDefault();
        event.stopPropagation();

        //Display errors
        loginForm.classList.add('was-validated');

        if(loginForm.checkValidity())
        {
            console.log("Form is valid");

            const formData = new FormData(loginForm);

            const data = { 
              endpoint: "user/log_in",
              username: formData.get("username"),
              password: formData.get("password")
            };

            console.log(JSON.stringify(data));

            fetch("php/api/api.php", {
                method: "POST",
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data =>{
                if(data.status == "success")
                {
                    console.log("Success:", data.message);
                    resetInputs();
                }
                else
                {
                    console.log("Error:", data.message);
                }
            })
        }
    })
})();

function resetInputs()
{
    loginForm.classList.remove('was-validated');
}