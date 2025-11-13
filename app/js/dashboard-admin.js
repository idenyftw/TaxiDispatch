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

export function loadAdminPage()
{
    console.log("load admin page");

    loadTrips();
    loadZones();
    loadVehicles();

    //Append rows
    gridMain.appendChild(firstRow);
    gridMain.appendChild(secondRow);
}

const firstRow  = row.cloneNode();
const secondRow = row.cloneNode();

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

// == VEHICLES CONTAINER == 

function loadVehicles()
{
    // Init container
    const vehiclesContainer = container.cloneNode();
    vehiclesContainer.classList.add("col-4");

    // == VEHICLES CONTAINER HEAD ===

    let vehiclesHead = containerHead.cloneNode();
    
    // Container title
    let vehiclesTitle = containerHeadTitle.cloneNode();
    vehiclesTitle.textContent = "Vehicles";
    vehiclesHead.appendChild(vehiclesTitle);

    // Search Input
    let vehiclesSearchInput = searchInput.cloneNode();
    vehiclesSearchInput.setAttribute("placeholder", "Search:");
    vehiclesSearchInput.setAttribute("id", "searchVehicles");
    vehiclesSearchInput.addEventListener("input",searchVehicles);
    vehiclesHead.appendChild(vehiclesSearchInput);

    vehiclesContainer.appendChild(vehiclesHead);

    // == VEHICLES CONTAINER BODY ===

    let vehiclesBody = containerBody.cloneNode();

    // == VEHICLES TABLE ==
    let vehiclesTable = table.cloneNode();
    let vehiclesTableHead = thead.cloneNode();
    let vehiclesTableHeadRow = tr.cloneNode();

    // zones table columns
    let vehicleHeaderCols = 
    [
        "#", 
        "Plate", 
        "Type Fr", 
        "Type En", 
        "Info"
    ];

    // Populate table header with columns
    vehicleHeaderCols.forEach(column => {
        let col = document.createElement("th");
        col.textContent = column;

        vehiclesTableHeadRow.appendChild(col);
    });

    let vehiclesTableBody = tbody.cloneNode();

    vehiclesTableHead.appendChild(vehiclesTableHeadRow);
    vehiclesTable.appendChild(vehiclesTableHead);
    vehiclesTable.appendChild(vehiclesTableBody);

    vehiclesBody.appendChild(vehiclesTable);
    vehiclesContainer.appendChild(vehiclesBody);

    secondRow.appendChild(vehiclesContainer);

    fetchAllVehicles();

    function searchVehicles()
    {
        fetchAllVehicles(vehiclesSearchInput.value);
    }

    function fetchAllVehicles(keyword = null)
    {
        console.log("fetch vehicles " + keyword);
        const data = {endpoint: "vehicle/get_all"};

        fetch("../php/api/api.php", {
            method: "POST",
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data =>{
            if(data.status == "success")
            {
                vehiclesTableBody.textContent = "";

                const vehicles = data.vehicles;
                vehicles.forEach(vehicle => {
                      if(keyword == "" 
                    || keyword == null 
                    || vehicle.licensePlate.toLowerCase().includes(keyword.toLowerCase()) 
                    || vehicle.type.nameEn.toLowerCase().includes(keyword.toLowerCase())
                    || vehicle.type.nameFr.toLowerCase().includes(keyword.toLowerCase())
                    )
                    {
                        let tableRow = document.createElement('tr');
                    
                        let idCell    = document.createElement('td');
                        let plateCell = document.createElement('td');
                        let typeFrCell  = document.createElement('td');
                        let typeEnCell  = document.createElement('td');

                        let btnDetails = document.createElement("button");
                        btnDetails.classList.add("btn");
                        btnDetails.classList.add("btn-primary");
                        btnDetails.textContent = "Details";
                        btnDetails.addEventListener("click", () => getVehicleDetails(vehicle.id));

                        idCell.textContent      =  vehicle.id;
                        plateCell.textContent   =  vehicle.licensePlate;
                        typeFrCell.textContent  =  vehicle.type.nameFr;
                        typeEnCell.textContent  =  vehicle.type.nameEn;

                        tableRow.appendChild(idCell);
                        tableRow.appendChild(plateCell);
                        tableRow.appendChild(typeFrCell);
                        tableRow.appendChild(typeEnCell);
                        tableRow.appendChild(btnDetails);

                        vehiclesTableBody.appendChild(tableRow);
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

    function getVehicleDetails(id)
    {
        console.log("Get vehicle " + id + " details");
    }
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


