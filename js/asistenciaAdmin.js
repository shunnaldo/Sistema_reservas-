document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const rut = document.getElementById('rut').value.trim();
        const nombre = document.getElementById('nombre').value.trim();
        const apellido = document.getElementById('apellido').value.trim();
        const correo = document.getElementById('correo').value.trim();
        const taller = document.getElementById('taller').value;

        // Validaciones básicas
        const nombreRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const rutRegex = /^[0-9kK]+$/;

        if (!rutRegex.test(rut)) {
            alert("El RUT no debe contener puntos, guion ni otros caracteres. Solo números y la letra K.");
            return;
        }

        if (!nombreRegex.test(nombre)) {
            alert("El nombre no debe contener números ni caracteres especiales.");
            return;
        }

        if (!nombreRegex.test(apellido)) {
            alert("El apellido no debe contener números ni caracteres especiales.");
            return;
        }

        if (!correoRegex.test(correo)) {
            alert("Ingrese un correo válido.");
            return;
        }

        if (!rut || !nombre || !apellido || !correo || !taller) {
            alert("Todos los campos son obligatorios.");
            return;
        }

        const formData = new FormData(form);

        fetch('/sistema_reservas/Sistema_reservas-/php/Admin/asistencia.php', {       
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            if (data.includes("exitosamente")) {
                form.reset();
            }
        })
        .catch(error => {
            alert("Error de conexión. Inténtalo de nuevo.");
            console.error('Error:', error);
        });
    });
});