const ruta = window.location.pathname.includes('/php/Admin/') 
    ? 'sidebarAdmin.html' 
    : 'pages/Admin/sidebarAdmin.html';


fetch(ruta)
    .then(response => response.text())
    .then(data => {
        const navbarContainer = document.getElementById('navbarAdmin-container');
        if (navbarContainer) {
            navbarContainer.innerHTML = data;

            // Esperamos a que el contenido se haya inyectado completamente antes de ejecutar las funciones
            setTimeout(() => {
                // Verificamos si los elementos necesarios existen antes de inicializar el sidebar
                const toggle = document.getElementById('header-toggle');
                const sidebar = document.getElementById('sidebar');
                const header = document.getElementById('header');
                
                // Verificamos que todos los elementos están presentes en el DOM
                if (toggle && sidebar && header) {
                    showSidebar('header-toggle', 'sidebar', 'header');
                } else {
                    console.error('Los elementos del sidebar no están presentes en el DOM');
                }

                // Inicializar el tema
                const themeButton = document.getElementById('theme-button');
                if (themeButton) {
                    const darkTheme = 'dark-theme';
                    const iconTheme = 'ri-sun-fill';

                    // Obtenemos el tema previamente seleccionado
                    const selectedTheme = localStorage.getItem('selected-theme');
                    const selectedIcon = localStorage.getItem('selected-icon');

                    // Función para obtener el tema actual
                    const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
                    const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-moon-clear-fill' : 'ri-sun-fill';

                    // Aplicamos el tema previamente seleccionado
                    if (selectedTheme) {
                        document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
                        themeButton.classList[selectedIcon === 'ri-moon-clear-fill' ? 'add' : 'remove'](iconTheme);
                    }

                    // Cambiar el tema al hacer clic en el botón
                    themeButton.addEventListener('click', () => {
                        document.body.classList.toggle(darkTheme);
                        themeButton.classList.toggle(iconTheme);
                        localStorage.setItem('selected-theme', getCurrentTheme());
                        localStorage.setItem('selected-icon', getCurrentIcon());
                    });
                } else {
                    console.error('Elemento theme-button no encontrado en el DOM');
                }
            }, 100);  // Usamos un timeout para dar más tiempo al DOM para cargar
        } else {
            console.error('Elemento navbarAdmin-container no encontrado en el DOM.');
        }
    })
    .catch(error => console.error('Error al cargar el navbar:', error));