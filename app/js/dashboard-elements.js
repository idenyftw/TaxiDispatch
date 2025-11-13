const gridMain = document.querySelector("#gridMain");

const refreshSvg =  
`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
</svg>`;

const row = document.createElement("div");
row.classList.add("row"); 

const container = document.createElement("div");
container.classList.add("container", "frost", "p-4");

const containerHead = document.createElement("div");
containerHead.classList.add("d-flex", "p-1");

const containerHeadTitle = document.createElement("h3");
containerHeadTitle.classList.add("me-auto");

const containerBody = document.createElement("div");

const searchInput = document.createElement("input");
searchInput.setAttribute("type", "text");

const refreshButton = document.createElement("button");
refreshButton.classList.add("btn", "btn-primary");
refreshButton.innerHTML = refreshSvg;

const table = document.createElement("table");
table.classList.add("table");
table.classList.add("table-striped");
table.classList.add("table-hover");

const thead = document.createElement("thead"); 
const tbody = document.createElement("tbody"); 
const tr = document.createElement("tr"); 
const th = document.createElement("th");

export {
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
};
