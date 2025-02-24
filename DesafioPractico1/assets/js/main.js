document.addEventListener('DOMContentLoaded', function () {
    function limpiarModal(modalElement) {
        // Limpiar el formulario
        modalElement.querySelector('form').reset();

        // Remover mensajes de error
        const alertas = modalElement.querySelectorAll('.alert');
        alertas.forEach(alerta => alerta.remove());

        // Limpiar el backdrop
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }

        // Limpiar clases y estilos del body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        // Forzar la recarga de la página para limpiar completamente el estado
        window.location.reload();
    }

    // Manejar modales
    const modales = document.querySelectorAll('.modal');
    modales.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            limpiarModal(this);
        });
    });

    // Manejar clics fuera del modal
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('modal')) {
            const modal = bootstrap.Modal.getInstance(event.target);
            if (modal) {
                limpiarModal(event.target);
            }
        }
    });
});