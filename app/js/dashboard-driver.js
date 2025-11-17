import {
    gridMain, 
    refreshButton, 
    row, 
    container, 
    containerHead, 
    containerHeadTitle,
    containerBody,
    searchInput,
    table,
    thead,
    tbody,
    tr,
    th
} from "./dashboard-elements.js";

let token;

export function loadDriverPage(token)
{
    console.log("load driver page");
    loadOrders(token);

    gridMain.appendChild(firstRow);
}
console.log(token)
const firstRow  = row.cloneNode();

// == ORDERS CONTAINER ===

function loadOrders(token)
{
    // Init container
    const ordersContainer = container.cloneNode();
    ordersContainer.classList.add("col-8");

    // == ORDERS CONTAINER HEAD ===

    let ordersHead = containerHead.cloneNode();

    // Container title
    let ordersTitle = containerHeadTitle.cloneNode();
    ordersTitle.textContent = "Orders";
    ordersHead.appendChild(ordersTitle);

    // Search Input
    let ordersSearchInput = searchInput.cloneNode();
    ordersSearchInput.setAttribute("placeholder", "Search:");
    ordersSearchInput.setAttribute("id", "searchOrders");
    ordersSearchInput.addEventListener("input",searchOrders);

    ordersHead.appendChild(ordersSearchInput);

    // Refresh button
    let ordersRefreshButton = refreshButton.cloneNode();
    ordersRefreshButton.setAttribute("id", "fetchorders");
    ordersHead.appendChild(ordersRefreshButton);

    ordersContainer.appendChild(ordersHead);

    // == orders CONTAINER BODY ===

    let ordersBody = containerBody.cloneNode();

    // == orders TABLE ==
    let ordersTable = table.cloneNode();
    let ordersTableHead = thead.cloneNode();
    let ordersTableHeadRow = tr.cloneNode();

    // trip table columns
    let ordersHeaderCols = 
    [
        "#", 
        "Date", 
        "Vehicle", 
        "Zone start",
        "Zone end",
        "Status",
        "Accept",
        "Decline"
    ];

    // Populate table header with columns
    ordersHeaderCols.forEach(column => {
        let col = document.createElement("th");
        // col.setAttribute("scope", "col");
        col.textContent = column;

        ordersTableHeadRow.appendChild(col);
    });

    let ordersTableBody = tbody.cloneNode();

    ordersTableHead.appendChild(ordersTableHeadRow);
    ordersTable.appendChild(ordersTableHead);
    ordersTable.appendChild(ordersTableBody);

    ordersBody.appendChild(ordersTable);

    ordersContainer.appendChild(ordersBody);
    firstRow.appendChild(ordersContainer);

    fetchAllOrders();

    function fetchAllOrders(keyword = "")
    {
        console.log("fetch orders");
        const data = {endpoint: "trip/get_orders"};

        fetch("../php/api/api.php", {
            method: "POST",
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data =>{
            if(data.status == "success")
            {
                console.log(data);

                ordersTableBody.textContent = "";

                const orders = data.orders;
                orders.forEach(trip => {
                    //verifie si le trip est déja refusé
                    let declinedTrip = JSON.parse(localStorage.getItem("declinedTrip"));
                    let result = true;
                    if (declinedTrip != null){
                        declinedTrip.forEach(declined => {
                            result = declined == trip.id ? false : result
                        })
                    }
                    if (result == true){
                    //------------
                        if(keyword == "" 
                        || keyword == null 
                        || (trip.startTime != null && trip.startTime.toLowerCase().includes(keyword.toLowerCase()))
                        || (trip.endTime != null && trip.endTime.toLowerCase().includes(keyword.toLowerCase())) 
                        || (trip.status != null && trip.status.toLowerCase().includes(keyword.toLowerCase()))
                        )
                        {
                            let tableRow = document.createElement('tr');
                        
                            let idCell = document.createElement('td');
                            let startDateCell = document.createElement('td');
                            let endDateCell  = document.createElement('td');
                            let driverCell  = document.createElement('td');
                            let vehicleCell  = document.createElement('td');
                            let statusCell  = document.createElement('td');
                            let acceptCell = document.createElement('td');
                            let declineCell = document.createElement('td');

                            idCell.textContent          = trip.id;
                            startDateCell.textContent   = trip.startTime ? trip.startTime : "N/A";
                            endDateCell.textContent     = trip.endTime ? trip.endTime : "N/A";
                            driverCell.textContent      = trip.driver ? ("("+trip.driver.id+") " + trip.driver.firstName + " " +  trip.driver.lastName) : "N/A";
                            vehicleCell.textContent     = trip.vehicle ? ("("+trip.vehicle.id+") " + trip.vehicle.licensePlate + " " +  trip.vehicle.type.nameEn) : "N/A";
                            statusCell.textContent      = trip.status;

                            tableRow.appendChild(idCell);
                            tableRow.appendChild(startDateCell);
                            tableRow.appendChild(endDateCell);
                            tableRow.appendChild(driverCell);
                            tableRow.appendChild(vehicleCell);
                            tableRow.appendChild(statusCell);
                            
                            let BtnAccept = document.createElement("button");
                            BtnAccept.classList.add("btn");
                            BtnAccept.classList.add("btn-primary");
                            BtnAccept.textContent = "Accept";
                            BtnAccept.addEventListener("click", () => updateTrip(trip.id));
                            acceptCell.appendChild(BtnAccept);

                            let btnDetails = document.createElement("button");
                            btnDetails.classList.add("btn");
                            btnDetails.classList.add("btn-danger");
                            btnDetails.textContent = "Decline";
                            btnDetails.addEventListener("click", () => declineTrip(trip.id));
                            declineCell.appendChild(btnDetails);

                            tableRow.appendChild(acceptCell);
                            tableRow.appendChild(declineCell);
                            ordersTableBody.appendChild(tableRow);
                        }
                    }
                });
            }
            else
            {
                alert(data.message);
                console.log("Error:", data);
            }
        })
    }
    //Fonction qui va decliner les trajets (il sont stocké dans le local storage)
    function declineTrip(TheTrip){
        console.log("click")
        let declinedTrip = JSON.parse(localStorage.getItem("declinedTrip"));
        if (declinedTrip == null){
            declinedTrip = [TheTrip]
        }else{
            declinedTrip.push(TheTrip)
        }
        localStorage.setItem("declinedTrip", JSON.stringify(declinedTrip));
        location.reload(); 
    }
    //------------
    // fonction qui va appeler l'api avec le token du driver l'id du trip et reload la page si tout est fonctionel
    function updateTrip(TheTrip)
    {
           const data = { 
                endpoint: "trip/Accept",
                token: token,
                trip: TheTrip    
            };
            console.log(data)
            fetch("../php/api/api.php", {
                method: "PUT",
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data =>{
                if(data.status == "success")
                {
                    console.log("Update avec succès")
                    location.reload(); 
                }
                else
                {
                    console.log("Error:", data);
                }
            })
    }
    //------------
    function searchOrders()
    {
        fetchAllOrders(ordersSearchInput.value);
    }
}

