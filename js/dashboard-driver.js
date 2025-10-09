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

export function loadDriverPage()
{
    console.log("load driver page");

    loadOrders();

    gridMain.appendChild(firstRow);
}

const firstRow  = row.cloneNode();

// == ORDERS CONTAINER ===

function loadOrders()
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
        "Accept"
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
        const data = {endpoint: "trip/get_awaiting"};

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
                    
                    let btnDetails = document.createElement("button");
                    btnDetails.classList.add("btn");
                    btnDetails.classList.add("btn-primary");
                    btnDetails.textContent = "Accept";
                    btnDetails.addEventListener("click", () => getTripDetails(trip.id));
                    tableRow.appendChild(btnDetails);

                    ordersTableBody.appendChild(tableRow);
                }});
            }
            else
            {
                alert(data.message);
                console.log("Error:", data);
            }
        })
    }

    function getTripDetails(id)
    {
        console.log("Get trip " + id + " details");
    }

    function searchOrders()
    {
        fetchAllOrders(ordersSearchInput.value);
    }
}

