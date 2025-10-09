import { loadAdminPage } from './dashboard-admin.js';
import { loadDriverPage } from './dashboard-driver.js';

const cookie = document.cookie;
const token = getCookie("token");

console.log("token "+ token);

function fetchRole()
{
    const data = { 
        endpoint: "user/get_role",
        token: token    
    };

    fetch("../php/api/api.php", {
        method: "POST",
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data =>{
        if(data.status == "success")
        {
            const role = data.role;

            console.log("Role:", role);

            switch(role)
            {
                case "admin":
                    loadAdminPage();
                    break;
                case "driver":
                    loadDriverPage();
                    break;
                default:
                    loadForbidden();
            }
        }
        else
        {
            console.log("Error:", data);
        }
    })
}

fetchRole();

function loadForbidden()
{
    console.log("forbidden");
}

function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}