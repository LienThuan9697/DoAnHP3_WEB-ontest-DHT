<style>
  #mamonhoc:disabled {
  background-color: #e9ecef; 
  cursor: not-allowed;      
}
</style>

<div class="content">
  <div class="block block-rounded shadow-sm">
    <div class="block-header block-header-default d-flex justify-content-between align-items-center">
      <h3 class="block-title mb-0">
        <i class="fa fa-book text-primary me-2"></i>Danh sách môn học
      </h3>
      <div class="block-options">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-subject"
          data-role="monhoc" data-action="create">
          <i class="fa fa-plus me-1"></i> Thêm môn học
        </button>
      </div>
    </div>

    <div class="block-content">
      <!-- Tìm kiếm -->
      <form action="#" id="search-form" onsubmit="return false;">
        <div class="mb-4">
          <div class="input-group">
            <input type="text" class="form-control form-control-alt" id="search-input" name="search-input"
              placeholder="Tìm kiếm môn học...">
            <button class="btn bg-body border-0 btn-search" type="submit">
              <i class="fa fa-search text-muted"></i>
            </button>
          </div>
        </div>
      </form>

      <!-- Bảng dữ liệu -->
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-vcenter align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>Mã môn</th>
              <th class="text-start">Tên môn</th>
              <th class="d-none d-sm-table-cell">Số tín chỉ</th>
              <th class="d-none d-sm-table-cell">Số tiết LT</th>
              <th class="d-none d-sm-table-cell">Số tiết TH</th>
              <th class="text-center col-header-action">Hành động</th>
            </tr>
          </thead>
          <tbody id="list-subject">
            <!-- Dữ liệu động -->
          </tbody>
        </table>
      </div>

      <?php if (isset($data["Plugin"]["pagination"])) {
          require "./mvc/views/inc/pagination.php";
      } ?>
    </div>
  </div>
</div>

<!-- Modal Thêm/Chỉnh sửa Môn học -->
<div class="modal fade" id="modal-add-subject" tabindex="-1" role="dialog" aria-labelledby="modal-add-subject"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="block block-rounded block-themed block-transparent mb-0">
        <div class="block-header bg-primary-dark text-white">
          <h3 class="block-title add-subject-element">Thêm môn học</h3>
          <h3 class="block-title update-subject-element">Chỉnh sửa môn học</h3>
          <div class="block-options">
            <button type="button" class="btn-block-option text-white" data-bs-dismiss="modal" aria-label="Close">
              <i class="fa fa-fw fa-times"></i>
            </button>
          </div>
        </div>
        <form class="block-content fs-sm form-add-subject">
          <div class="mb-3">
            <label class="form-label">Mã môn học</label>
            <input type="text" class="form-control form-control-alt" name="mamonhoc" id="mamonhoc"
              placeholder="Nhập mã môn học">
          </div>
          <div class="mb-3">
            <label class="form-label">Tên môn học</label>
            <input type="text" class="form-control form-control-alt" name="tenmonhoc" id="tenmonhoc"
              placeholder="Nhập tên môn học">
          </div>
        <div class="mb-3">
  <label class="form-label">Hình thức</label>
  <select class="form-control form-control-alt" name="loaimon" id="loaimon">
    <option value="lt">Lý thuyết</option>
    <option value="th">Thực hành</option>
    <option value="lt+th">Lý thuyết & Thực hành</option>
  </select>
</div>

          
          <div class="mb-3">
            <label class="form-label">Tổng số tín chỉ</label>
            <input type="number" class="form-control form-control-alt" name="sotinchi" id="sotinchi"
              placeholder="Nhập số tín chỉ">
          </div>
          <div class="row">
            <div class="col-6 mb-3">
              <label class="form-label">Số tiết lý thuyết</label>
              <input type="number" class="form-control form-control-alt" name="sotiet_lt" id="sotiet_lt"
                placeholder="Nhập số tiết lý thuyết">
            </div>
            <div class="col-6 mb-3">
              <label class="form-label">Số tiết thực hành</label>
              <input type="number" class="form-control form-control-alt" name="sotiet_th" id="sotiet_th"
                placeholder="Nhập số tiết thực hành">
            </div>
          </div>
        </form>
        <div class="block-content block-content-full text-end bg-body">
          <button type="button" class="btn btn-sm btn-alt-secondary me-2" data-bs-dismiss="modal" >Đóng</button>
          <button type="button" class="btn btn-sm btn-primary add-subject-element" id="add_subject">Lưu</button>
          <button type="button" class="btn btn-sm btn-primary update-subject-element" id="update_subject"
            data-id="">Cập nhật</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Chương -->
<div class="modal fade" id="modal-chapter" tabindex="-1" role="dialog" aria-labelledby="modal-chapter"
  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-body-light">
        <h5 class="modal-title"><i class="fa fa-list me-2 text-primary"></i>Danh sách chương</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body pb-1">
        <div class="table-responsive">
          <table class="table table-vcenter table-hover align-middle">
            <thead>
              <tr>
                <th class="text-center" style="width: 40px;">STT</th>
                <th>Tên chương</th>
                <th class="text-center col-header-action">Hành động</th>
              </tr>
            </thead>
            <tbody id="showChapper"></tbody>
          </table>
        </div>

        <div class="block block-rounded mt-3">
          <div class="block-content pb-3">
            <a class="fw-semibold" data-role="chuong" data-action="create" data-bs-toggle="collapse"
              href="#collapseChapter" role="button" aria-expanded="false" aria-controls="collapseChapter"
              id="btn-add-chapter">
              <i class="fa fa-plus me-1"></i> Thêm chương
            </a>

            <div class="collapse mt-2" id="collapseChapter">
              <form method="post" class="form-chapter">
                <div class="row g-2">
                  <div class="col-8">
                    <input type="text" class="form-control" name="name_chapter" id="name_chapter"
                      placeholder="Nhập tên chương">
                  </div>
                  <div class="col-4 d-flex flex-wrap gap-1">
                    <input type="hidden" name="mamon_chuong" id="mamon_chuong">
                    <input type="hidden" name="machuong" id="machuong">
                    <button id="add-chapter" type="submit" class="btn btn-alt-primary btn-sm">Tạo chương</button>
                    <button id="edit-chapter" type="submit" class="btn btn-primary btn-sm">Đổi tên</button>
                    <button type="button" class="btn btn-alt-secondary btn-sm close-chapter">Huỷ</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer bg-body-light">
        <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Thoát</button>
      </div>
    </div>
  </div>
</div>
