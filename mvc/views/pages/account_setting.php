<div class="content content-full content-boxed account_ID" data-id="<?php echo $_SESSION['user_id'] ?>">
  <!-- Hero -->
  <div class="rounded border overflow-hidden push">
    <div class="bg-image pt-9" style="background-image: url('https://aztest.vn/uploads/news/2022/thi-online_1.jpg')"></div>
    <div class="px-4 py-3 bg-body-extra-light d-flex flex-column flex-md-row align-items-center load-profile">
      <a class="d-block img-link mt-n5 avatar-Profile" href="javascript:void(0)">
        <img class="img-avatar img-avatar128 img-avatar-thumb" src="./public/media/avatars/<?php echo $data['User']['avatar'] == '' ? 'avatar2.jpg' : $data['User']['avatar'] ?>" alt="">
      </a>
      <div class="ms-3 flex-grow-1 text-center text-md-start my-3 my-md-0 load-nameProfile">
        <h1 class="fs-4 fw-bold mb-1"><?php echo $_SESSION['user_name'] ?></h1>
        <h2 class="fs-sm fw-medium text-muted mb-0">Chỉnh sửa hồ sơ</h2>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Edit Account -->
  <div class="block block-bordered block-rounded">
    <!-- Tabs -->
    <ul class="nav nav-tabs nav-tabs-alt" role="tablist">
      <li class="nav-item">
        <button class="nav-link space-x-1 active" id="account-profile-tab" data-bs-toggle="tab" data-bs-target="#account-profile" role="tab" aria-controls="account-profile" aria-selected="true">
          <i class="fa fa-user-circle d-sm-none"></i>
          <span class="d-none d-sm-inline">Hồ sơ</span>
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link space-x-1" id="account-password-tab" data-bs-toggle="tab" data-bs-target="#account-password" role="tab" aria-controls="account-password" aria-selected="false">
          <i class="fa fa-asterisk d-sm-none"></i>
          <span class="d-none d-sm-inline">Mật khẩu</span>
        </button>
      </li>
    </ul>
    <!-- END Tabs -->

    <!-- Tab Contents -->
    <div class="block-content tab-content">
      <!-- Profile Tab -->
      <div class="tab-pane active" id="account-profile" role="tabpanel" aria-labelledby="account-profile-tab" tabindex="0">
        <div class="row push p-sm-2 p-lg-4">
          <div class="offset-xl-1 col-xl-4 order-xl-1">
            <p class="bg-body-light p-4 rounded-3 text-muted fs-sm">
              Thông tin tài khoản của bạn. Tên người dùng sẽ hiển thị công khai.
            </p>
          </div>
          <div class="col-xl-6 order-xl-0">
            <form class="form-update-profile" method="POST" enctype="multipart/form-data">
              <div class="mb-4">
                <label class="form-label" for="dm-profile-msv">
                  <?php echo $data["User"]["manhomquyen"] == 1 ? "Mã giảng viên" : "Mã sinh viên"; ?>
                </label>
                <input type="text" class="form-control" id="dm-profile-msv" name="dm-profile-msv" value="<?php echo $data["User"]["id"] ?>" disabled>
              </div>

              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-name">Họ và tên</label>
                <input type="text" class="form-control" id="dm-profile-edit-name" name="dm-profile-edit-name" value="<?php echo $data["User"]["hoten"] ?>">
              </div>

              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-email">Địa chỉ email</label>
                <input type="email" class="form-control" id="dm-profile-edit-email" name="dm-profile-edit-email" value="<?php echo $data["User"]["email"] ?>">
              </div>

              <div class="mb-4">
                <label class="form-label">Giới tính</label>
                <div class="d-flex gap-3 mt-1">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="gender-male" name="user_gender" value="1" <?php echo $data["User"]["gioitinh"] == 1 ? "checked" : "" ?>>
                    <label class="form-check-label" for="gender-male">Nam</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="gender-female" name="user_gender" value="0" <?php echo $data["User"]["gioitinh"] == 0 ? "checked" : "" ?>>
                    <label class="form-check-label" for="gender-female">Nữ</label>
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label" for="user_ngaysinh">Ngày sinh</label>
                <input type="text" class="js-flatpickr form-control" id="user_ngaysinh" name="user_ngaysinh" placeholder="Ngày sinh" value="<?php echo $data["User"]["ngaysinh"] ?>">
              </div>

              <div class="mb-4">
                <label class="form-label">Ảnh đại diện hiện tại</label>
                <div class="push up-avatar">
                  <img class="img-avatar" src="./public/media/avatars/<?php echo $data['User']['avatar'] == '' ? 'avatar2.jpg' : $data['User']['avatar'] ?>" alt="">
                </div>
                <label class="form-label mt-2" for="dm-profile-edit-avatar">Chọn ảnh đại diện mới</label>
                <input class="form-control" type="file" id="dm-profile-edit-avatar" name="file-img" accept="image/*">
              </div>

              <button type="submit" class="btn btn-alt-primary" id="update-profile">
                <i class="fa fa-check-circle opacity-50 me-1"></i> Cập nhật hồ sơ
              </button>
            </form>
          </div>
        </div>
      </div>
      <!-- END Profile Tab -->

      <!-- Password Tab -->
      <div class="tab-pane" id="account-password" role="tabpanel" aria-labelledby="account-password-tab" tabindex="0">
        <div class="row push p-sm-2 p-lg-4">
          <div class="offset-xl-1 col-xl-4 order-xl-1">
            <p class="bg-body-light p-4 rounded-3 text-muted fs-sm">
              Đổi mật khẩu là cách đơn giản để bảo vệ tài khoản của bạn.
            </p>
          </div>
          <div class="col-xl-6 order-xl-0">
            <form class="form-change-password" onsubmit="return false;">
              <div class="mb-4">
                <label class="form-label" for="current-password">Mật khẩu hiện tại</label>
                <input type="password" class="form-control" id="current-password" name="current-password">
              </div>
              <div class="mb-4">
                <label class="form-label" for="new-password">Mật khẩu mới</label>
                <input type="password" class="form-control" id="new-password" name="new-password">
              </div>
              <div class="mb-4">
                <label class="form-label" for="password-new-confirm">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control" id="password-new-confirm" name="password-new-confirm">
              </div>
              <button type="submit" class="btn btn-alt-primary" id="update-password">
                <i class="fa fa-check-circle opacity-50 me-1"></i> Cập nhật mật khẩu
              </button>
            </form>
          </div>
        </div>
      </div>
      <!-- END Password Tab -->
    </div>
    <!-- END Tab Contents -->
  </div>
  <!-- END Edit Account -->
</div>