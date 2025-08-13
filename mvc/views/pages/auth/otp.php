<!-- Page Content -->
<div class="bg-image position-relative"
  style="background-image: url('https://y7b6t9n6.delivery.rocketcdn.me/wp-content/uploads/2024/01/SVG-3.svg'); background-size: cover; background-position: center; min-height: 100vh;">
  <div style="position: absolute; inset: 0; background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); z-index: 0;"></div>

  <div class="row g-0 justify-content-center align-items-center min-vh-100 position-relative" style="z-index: 1;">
    <div class="hero-static col-sm-10 col-md-8 col-xl-4 d-flex align-items-center p-2 px-sm-0">
      <div class="block bg-white shadow-lg rounded-4 w-100 mb-0 overflow-hidden p-4 p-lg-5">

        <div class="text-center mb-4">
          <p class="fw-bold fs-2 text-decoration-none">
            <span class="bg-dark text-white px-3 py-1 rounded-3 shadow-sm">DHT</span>
            <span class="text-primary fs-2">OnTest</span>
          </p>
          <p class="text-uppercase fw-bold fs-sm text-dark mt-3 mb-1">Xác minh OTP</p>
          <p id="otpMessage" class="fs-sm text-muted"></p>

        </div>

        <form id="formOpt">
          <div class="mb-4">
            <div class="input-group input-group-lg">
              <input type="text" id="txtOpt" name="txtOtp" class="form-control form-control-lg rounded-3 border border-secondary"
                placeholder="Nhập mã OTP" required>
            </div>
          </div>

          <div class="text-center mb-3">
            <button type="submit" id="btnOtpVerify" class="btn btn-lg w-100 text-white fw-bold rounded-3 shadow"
              style="background: linear-gradient(to right, #00c6ff, #0072ff);">
              <i class="fa fa-key me-2"></i> Xác minh
            </button>
          </div>

          <div class="text-center small text-muted mb-2">
<span id="countdownText">
  Bạn có thể yêu cầu mã mới sau 
  <span style="color:red;" id="countdownWrapper">
    <span id="countdown">60</span>s
  </span>
</span>
          </div>
          <div class="text-center">
            <button type="button" id="btnResendOtp" class="btn btn-sm btn-outline-primary rounded-pill" disabled>
              Gửi lại mã
            </button>
          </div>
        </form>

        <input type="hidden" id="storedEmail" name="storedEmail">

      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const countdownEl = document.getElementById("countdown");
    const resendBtn = document.getElementById("btnResendOtp");
    let timeLeft = 60;
    const timer = setInterval(() => {
      timeLeft--;
      countdownEl.textContent = timeLeft;
      if (timeLeft <= 0) {
        clearInterval(timer);
        resendBtn.disabled = false;
        document.getElementById("countdownText").textContent = "Bạn có thể yêu cầu mã mới";
      }
    }, 1000);
  });
document.addEventListener("DOMContentLoaded", () => {
  const email = localStorage.getItem("otpEmail");
  if (email) {
    document.getElementById("otpMessage").innerHTML =
      `<span class="text-muted">Mã OTP đã được gửi đến email:</span> <strong>${email}</strong>`;
  }
});




</script>
