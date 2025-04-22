window.addEventListener('load', function () {
    const API_KEY = 'pk.eca1d85507789494dd9f3a2e2f8227f9'; 

    function quitarTildes(texto) {
        return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            fetch(`https://us1.locationiq.com/v1/reverse?key=${API_KEY}&lat=${lat}&lon=${lon}&format=json`)
                .then(response => response.json())
                .then(data => {
                    let direccion = data.display_name;
                    direccion = quitarTildes(direccion); 
                    console.log("Dirección limpia: ", direccion);
                    document.getElementById('ubicacion').value = direccion;
                })
                .catch(error => {
                    console.error('Error al obtener la dirección:', error);
                });
        }, function (error) {
            console.warn('No se pudo obtener la ubicación:', error);
        });
    } else {
        console.warn('Geolocalización no es compatible con este navegador.');
    }
});