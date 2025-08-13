<div class="content" data-id="<?php echo $_SESSION['user_id']; ?>">
    <!-- Form tìm kiếm -->
 <form id="search-form" onsubmit="return false;">
  <div class="row g-3 align-items-end mb-4 bg-white p-3 rounded shadow-sm">
    <!-- Học kỳ -->
    <div class="col-lg-3 col-md-6">
      <label for="filter-kihoc" class="form-label fw-bold">Học kỳ</label>
      <select id="filter-kihoc" class="form-select">
        <option value="">Tất cả học kỳ</option>
        <option value="1">Học kỳ 1</option>
        <option value="2">Học kỳ 2</option>
        <option value="3">Học kỳ 3</option>
      </select>
    </div>

    <!-- Nhóm học phần -->
    <div class="col-lg-3 col-md-6">
      <label for="filter-nhomhocphan" class="form-label fw-bold">Nhóm học phần</label>
      <select id="filter-nhomhocphan" class="form-select">
        <option value="">Tất cả nhóm học phần</option>
      </select>
    </div>

    <!-- Tìm kiếm -->
    <div class="col-lg-3 col-md-6">
      <label for="search-input" class="form-label fw-bold">Tìm kiếm</label>
      <input type="text" class="form-control" id="search-input" placeholder="Nhập từ khóa...">
    </div>

    <!-- Nút thêm thông báo -->
    <div class="col-lg-3 col-md-6 text-end">
      <label class="form-label d-block invisible">Thêm</label>
      <a href="./teacher_announcement/add" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
        <i class="fa fa-fw fa-plus me-1"></i> Thêm Thông Báo
      </a>
    </div>
  </div>
</form>



    <!-- Danh sách thông báo -->
    <div class="list-announces" id="list-announces">
        <!-- List sẽ được render bằng JS -->
    </div>

    <!-- Phân trang -->
    <div class="row my-3">
        <?php
        if (isset($data["Plugin"]["pagination"])) {

            require "./mvc/views/inc/pagination.php";
        }
?>
    </div>
</div>
