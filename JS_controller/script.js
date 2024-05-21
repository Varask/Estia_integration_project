// Récupérer les données JSON des tâches depuis l'attribut data-tasks
var tasks = JSON.parse(tasksJsonString);

// Dictionnaire des couleurs en fonction de l'ID
var colorMap = {
    0: '#fde2e4',
    1: '#accbe1',
    2: '#9fd8df',
    3: '#97c1a9',
    4: '#e4cbf8'
};

// Fonction pour créer et ajouter une ligne de tâche au tableau
function addTaskRow(task) {
    var table = document.getElementById("T1").getElementsByTagName('tbody')[0];
    var newRow = table.insertRow(table.rows.length);

    // Appliquer la couleur de fond en fonction de l'ID de la couleur
    var color = colorMap[task.color];
    newRow.style.backgroundColor = color;

    var cellNames = ['name', 'type', 'state', 'date_debut', 'date_fin', 'estimated_time', 'assigned_to'];
    cellNames.forEach(function(cellName) {
        var cell = newRow.insertCell();
        if (cellName === 'assigned_to') {
            // Combiner firstname et employee_name dans une seule cellule
            cell.appendChild(document.createTextNode(task.firstname + ' ' + task.employee_name));
        } else {
            cell.appendChild(document.createTextNode(task[cellName]));
        }
    });
}

// Ajouter chaque tâche au tableau
tasks.forEach(function(task) {
    addTaskRow(task);
});

// Récupérer les données JSON des assignés depuis l'attribut data-tasks
var assignees = JSON.parse(assigneesJsonString);

// Remplir le menu déroulant avec les noms des salariés
function addAssignee(assignee) {
        var select = document.getElementById("Assignee");
        var option = document.createElement("option");
        option.value = assignee.id;  // Utiliser l'ID du salarié comme valeur
        option.text = assignee.firstname + " " + assignee.name; // Utiliser le nom du salarié comme texte
        select.add(option);
}

// Ajouter chaque tâche au menu déroulant
assignees.forEach(function(assignee) {
    addAssignee(assignee);
});

// Remplir le menu déroulant avec les noms des tâches
function addTaskOption(task) {
    addToSelect(task, "tache-selection");
    addToSelect(task, "tache-selection-2");
}

function addToSelect(task, selectId) {
    var select = document.getElementById(selectId);
    var option = document.createElement("option");
    option.value = task.id;  // Utiliser l'ID de la tâche comme valeur
    option.text = task.name; // Utiliser le nom de la tâche comme texte
    select.add(option);
}

// Ajouter chaque tâche au menu déroulant
tasks.forEach(function(task) {
    addTaskOption(task);
});

var taskId;

// Mettre à jour l'état de la tâche sélectionnée
document.getElementById("tache-selection").addEventListener("change", function() {
    var selectedTaskId = this.value;
    var selectedTask = tasks.find(task => task.id == selectedTaskId);
    taskId = selectedTaskId;
    if (selectedTask) {
        document.getElementById("etat").value = selectedTask.state;

        // Changer le texte du bouton en fonction de l'état de la tâche
        var validateBtn = document.getElementById("state-button");
        var validateBtn2 = document.getElementById("state-button2");
        if (selectedTask.state === "On hold") {
            validateBtn.style.display = "inline";
            validateBtn.textContent = "Rouvrir";
            validateBtn2.style.display = "none";
        } else if (selectedTask.state === "Current") {
            validateBtn.style.display = "inline";
            validateBtn.textContent = "Mettre en attente";
            validateBtn2.style.display = "inline";
            validateBtn2.textContent = "Valider";
        } else if (selectedTask.state === "Validated") {
            validateBtn.style.display = "inline";
            validateBtn.textContent = "Rouvrir";
            validateBtn2.style.display = "inline";
            validateBtn2.textContent = "Fermer";
        } else if (selectedTask.state === "Closed") {
            validateBtn.style.display = "none";
            validateBtn2.style.display = "none";
        }
    }
});

// Initialiser l'état avec la première tâche
if (tasks.length > 0) {
    var initialTask = tasks[0];
    taskId = tasks[0].id;
    document.getElementById("etat").value = initialTask.state;

    var validateBtn = document.getElementById("state-button");
    var validateBtn2 = document.getElementById("state-button2");
    if (initialTask.state === "On hold") {
        validateBtn.style.display = "inline";
        validateBtn.textContent = "Rouvrir";
        validateBtn2.style.display = "none";
    } else if (initialTask.state === "Current") {
        validateBtn.style.display = "inline";
        validateBtn.textContent = "Mettre en attente";
        validateBtn2.style.display = "inline";
        validateBtn2.textContent = "Valider";
    } else if (initialTask.state === "Validated") {
        validateBtn.style.display = "inline";
        validateBtn.textContent = "Rouvrir";
        validateBtn2.style.display = "inline";
        validateBtn2.textContent = "Fermer";
    } else if (initialTask.state === "Closed") {
        validateBtn.style.display = "none";
        validateBtn2.style.display = "none";
    }
}

// Fonction pour envoyer les données au fichier PHP
function sendDataToPHP(buttonContent, taskId) {
    fetch('../PHP_model/homepageModel.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `buttonContent=${encodeURIComponent(buttonContent)}&taskId=${encodeURIComponent(taskId)}&submitTaskStateForm=true`
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        window.location.reload();
    })
    .catch(error => console.error('Error:', error));
}

// Ajoutez des écouteurs d'événements aux boutons
document.getElementById("state-button").addEventListener("click", function() {
    sendDataToPHP(this.textContent, taskId);
});

document.getElementById("state-button2").addEventListener("click", function() {
    sendDataToPHP(this.textContent, taskId);
});

var colorVerMap = {
    0: '#ff001e',
    1: '#11ff00'
};

// Mettre à jour l'état de la tâche sélectionnée
document.getElementById("tache-selection-2").addEventListener("change", function() {
    var selectedTaskId = this.value;
    var selectedTask = tasks.find(task => task.id == selectedTaskId);
    if (selectedTask) {
        document.getElementById("task-name").value = selectedTask.name;
        document.getElementById("task-id_type").value = selectedTask.type;
        document.getElementById("task-id_state").value = selectedTask.state;
        is_validated = document.getElementById("task-is_validated");
        var colorval = colorVerMap[selectedTask.is_validated];
        is_validated.style.backgroundColor = colorval;
        // Appliquer la couleur de fond en fonction de l'ID de la couleur
        taskColor = document.getElementById("task-color");
        var color = colorMap[selectedTask.color];
        taskColor.style.backgroundColor = color;
        document.getElementById("task-date_debut").value = selectedTask.date_debut;
        document.getElementById("task-date_fin").value = selectedTask.date_fin;
        document.getElementById("task-estimated_time").value = selectedTask.estimated_time + "h";
        document.getElementById("task-created_at").value = selectedTask.created_at;
    }
});

// Initialiser l'état avec la première tâche
if (tasks.length > 0) {
    var initialTask = tasks[0];
    document.getElementById("task-name").value = initialTask.name;
    document.getElementById("task-id_type").value = initialTask.type;
    document.getElementById("task-id_state").value = initialTask.state;
    is_validated = document.getElementById("task-is_validated");
    var colorval = colorVerMap[initialTask.is_validated];
    is_validated.style.backgroundColor = colorval;
    // Appliquer la couleur de fond en fonction de l'ID de la couleur
    taskColor = document.getElementById("task-color");
    var color = colorMap[initialTask.color];
    taskColor.style.backgroundColor = color;
    document.getElementById("task-date_debut").value = initialTask.date_debut;
    document.getElementById("task-date_fin").value = initialTask.date_fin;
    document.getElementById("task-estimated_time").value = initialTask.estimated_time + "h";
    document.getElementById("task-created_at").value = initialTask.created_at;
}

document.addEventListener("DOMContentLoaded", function() {
    adjustContentBasedOnRole(role);
    updateBudgetForecast();

    var tasks = JSON.parse(tasksJsonString);

    var colorMap = {
        0: '#fde2e4',
        1: '#accbe1',
        2: '#9fd8df',
        3: '#97c1a9',
        4: '#e4cbf8'
    };

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        editable: false,
        events: tasks.map(task => ({
            title: task.name,
            start: task.date_debut,
            end: task.date_fin,
            backgroundColor: colorMap[task.color], // Appliquez la couleur ici
            description: `Assigned to: ${task.firstname} ${task.employee_name}, Estimated time: ${task.estimated_time} hours`
        })),
        eventRender: function(event, element) {
            element.qtip({
                content: event.description,
                position: {
                    my: 'bottom center',
                    at: 'top center'
                },
                style: {
                    classes: 'qtip-blue qtip-shadow'
                }
            });
        }
    });
});

function updateBudgetForecast() {
    var tasks = JSON.parse(tasksJsonString);
    var assignees = JSON.parse(assigneesJsonString);
    var roles = JSON.parse(rolesJsonString);
    var assigned = JSON.parse(assignedJsonString);
    var today = new Date();
    
    var actualCosts = calculateCosts(tasks, assigned, assignees, roles, task => new Date(task.date_debut) <= today && new Date(task.date_fin) > today);
    var futureCosts = calculateCosts(tasks, assigned, assignees, roles, task => new Date(task.date_debut) > today);
    
    // Mettre à jour les valeurs actuelles
    document.getElementById("actualTask").textContent = actualCosts.taskNumber;
    document.getElementById("totalCost").textContent = actualCosts.totalCost + "€";
    document.getElementById("totalHours").textContent = actualCosts.totalHours + "h";
    document.getElementById("hourlyCost").textContent = actualCosts.hourlyCost + "€";
    document.getElementById("actualUsers").textContent = actualCosts.users.join(", ");
    
    // Mettre à jour les valeurs futures
    document.getElementById("plannedTask").textContent = futureCosts.taskNumber;
    document.getElementById("plannedTaskCost").textContent = futureCosts.totalCost + "€";
    document.getElementById("plannedHours").textContent = futureCosts.totalHours + "h";
    document.getElementById("plannedHourlyCost").textContent = futureCosts.hourlyCost + "€";
    document.getElementById("totalProjectCost").textContent = futureCosts.totalCost + actualCosts.totalCost + "€";
    document.getElementById("futureUsers").textContent = futureCosts.users.join(", ");
}

function calculateCosts(tasks, assigned, assignees, roles, filterFunction) {
    var filteredTasks = tasks.filter(filterFunction);
    var totalCost = 0;
    var totalHours = 0;
    var hourlyCost = 0;
    var users = new Set();

    var roleMap = roles.reduce((map, role) => {
        map[role.id] = parseFloat(role.price);
        return map;
    }, {});

    filteredTasks.forEach(task => {
        var taskAssignees = assigned.filter(assignment => assignment.id_task === task.id);
        var taskCost = 0;

        taskAssignees.forEach(assignment => {
            var assignee = assignees.find(assignee => assignee.id === assignment.id_employee);
            if (assignee) {
                var assigneeRolePrice = roleMap[assignee.id_role];
                taskCost += assigneeRolePrice * task.estimated_time;
                hourlyCost += assigneeRolePrice;
                users.add(`${assignee.firstname} ${assignee.name}`);
            }
        });

        totalCost += taskCost;
        totalHours += parseFloat(task.estimated_time);
    });

    return {
        taskNumber: filteredTasks.length,
        totalCost: totalCost,
        totalHours: totalHours,
        hourlyCost: hourlyCost,
        users: Array.from(users)
    };
}
