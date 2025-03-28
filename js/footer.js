(() => {
    const ruta = window.location.pathname.includes('/php/') ? 'footer.html' : 'php/footer.html';

    fetch(ruta)
        .then(response => response.text())
        .then(data => {
            document.getElementById('footer-container').innerHTML = data;
        })
        .catch(error => console.error('Error al cargar el footer:', error));
})();
