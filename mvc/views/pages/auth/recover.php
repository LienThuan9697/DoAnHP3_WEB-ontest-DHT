<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OnTest DHT - Quên mật khẩu</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
    }
    .bg-image {
      background-image: url('https://y7b6t9n6.delivery.rocketcdn.me/wp-content/uploads/2024/01/SVG-3.svg');
      background-size: cover;
      background-position: center;
      min-height: 100vh;
      position: relative;
    }
    .bg-overlay {
      position: absolute;
      inset: 0;
      background: rgba(255, 255, 255, 0.75);
      backdrop-filter: blur(8px);
      z-index: 0;
    }
    .form-card {
      background: white;
      border-radius: 24px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      text-align: center;
      z-index: 1;
      padding: 40px 30px;
    }

    .form-card p {
      font-size: 15px;
      color: #555;
      margin-bottom: 20px;
    }

    .input-group {
      position: relative;
      margin-bottom: 20px;
    }
    .input-group .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #555;
      font-size: 16px;
      pointer-events: none;
    }
    .input-group input[type="email"] {
      width: 100%;
      padding: 14px 14px 14px 42px;
      border-radius: 12px;
      border: 1px solid #ddd;
      font-size: 15px;
      height: 50px;
      transition: border 0.3s;
    }
    .input-group input:focus {
      border-color: #00C9A7;
      outline: none;
      box-shadow: 0 0 0 2px rgba(0,201,167,0.2);
    }

    .btn-primary {
      width: 100%;
      padding: 10px;
      background: linear-gradient(45deg, #00C9A7, #1E90FF);
      border: none;
      border-radius: 12px;
      font-weight: 600;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      height: 45px;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      opacity: 0.95;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    .btn-secondary {
      width: 100%;
      padding: 10px;
      background: #f1f1f1;
      color: #333;
      text-decoration: none;
      border-radius: 12px;
      font-weight: 500;
      font-size: 14px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      height: 45px;
      display: inline-block;
      line-height: 20px;
    }
    .btn-secondary:hover {
      background: #e2e2e2;
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .alert {
      padding: 12px 16px;
      border-radius: 6px;
      font-weight: 500;
      margin-bottom: 15px;
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
</head>
<body>
    <div class="bg-image">
        <div class="bg-overlay"></div>
        <div class="row g-0 justify-content-center align-items-center min-vh-100 position-relative" style="z-index: 1;">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-4 d-flex align-items-center p-4">
                <div class="form-card w-full">
                    <div class="mb-4 text-center">
                        <a class="fw-bold text-3xl text-decoration-none" href="/Quanlythitracnghiem">
                            <span class="text-dark">DHT </span><span class="text-primary">OnTest</span>
                        </a>
                        <p class="text-uppercase fw-semibold text-muted mt-1 mb-2 text-sm">Quên mật khẩu</p>
                        <div id="recoverError" class="alert"></div>
                        <div id="loadingMessage" class="alert alert-info" style="display: none;">Đang xử lý yêu cầu...</div>
                    </div>
                    <form id="recoverForm" class="js-validation-reminder" method="POST" action="/Quanlythitracnghiem/auth/sendOptAuth">
                         <div class="input-group">
              <span class="input-icon">
                <i class="fa fa-envelope"></i>
              </span>
              <input type="email" id="reminder-credential" name="reminder-credential"
                     placeholder="Nhập email của tài khoản" required>
            </div>

                        <div class="d-grid gap-3">
                            <button id="btnRecover" type="submit" class="btn-primary">
                                <i class="fa fa-reply me-1"></i> Khôi phục mật khẩu
                            </button>
                            <a href="/Quanlythitracnghiem/auth/signin" class="btn-secondary">
                                <i class="fa fa-sign-in-alt me-1"></i> Quay về đăng nhập
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
</body>


</html>