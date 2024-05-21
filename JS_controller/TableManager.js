console.log("TableManager.js loaded");

//+++++++++++++++++ Définir les options par défaut +++++++++++++++++
const TASK_TABLE_OPTION = [
    "id",
    "name",
    "type",
    "state",
    "validated",
    "assigned_to",
    "color",
    "date_debut",
    "date_fin",
    "estimated_time",
    "created_at"
]
const DEFAULT_TASK_TABLE_OPTION = [
    "id",
    "name",
    "type",
    "state",
    "validated",
    "assigned_to",
    "color",
    "date_debut",
    "date_fin",
    "estimated_time",
    "created_at"
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
    async populateTable() {
        // Ajouter la ligne de titre
        try {
            this.addRow(TITLE_ROW,[]);
        } catch (error) {
            console.log("Error: TableManager.populateTable() -> " + error);
        }

        // Ajouter les lignes de données
        try {
            let tasks = await this.getTasks();
            tasks.forEach(task => this.addDataRow(task));
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
            cell.innerHTML = data[this.options[i]];
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
            const response = await fetch('getTasks.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
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