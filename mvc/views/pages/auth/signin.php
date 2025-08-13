<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=block" rel="stylesheet">
<style>
body {
  font-family: 'Inter', sans-serif;
  background: #f8f9fc;
}

.text-gradient {
  background: linear-gradient(45deg, #1E90FF, #00C9A7);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.btn-google {
  background: white;
  border: 1px solid #ddd;
  font-weight: 600;
  color: #444;
  transition: all 0.3s;
}

.btn-google:hover {
  background: #f5f5f5;
}

.shadow-elevated {
  box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.btn-primary:hover {
  background: #0d6efd;
}

#loginError {
  display: none;
  margin-top: 10px;
}

.alert {
  padding: 12px 16px;
  border-radius: 6px;
  font-weight: 500;
  margin-top: 15px;
  display: none;
}

.alert-success {
  background-color: #d1e7dd;
  color: #0f5132;
  border-left: 5px solid #198754;
}

.alert-danger {
  background-color: #f8d7da;
  color: #842029;
  border-left: 5px solid #dc3545;
}
</style>

<div class="container-fluid min-vh-100">
  <div class="row g-0 min-vh-100">
    <div class="col-md-6 d-flex align-items-center justify-content-center bg-white">
      <div class="p-5 w-100 shadow-elevated rounded-5" style="max-width: 450px;">
        <div class="text-center mb-4">
          <a class="fs-1 fw-bold text-decoration-none">
            <span class="text-gradient">DHT ONTEST</span>
          </a>
          <p class="text-muted fw-semibold text-uppercase mt-2">Đăng nhập</p>
          <div id="loginError" class="alert"></div>
        </div>
        <form id="loginForm" method="POST">
          <div class="mb-3">
            <label class="form-label fw-semibold">Mã SV - GV</label>
            <input type="text" class="form-control form-control-lg rounded-4 shadow-sm" name="masinhvien" placeholder="Nhập mã sinh viên hoặc giảng viên" required>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Mật khẩu</label>
            <input type="password" class="form-control form-control-lg rounded-4 shadow-sm" name="password" placeholder="Nhập mật khẩu" required>
          </div>
          <div class="d-grid mb-3">
            <button id="loginBtn" type="submit" class="btn btn-lg btn-primary rounded-4 shadow-sm">
 <span class="btn-text">Đăng nhập</span>
</button>
          </div>
          <div class="d-flex justify-content-between mt-4 small">
            <a class="text-muted" href="/Quanlythitracnghiem/auth/recover"><i class="fa fa-question-circle me-1"></i> Quên mật khẩu?</a>
            <a class="text-muted" href="/Quanlythitracnghiem/auth/signup"><i class="fa fa-user-plus me-1"></i> Tạo tài khoản</a>
          </div>
          <div class="text-center mt-4">
            <a href="/Quanlythitracnghiem" class="btn btn-outline-secondary btn-sm rounded-3 px-4">
              <i class="fa fa-arrow-left me-1"></i> Quay lại trang chủ
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- BÊN PHẢI: ẢNH NỀN -->
    <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center text-dark text-center p-5" style="
    background: url('https://y7b6t9n6.delivery.rocketcdn.me/wp-content/uploads/2024/01/SVG-3.svg') center/cover no-repeat;
    position: relative;">
      <div style="
          position: absolute;
          inset: 0;
          background: rgba(255, 255, 255, 0.6);
          backdrop-filter: blur(8px);
          border-radius: 0;">
      </div>
      <div class="position-relative p-5" style="max-width: 500px;">
        <h1 class="fw-bold mb-3" style="font-size: 2.5rem;">
          <span class="text-gradient">DHT ONTEST</span>
        </h1>
        <p class="fs-5 text-dark fw-normal mb-4">Hệ thống tạo và quản lý bài thi cá nhân hóa</p>
        <p class="small text-muted">© <span id="year-copy"></span> DHT ONTEST</p>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.getElementById('year-copy').textContent = new Date().getFullYear();

$(document).ready(function () {
  $('#loginForm').on('submit', function (e) {
  e.preventDefault();

  const $loginBtn = $('#loginBtn');
  const $btnText = $loginBtn.find('.btn-text');
  const $loginError = $('#loginError');

  $loginBtn.prop('disabled', true);
  $btnText.html(`<i class="fa fa-spinner fa-spin me-2"></i> Đang xử lý...`);

  $.ajax({
    url: '/Quanlythitracnghiem/auth/checkLogin',
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function (response) {
      if (response.success) {
        $loginError
          .removeClass('alert-danger')
          .addClass('alert alert-success')
          .text(response.message)
          .fadeIn();

      
          window.location.href = '/Quanlythitracnghiem/dashboard';
   
      } else {
        $loginError
          .removeClass('alert-success')
          .addClass('alert alert-danger')
          .text(response.message)
          .fadeIn();
      }
    },
    error: function () {
      $loginError
        .removeClass('alert-success')
        .addClass('alert alert-danger')
        .text('Đã xảy ra lỗi khi đăng nhập!')
        .fadeIn();
    },
    complete: function () {
      // Reset nút
      $loginBtn.prop('disabled', false);
      $btnText.html(`<i class="fa fa-sign-in-alt me-2"></i> Đăng nhập`);
    }
  });
});

});
</script>