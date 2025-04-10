window.addEventListener('load', function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            // API de Nominatim (OpenStreetMap) 
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
                .then(response => response.json())
                .then(data => {
                    const direccion = data.display_name; 
                    console.log("Direcci贸n obtenida: ", direccion); 
                    document.getElementById('ubicacion').value = direccion;  
                })
                .catch(error => {
                    console.error('Error al obtener la direcci贸n:', error);
                });
        }, function (error) {
            console.warn('No se pudo obtener la ubicaci贸n:', error);
        });
    } else {
        console.warn('Geolocalizaci贸n no es compatible con este navegador.');
    }
});