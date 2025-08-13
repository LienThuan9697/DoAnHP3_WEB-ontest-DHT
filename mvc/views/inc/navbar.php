<style>
#sidebar {
  background-color: #e0f2f1; 
  color: #004d40; 
}

.bg-header-dark {
  background-color: #b2dfdb !important; 
}

.content-header.bg-white-10 {
  background-color: rgba(0, 128, 128, 0.05) !important; 
}

.nav-main-link {
  color: #004d40; 
  padding: 10px 16px;
  font-size: 15px;
  font-weight: 500;
  border-radius: 8px;
  display: flex;
  align-items: center;
  transition: all 0.2s ease;
}

.nav-main-link:hover {
  background-color: rgba(0, 128, 128, 0.08); 
  color: #004d40;
}

.nav-main-link i {
  margin-right: 10px;
  font-size: 16px;
  color: #00796b; 
}

.nav-main-link.active {
  background-color: #80cbc4 !important; 
  color: #004d40 !important;
  font-weight: 600;
}

.nav-main-link.active i {
  color: #004d40 !important;
}
</style>

<?php require_once "config.php" ?>
<!-- Sidebar -->
<nav id="sidebar" aria-label="Main Navigation">
    <div class="bg-header-dark">
        <div class="content-header bg-white-10 d-flex align-items-center justify-content-between p-3">
            <a class="fw-bold fs-4 d-flex align-items-center justify-content-center gap-2 text-decoration-none text-teal" href="#">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTuFLY_S9yy9U-riLBII3M5-3oQZ9Pr1Zjkw&s" alt="Logo" width="40" height="40" class="rounded-circle border shadow-sm">
                <span>DHT OnTest </span>
            </a>
        

            <!-- Options -->
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-alt-light" data-toggle="class-toggle"
                        data-target="#dark-mode-toggler" data-class="far fa"
                        onclick="Dashmix.layout('dark_mode_toggle');">
                    <i class="far fa-moon" id="dark-mode-toggler"></i>
                </button>

                <button type="button" class="btn btn-sm btn-alt-light d-lg-none" data-toggle="layout"
                        data-action="sidebar_close">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
            <!-- END Options -->
        </div>
    </div>
    <!-- END Side Header -->

    <!-- Sidebar Scroll -->
    <div class="js-sidebar-scroll">
        <div class="content-side pt-3 pb-4">
            <ul class="nav-main">
                <!-- Tổng quan -->
                <li class="nav-main-item">
                    <a class="nav-main-link <?= getActiveNav() === 'dashboard' ? 'active' : '' ?>" href="./dashboard">
                        <i class="nav-main-link-icon fa fa-rocket"></i>
                        <span class="nav-main-link-name">Tổng quan</span>
                    </a>
                </li>

                <!-- Dynamic Menu -->
                <?php build_navbar(); ?>
            </ul>
        </div>
    </div>
    <!-- END Sidebar Scroll -->
</nav>
<!-- END Sidebar -->
