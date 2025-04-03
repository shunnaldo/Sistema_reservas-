const ruta = window.location.pathname.includes('/php/') ? 'navbar.html' : 'php/navbar.html';

fetch(ruta)
    .then(response => response.text())
    .then(data => {
        document.getElementById('navbar-container').innerHTML = data;
    })
    .catch(error => console.error('Error al cargar el navbar:', error));