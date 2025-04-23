document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('filtro-categoria').addEventListener('change', function () {
        this.form.submit();
    });

    document.getElementById('btn-limpiar-busqueda').addEventListener('click', function () {
        document.getElementById('filtro-productos').value = '';
        document.getElementById('form-filtros').submit();
    });
});
