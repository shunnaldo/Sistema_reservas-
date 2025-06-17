<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">

    <link rel="stylesheet" href="../../../css/navbarAdmin.css">
</head>

<body>


    <!--=============== HEADER ===============-->
    <header class="header" id="header">
        <div class="header__container">
            <button class="header__toggle" id="header-toggle">
                <i class="ri-menu-line"></i>
            </button>
        </div>
    </header>

    <!--=============== SIDEBAR ===============-->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar__container">
            <div class="sidebar__user">
                <div class="sidebar__img">
                    <img src="../../../img/Logo CE negro-02.png" alt="User Image">
                </div>
                <div class="sidebar__info">
                </div>

            </div>

            <div class="sidebar__content">
                <div>
                    <h3 class="sidebar__title text-success">Formulario</h3>
                    <div class="sidebar__list">
                        <a href="formularioFip.php" class="sidebar__link">
                            <i class="ri-file-text-fill"></i>
                            <span class="text-success">Formulario FIP</span>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="sidebar__title text-success">Estado</h3>
                    <div class="sidebar__list">
                        <a href="estadoSolicitud.php" class="sidebar__link">
                            <i class="ri-hourglass-fill"></i>
                            <span class="text-success">Estado solicitud</span>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="sidebar__title text-success">Enviados</h3>
                    <div class="sidebar__list">
                        <a href="ver_fichas.php" class="sidebar__link">
                            <i class="ri-mail-send-line"></i>
                            <span class="text-success">Enviados</span>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="sidebar__title text-success">Historial</h3>
                    <div class="sidebar__actions">
                        <a href="logout.php" class="sidebar__link">
                            <i class="ri-book-open-line"></i>
                            <span class="text-success">Historial solicitudes</span>
                        </a>
                    </div>
                </div>


                <div>
                    <h3 class="sidebar__title text-success">Logout</h3>
                    <div class="sidebar__actions">
                        <a href="logout.php" class="sidebar__link">
                            <i class="ri-logout-box-r-fill"></i>
                            <span class="text-success">Log Out</span>
                        </a>
                    </div>
                </div>


            </div>

        </div>
    </nav>



    <script src="../../../js/sidebar.js"></script>

</body>

</html