<!-- Notifications Dropdown -->
 <?php if (isset($_SESSION['user_permission_group']) && $_SESSION['user_permission_group'] == 2): ?>

<style>
@keyframes shake-bell {
  0%, 100% { transform: rotate(0); }
  25% { transform: rotate(10deg); }
  50% { transform: rotate(-10deg); }
  75% { transform: rotate(5deg); }
}

.bell-shake {
  animation: shake-bell 1s ease-in-out infinite;
}
</style>


<div data-action="join" class="dropdown d-inline-block">
    <button type="button" class="btn btn-alt-secondary btn-show-notifications" data-id="<?php echo $_SESSION['user_id']?>" id="page-header-notifications-dropdown"
        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-fw fa-bell" id="notification-bell-icon"></i>
        <span class="badge bg-danger" id="notification-count">0</span>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" style="width: 25rem;"
        aria-labelledby="page-header-notifications-dropdown">
        <div class="bg-primary-dark rounded-top fw-semibold text-white text-center p-3">
            Thông báo
        </div>
        <ul class="nav-items my-2 list-notifications">
            <!-- data -->
        </ul>
    </div>
</div>
<!-- END Notifications Dropdown -->
<?php endif; ?>