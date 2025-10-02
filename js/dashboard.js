const cookie = document.cookie;

const fleetTable        = document.querySelector("#fleetTable");
const driversTable      = document.querySelector("#driversTable");

const btnFetchZones     = document.querySelector("#fetchZones");
const btnFetchFleet     = document.querySelector("#fetchFleet");
const btnFetchDrivers   = document.querySelector("#fetchDrivers");
const btnFetchTrips     = document.querySelector("#fetchTrips");

btnFetchZones.addEventListener("click",fetchAllZones);
btnFetchFleet.addEventListener("click",fetchFleet);
btnFetchDrivers.addEventListener("click",fetchDrivers);
btnFetchTrips.addEventListener("click",fetchAllTrips);

const inputSearchZones  = document.querySelector("#searchZones");
inputSearchZones.addEventListener("input",searchZones);

const inputSearchTrips  = document.querySelector("#searchTrips");
inputSearchTrips.addEventListener("input",searchTrips);

function searchZones()
{
    fetchAllZones(inputSearchZones.value);
}

function searchTrips()
{
    fetchAllTrips(inputSearchTrips.value);
}

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
            let zoneTableBody = document.querySelector("#zoneTable tbody");
            zoneTableBody.textContent = "";

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

                    zoneTableBody.appendChild(tableRow);
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

            let tripTableBody = document.querySelector("#tripTable tbody");
            tripTableBody.textContent = "";

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

                tripTableBody.appendChild(tableRow);
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

fetchAllZones();
fetchAllTrips();