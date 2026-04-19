/**
 * Sistema de Gestión Empresarial - Scripts Principales
 */

$(document).ready(function () {
    // --- ANIMACIONES ORIGINALES DEL LOGIN ---
    if ($(".login-form").length > 0 || $("#tarjeta").length > 0) {
        function randomValues() {
            anime({
                targets: '.square, .circle, .triangle',
                translateX: function () {
                    return anime.random(-window.innerWidth / 2, window.innerWidth / 2);
                },
                translateY: function () {
                    return anime.random(-window.innerHeight / 2, window.innerHeight / 2);
                },
                rotate: function () {
                    return anime.random(0, 360);
                },
                scale: function () {
                    return anime.random(.2, 1.5);
                },
                duration: 1000,
                easing: 'easeInOutQuad',
                complete: randomValues,
            });
        }
        randomValues();
    }

    // --- INICIALIZACIÓN DE DATATABLES ---
    if ($("#tabla_id").length > 0) {
        $("#tabla_id").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "Todos"]
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
            },
            initComplete: function () {
                $(this.api().table().node()).css('visibility', 'visible');
            }
        });
    }

    // --- TOOLTIPS DE BOOTSTRAP ---
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

/**
 * Función para confirmar el borrado de un registro
 */
function borrar(formId) {
    Swal.fire({
        title: '¿Está seguro de borrar el registro?',
        text: '¡Una vez borrado no se puede recuperar!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, elimínelo',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(formId);
            if (form) {
                form.submit();
            }
        }
    });
}
