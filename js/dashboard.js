const cookie = document.cookie;

const zoneTable = document.querySelector("#zoneTable");
const zoneTableBody = document.querySelector("#zoneTableBody");

const fleetTable = document.querySelector("#fleetTable");
const fleetTableBody = document.querySelector("#fleetTableBody");

const btnFetchZones = document.querySelector("#fetchZones");
const btnFetchFleet = document.querySelector("#fetchFleet");

btnFetchZones.addEventListener("click",fetchAllZones);
btnFetchFleet.addEventListener("click",fetchFleet);

function fetchAllZones()
{
    console.log("fetch zones");
    const data = {endpoint: "zone/get_all"};

    fetch("../php/api/api.php", {
        method: "POST",
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data =>{
        if(data.status == "success")
        {
            console.log(data);

            const zones = data.zones;

            zones.forEach(zone => {
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
            });
        }
        else
        {
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
            console.log("Error:", data);
        }
    })
}

function getVehicleDetails(id)
{
    console.log("Get vehicle " + id + " details");
}