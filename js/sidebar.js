const showSidebar = (toggleId, sidebarId, headerId) => {
    const toggle = document.getElementById(toggleId),
          sidebar = document.getElementById(sidebarId),
          header = document.getElementById(headerId);

    if (!toggle || !sidebar || !header) return;

    // Manejar el clic en el botón toggle
    toggle.addEventListener('click', (event) => {
        event.stopPropagation();
        sidebar.classList.toggle('show-sidebar');
        header.classList.toggle('left-pd');
    });
};

// Inicializar el sidebar
showSidebar('header-toggle', 'sidebar', 'header');

/*=============== LINK ACTIVE ===============*/
const sidebarLinks = document.querySelectorAll('.sidebar__list a');

if (sidebarLinks.length > 0) {
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            sidebarLinks.forEach(l => l.classList.remove('active-link'));
            this.classList.add('active-link');
        });
    });
} else {
    console.error('No se encontraron enlaces en el sidebar');
}


/*=============== DARK LIGHT THEME ===============*/ 
const themeButton = document.getElementById('theme-button');
const darkTheme = 'dark-theme';
const iconTheme = 'ri-sun-fill';

// Previously selected topic (if user selected)
const selectedTheme = localStorage.getItem('selected-theme');
const selectedIcon = localStorage.getItem('selected-icon');

// We obtain the current theme that the interface has by validating the dark-theme class
const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light';
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'ri-moon-clear-fill' : 'ri-sun-fill';

// We validate if the user previously chose a topic
if (selectedTheme) {
  document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme);
  themeButton.classList[selectedIcon === 'ri-moon-clear-fill' ? 'add' : 'remove'](iconTheme);
}


