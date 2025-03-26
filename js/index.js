document.getElementById('reservation-form').addEventListener('submit', function (e) {
    e.preventDefault();

    // Obtener los valores del formulario
    const rut = document.getElementById('rut').value;
    const nombre = document.getElementById('nombre').value.trim();
    const apellido = document.getElementById('apellido').value.trim();
    const correo = document.getElementById('correo').value;
    const fecha = document.getElementById('fecha').value;
    const horaInicio = document.getElementById('horaInicio').value;
    const horaFin = document.getElementById('horaFin').value;

    // Expresión regular para validar que solo contenga letras y espacios
    const nombreApellidoRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;

    if (!nombreApellidoRegex.test(nombre) || !nombreApellidoRegex.test(apellido)) {
        alert("Formato de nombre no válido. Solo se permiten letras y espacios.");
        return;
    }

    // Obtener la fecha y hora actuales
    const now = new Date();
    const fechaSeleccionada = new Date(fecha + "T" + horaInicio);

    // Verificar si la fecha y hora seleccionadas son válidas y no están en el pasado
    if (fechaSeleccionada < now) {
        alert("La fecha y hora deben ser válidas o a futuro.");
        return;
    }

    if (horaInicio && horaFin) {
        const horaInicioDate = new Date("1970-01-01T" + horaInicio + "Z");
        const horaFinDate = new Date("1970-01-01T" + horaFin + "Z");

        const diferenciaHoras = (horaFinDate - horaInicioDate) / 3600000;

        if (diferenciaHoras > 2) {
            alert("El máximo de horas para agendar es 2.");
            return;
        }
    } else {
        alert("Por favor, completa las horas de inicio y fin.");
        return;
    }

    const data = {
        rut: rut,
        nombre: nombre,
        apellido: apellido,
        correo: correo,
        fecha: fecha,
        horaInicio: horaInicio,
        horaFin: horaFin
    };

    fetch('../php/guardarReserva.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const successMessageElement = document.getElementById('success-message');
        const errorMessageElement = document.getElementById('error-message');

        if (data.success) {
            successMessageElement.innerText = data.message;
            successMessageElement.style.display = 'block';
            errorMessageElement.style.display = 'none';

            setTimeout(function() {
                successMessageElement.style.display = 'none';
            }, 3000);

            document.getElementById('reservation-form').reset();
        } else {
            errorMessageElement.innerText = data.message;
            errorMessageElement.style.display = 'block';
            successMessageElement.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});