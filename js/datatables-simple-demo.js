window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki
    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        const toto = new DataTable(datatablesSimple,{
            "lengthMenu": [
                [-1, 10, 25, 50 ],
                ['Total', 10, 25, 50],
            ],
            "language": {
                "emptyTable":     "Pas de données dans la table",
                "info":           "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                "infoEmpty":      "Affichage de 0 à 0 sur 0 entrées",
                "infoFiltered":   "(Filtrés des _MAX_ entrées totales)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Afficher _MENU_ entrées",

                "search":         "Recherche:",
                "paginate": {
                    "first":      "Premier",
                    "last":       "Dernier",
                    "next":       "Suivant",
                    "previous":   "Précédent"
            }}
        });
    }
});
