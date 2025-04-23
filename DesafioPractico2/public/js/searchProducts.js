document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('filtro-productos');
    const form = input.closest('form');
    let timeout = null;

    input.addEventListener('input', function () {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            form.submit();
        }, 400); // Espera 400ms después de dejar de escribir
    });

    document.getElementById('filtro-categoria').addEventListener('change', function () {
        this.form.submit();
    });
});
