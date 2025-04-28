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

    // Manejo de login por AJAX
    const loginForm = document.getElementById('loginForm');
    const loginError = document.getElementById('loginError');
    const loginModal = document.getElementById('loginModal');

    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);

            fetch('/Usuario/login', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cierra el modal y recarga la página
                        const modal = bootstrap.Modal.getInstance(loginModal) || new bootstrap.Modal(loginModal);
                        modal.hide();
                        window.location.reload();
                    } else {
                        // Muestra el error
                        loginError.textContent = data.error || 'Error desconocido';
                        loginError.classList.remove('d-none');
                        // Limpiar el input de password
                        const passwordInput = document.getElementById('password');
                        if (passwordInput) passwordInput.value = '';
                        passwordInput.focus();
                    }
                })
                .catch(() => {
                    loginError.textContent = 'Error de conexión';
                    loginError.classList.remove('d-none');
                });
        });
    }

    // Manejo de logout por AJAX
    const logoutForm = document.getElementById('logoutForm');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function (e) {
            e.preventDefault();
            fetch('/Usuario/logout', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload(); // Recarga la misma vista
                    }
                });
        });
    }
});