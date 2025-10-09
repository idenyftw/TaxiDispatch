export function loadAdminPage()
{
    console.log("load admin page");

    loadTrips();
    loadZones();

    //Append rows
    gridMain.appendChild(firstRow);
    gridMain.appendChild(secondRow);
}

const gridMain = document.querySelector("#gridMain");

const refreshSvg =  
`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
</svg>`

const row = document.createElement("div");
row.classList.add("row"); 

const firstRow  = row;
const secondRow = row;

const container = document.createElement("div");
container.classList.add("container", "frost", "p-4");

const containerHead = document.createElement("div");
containerHead.classList.add("d-flex", "p-1");

const containerHeadTitle = document.createElement("h3");
containerHeadTitle.classList.add("me-auto");

const searchInput = document.createElement("input");
searchInput.setAttribute("type", "text");

const refreshButton = document.createElement("button");
refreshButton.classList.add("btn", "btn-primary");
refreshButton.innerHTML = refreshSvg;

const containerBody = document.createElement("div");

const table = document.createElement("table");
table.classList.add("table");

const thead = document.createElement("thead"); 
const tbody = document.createElement("tbody"); 
const tr = document.createElement("tr"); 
const th = document.createElement("th");

// == TRIPS CONTAINER ===

function loadTrips()
{
    // Init container
    const tripsContainer = container.cloneNode();
    tripsContainer.classList.add("col-8");

    // == TRIPS CONTAINER HEAD ===

    let tripsHead = containerHead.cloneNode();

    // Container title
    let tripsTitle = containerHeadTitle.cloneNode();
    tripsTitle.textContent = "Trips";
    tripsHead.appendChild(tripsTitle);

    // Search Input
    let tripsSearchInput = searchInput.cloneNode();
    tripsSearchInput.setAttribute("placeholder", "Search:");
    tripsSearchInput.setAttribute("id", "searchTrips");
    tripsSearchInput.addEventListener("input",searchTrips);

    tripsHead.appendChild(tripsSearchInput);

    // Refresh button
    let tripsRefreshButton = refreshButton.cloneNode();
    tripsRefreshButton.setAttribute("id", "fetchTrips");
    tripsHead.appendChild(tripsRefreshButton);

    tripsContainer.appendChild(tripsHead);

    // == TRIPS CONTAINER BODY ===

    let tripsBody = containerBody.cloneNode();

    // == TRIPS TABLE ==
    let tripsTable = table.cloneNode();
    let tripsTableHead = thead.cloneNode();
    let tripsTableHeadRow = tr.cloneNode();

    // trip table columns
    let tripsHeaderCols = 
    [
        "#", 
        "Start Date", 
        "End Date", 
        "Driver",
        "Vehicle",
        "Status",
        "Info"
    ];

    // Populate table header with columns
    tripsHeaderCols.forEach(column => {
        let col = document.createElement("th");
        // col.setAttribute("scope", "col");
        col.textContent = column;

        tripsTableHeadRow.appendChild(col);
    });

    let tripsTableBody = tbody.cloneNode();

    tripsTableHead.appendChild(tripsTableHeadRow);
    tripsTable.appendChild(tripsTableHead);
    tripsTable.appendChild(tripsTableBody);

    tripsBody.appendChild(tripsTable);

    tripsContainer.appendChild(tripsBody);
    firstRow.appendChild(tripsContainer);

    fetchAllTrips();

    function fetchAllTrips(keyword = "")
    {
        console.log("fetch all trips");
        const data = {endpoint: "trip/get_all"};

        fetch("../php/api/api.php", {
            method: "POST",
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data =>{
            if(data.status == "success")
            {
                console.log(data);

                tripsTableBody.textContent = "";

                const trips = data.trips;
                trips.forEach(trip => {
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
                    btnDetails.textContent = "Details";
                    btnDetails.addEventListener("click", () => getTripDetails(trip.id));
                    tableRow.appendChild(btnDetails);

                    tripsTableBody.appendChild(tableRow);
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

    function searchTrips()
    {
        fetchAllTrips(tripsSearchInput.value);
    }
}


// == ZONES CONTAINER ===

function loadZones()
{
    // Init zones
    const zonesContainer = container.cloneNode();
    zonesContainer.classList.add("col-4");

    // == ZONES CONTAINER HEAD ===

    let zonesHead = containerHead.cloneNode();

    // Container title
    let zonesTitle = containerHeadTitle.cloneNode();
    zonesTitle.textContent = "Zones";
    zonesHead.appendChild(zonesTitle);

    // Search Input
    let zonesSearchInput = searchInput.cloneNode();
    zonesSearchInput.setAttribute("placeholder", "Search:");
    zonesSearchInput.setAttribute("id", "searchZones");
    zonesSearchInput.addEventListener("input",searchZones);

    zonesHead.appendChild(zonesSearchInput);

    // Refresh button
    let zonesRefreshButton = refreshButton;
    zonesRefreshButton.setAttribute("id", "fetchZones");
    zonesHead.appendChild(zonesRefreshButton);

    zonesContainer.appendChild(zonesHead);

    // == ZONES CONTAINER BODY ===

    let zonesBody = containerBody.cloneNode();

    // == ZONES TABLE ==
    let zonesTable = table.cloneNode();
    let zonesTableHead = thead.cloneNode();
    let zonesTableHeadRow = tr.cloneNode();

    // zones table columns
    let zonesHeaderCols = 
    [
        "#", 
        "Name", 
        "Zip Code", 
        "Info"
    ];

    // Populate table header with columns
    zonesHeaderCols.forEach(column => {
        let col = document.createElement("th");
        // col.setAttribute("scope", "col");
        col.textContent = column;

        zonesTableHeadRow.appendChild(col);
    });

    let zonesTableBody = tbody.cloneNode();

    zonesTableHead.appendChild(zonesTableHeadRow);
    zonesTable.appendChild(zonesTableHead);
    zonesTable.appendChild(zonesTableBody);

    zonesBody.appendChild(zonesTable);

    zonesContainer.appendChild(zonesBody);
    firstRow.appendChild(zonesContainer);

    fetchAllZones();

    function fetchAllZones(keyword = null)
    {
        console.log("fetch zones " + keyword);
        const data = {endpoint: "zone/get_all"};
        fetch("../php/api/api.php", {
            method: "POST",
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data =>{
            if(data.status == "success")
            {
                zonesTableBody.textContent = "";

                const zones = data.zones;
                zones.forEach(zone => {
                    if(keyword == "" 
                    || keyword == null 
                    || zone.name.toLowerCase().includes(keyword.toLowerCase()) 
                    || zone.zipCode.toLowerCase().includes(keyword.toLowerCase())
                    )
                    {
                        let tableRow = document.createElement('tr');
                    
                        let idCell = document.createElement('td');
                        let nameCell = document.createElement('td');
                        let zipCell  = document.createElement('td');

                        let btnDetails = document.createElement("button");
                        btnDetails.classList.add("btn");
                        btnDetails.classList.add("btn-primary");
                        btnDetails.textContent = "Details";
                        btnDetails.addEventListener("click", () => getZoneDetails(zone.id));

                        idCell.textContent = zone.id;
                        nameCell.textContent = zone.name;
                        zipCell.textContent = zone.zipCode;

                        tableRow.appendChild(idCell);
                        tableRow.appendChild(nameCell);
                        tableRow.appendChild(zipCell);
                        tableRow.appendChild(btnDetails);

                        zonesTableBody.appendChild(tableRow);
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

    function getZoneDetails(id)
    {
        console.log("Get zone " + id + " details");
    }
    
    function searchZones()
    {
        fetchAllZones(zonesSearchInput.value);
    }
}

// OLD

function fetchFleet()
{
    console.log("fetch fleet");
    const data = {endpoint: "fleet/get_all"};

    fetch("../php/api/api.php", {
        method: "POST",
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data =>{
        if(data.status == "success")
        {
            console.log(data);
            alert(data.message.msg);

            let fleetTableBody = document.querySelector("#fleetTable tbody");

            const fleet = data.fleet;
            fleet.forEach(vehicle => {
                let tableRow = document.createElement('tr');
            
                let idCell = document.createElement('td');
                let plateCell = document.createElement('td');
                let typeCell  = document.createElement('td');

                let btnDetails = document.createElement("button");
                btnDetails.classList.add("btn");
                btnDetails.classList.add("btn-primary");
                btnDetails.textContent = "Details";
                btnDetails.addEventListener("click", () => getVehicleDetails(vehicle.id));

                idCell.textContent =    vehicle.id;
                plateCell.textContent = vehicle.licensePlate;
                typeCell.textContent =  vehicle.type.nameFr;

                tableRow.appendChild(idCell);
                tableRow.appendChild(plateCell);
                tableRow.appendChild(typeCell);
                tableRow.appendChild(btnDetails);

                fleetTableBody.appendChild(tableRow);
            });
        }
        else
        {
            alert(data.message);
            console.log("Error:", data);
        }
    })
}

function getVehicleDetails(id)
{
    console.log("Get vehicle " + id + " details");
}

function fetchDrivers()
{
    console.log("fetch drivers");
    const data = {endpoint: "driver/get_all"};

    fetch("../php/api/api.php", {
        method: "POST",
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data =>{
        if(data.status == "success")
        {
            console.log(data);
            alert(data.message.msg);

            let driversTableBody = document.querySelector("#driversTable tbody");

            const drivers = data.drivers;
            drivers.forEach(driver => {
                let tableRow = document.createElement('tr');
            
                let idCell = document.createElement('td');
                let firstNameCell = document.createElement('td');
                let lastNameCell  = document.createElement('td');

                let btnDetails = document.createElement("button");
                btnDetails.classList.add("btn");
                btnDetails.classList.add("btn-primary");
                btnDetails.textContent = "Details";
                btnDetails.addEventListener("click", () => getDriverDetails(driver.id));

                idCell.textContent        = driver.id;
                firstNameCell.textContent = driver.firstName;
                lastNameCell.textContent  = driver.lastName;

                tableRow.appendChild(idCell);
                tableRow.appendChild(firstNameCell);
                tableRow.appendChild(lastNameCell);
                tableRow.appendChild(btnDetails);

                driversTableBody.appendChild(tableRow);
            });
        }
        else
        {
            alert(data.message);
            console.log("Error:", data);
        }
    })
}

function getDriverDetails(id)
{
    console.log("Get driver " + id + " details");
}


