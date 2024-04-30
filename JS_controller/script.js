document.querySelectorAll('.day').forEach(day => {
    day.addEventListener('click', function() {
        const taskId = prompt('Entrez l’ID de la tâche à ajouter :');
        fetch(`/add-task.php?day=${this.id}&taskId=${taskId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Tâche ajoutée avec succès!');
            } else {
                alert('Erreur lors de l’ajout de la tâche.');
            }
        })
        .catch(error => console.error('Erreur:', error));
    });
});
