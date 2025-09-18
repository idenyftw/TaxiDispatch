const signupForm        = document.querySelector("#signupForm");

(() => {
    'use strict'

    signupForm.addEventListener("submit", event => {
        event.preventDefault();
        event.stopPropagation();

        //Display errors
        signupForm.classList.add('was-validated');

        if(signupForm.checkValidity())
        {
            console.log("Form is valid");

            const formData = new FormData(signupForm);

            const data = { 
              endpoint: "user/sign_up",
              first_name: formData.get("first_name"),
              last_name: formData.get("last_name"),
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
                    console.log("Error:", result.message);
                }
            })
        }
    })
})();

function resetInputs()
{
    inputUsername.value = "";
    inputPassword.value = "";
    signupForm.classList.remove('was-validated');
}