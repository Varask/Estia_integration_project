console.log("TableManager.js loaded");

//+++++++++++++++++ Définir les options par défaut +++++++++++++++++
const DEFAULT_TASK_TABLE_OPTION = [
    "id", "name", "type", "status", "priority", "created_at", "due_date", "assignee"
]

const TITLE_ROW = false;
const DATA_ROW = true;

//+++++++++++++++++ Classe TableManager +++++++++++++++++
class TableManager {
    // Constructeur de la classe
    constructor(idTable, options = DEFAULT_TASK_TABLE_OPTION) {
        this.idTable = idTable;
        this.table = document.getElementById(this.idTable);
        this.options = options;
        this.rowNumber = 0;
        this.colNumber = options.length;
        this.populateTable();
    }

    //+++++++++++++++++ Section: Initialisation et peuplement du tableau +++++++++++++++++
    populateTable() {
        // Ajouter la ligne de titre
        try {
            this.addTitleRow(this.options);
        } catch (error) {
            console.log("Error: TableManager.populateTable() -> " + error);
        }

        // Ajouter une ligne de données initiale
        try {
            this.updateRow();
        } catch (error) {
            console.log("Error: TableManager.populateTable() -> " + error);
        }
    }

    //+++++++++++++++++ Section: Gestion des lignes et des colonnes du tableau +++++++++++++++++
    addRow(type = DATA_ROW, data = []) {
        if (type === TITLE_ROW) {
            this.addTitleRow(this.options);
        } else {
            this.addDataRow(data);
        }
    }

    addDataRow(data) {
        let row = this.table.insertRow(this.rowNumber);
        for (let i = 0; i < this.colNumber; i++) {
            let cell = row.insertCell(i);
            cell.innerHTML = data[i];
        }
        this.rowNumber++;
    }

    addTitleRow(options) {
        let row = this.table.insertRow(this.rowNumber);
        for (let i = 0; i < this.colNumber; i++) {
            let cell = document.createElement("th");
            cell.innerHTML = options[i];
            row.appendChild(cell);
        }
        this.rowNumber++;
    }

    updateRow() {
        let data = {
            "id": "1",
            "name": "Task 1",
            "type": "Bug",
            "status": "Open",
            "priority": "High",
            "created_at": "2021-01-01",
            "due_date": "2021-01-31",
            "assignee": "John Doe",
        }

        let rowData = [];
        this.options.forEach(option => {
            rowData.push(data[option]);
        });

        this.addDataRow(rowData);
    }

    deleteRow(rowIndex) {
        this.table.deleteRow(rowIndex);
    }

    deleteCol(colIndex) {
        for (let row of this.table.rows) {
            row.deleteCell(colIndex);
        }
        this.colNumber--;
    }

    //+++++++++++++++++ Section: Récupération des tâches via une requête POST +++++++++++++++++
    async getTasks() {
        try {
            const response = await fetch('YOUR_API_ENDPOINT_URL', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ /* Add any required request body here */ })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const tasks = await response.json();
            return tasks;
        } catch (error) {
            console.error('Error fetching tasks:', error);
            return [];
        }
    }
}

//+++++++++++++++++ Section: Gestion du menu contextuel +++++++++++++++++
const contextMenu = document.getElementById('contextMenu');
let currentRowIndex = null;
let currentColIndex = null;

document.getElementById('T1').addEventListener('contextmenu', function(event) {
    event.preventDefault();
    contextMenu.style.display = 'block';
    contextMenu.style.left = `${event.pageX}px`;
    contextMenu.style.top = `${event.pageY}px`;

    const cell = event.target;
    if (cell.tagName === 'TD' || cell.tagName === 'TH') {
        currentRowIndex = cell.parentElement.rowIndex;
        currentColIndex = cell.cellIndex;
    }
});

document.addEventListener('click', function() {
    contextMenu.style.display = 'none';
});

document.getElementById('deleteRow').addEventListener('click', function() {
    if (currentRowIndex !== null) {
        table.deleteRow(currentRowIndex);
        currentRowIndex = null;
    }
});

document.getElementById('deleteCol').addEventListener('click', function() {
    if (currentColIndex !== null) {
        table.deleteCol(currentColIndex);
        currentColIndex = null;
    }
});

contextMenu.addEventListener('click', function(event) {
    event.stopPropagation();
});
