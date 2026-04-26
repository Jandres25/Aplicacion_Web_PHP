/**
 * Gestión de Usuarios
 */

/**
 * Función para confirmar y eliminar un usuario vía AJAX
 * @param {number} id - ID del usuario a eliminar
 */
function eliminarUsuario(id) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
        title: '¿Está seguro de borrar el usuario?',
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
            // Mostrar cargando
            Swal.fire({
                title: 'Procesando...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: 'usuarios-eliminar',
                type: 'POST',
                data: {
                    txtID: id,
                    csrf_token: csrfToken
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message);
                        // Recargar la página después de un breve delay para que se vea el toast
                        setTimeout(() => {
                            location.reload();
                        }, 5500);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function () {
                    showToast('error', 'No se pudo procesar la solicitud');
                }
            });
        }
    });
}
