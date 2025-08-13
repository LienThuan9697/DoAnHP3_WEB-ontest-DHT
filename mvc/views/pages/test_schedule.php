<div class="content" data-id="<?php echo $data["user_id"] ?>">
    <!-- Thanh tìm kiếm và lọc trạng thái -->
<div class="row mb-4 justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">
        <form action="#" id="search-form" onsubmit="return false;">
            <div class="input-group">
                <button class="btn btn-alt-primary dropdown-toggle btn-filtered-by-state"
                        id="dropdown-filter-state" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    Tất cả
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdown-filter-state">
                    <li><a class="dropdown-item filtered-by-state" href="javascript:void(0)" data-value="0">Chưa làm</a></li>
                    <li><a class="dropdown-item filtered-by-state" href="javascript:void(0)" data-value="1">Quá hạn</a></li>
                    <li><a class="dropdown-item filtered-by-state" href="javascript:void(0)" data-value="2">Chưa mở</a></li>
                    <li><a class="dropdown-item filtered-by-state" href="javascript:void(0)" data-value="3">Đã hoàn thành</a></li>
                    <li><a class="dropdown-item filtered-by-state" href="javascript:void(0)" data-value="4">Tất cả</a></li>
                </ul>
                <input type="text" class="form-control" placeholder="Tìm kiếm đề thi, tên môn học..."
                       id="search-input" name="search-input">
            </div>
        </form>
    </div>
</div>


    <!-- Bảng danh sách đề thi -->
  <!-- <div class="table-responsive"> -->
  <table class="table table-bordered table-hover align-middle text-center">
    <thead class="bg-body-light text-uppercase fw-semibold">
      <tr class="text-primary">
        <th><i class="me-1 text-muted"></i> Tên đề</th>
        <th><i class="me-1 text-muted"></i> Môn học</th>
        <th><i class="me-1 text-muted"></i> Bắt đầu</th>
        <th><i class="me-1 text-muted"></i> Kết thúc</th>
        <th><i class="me-1 text-muted"></i> Nhóm</th>
        <th><i class="me-1 text-muted"></i> Điểm</th>
        <th><i class="me-1 text-muted"></i> Trạng thái</th>
        <th><i class="me-1 text-muted"></i> Hành động</th>
      </tr>
    </thead>
    <tbody class="list-test">
      <!-- Dữ liệu sẽ được thêm vào bằng JS hoặc PHP -->
    </tbody>
  </table>
</div>


    <!-- Phân trang -->
    <div class="row my-3">
        <?php if (isset($data["Plugin"]["pagination"])) {
            require "./mvc/views/inc/pagination.php";
        } ?>
    </div>
</div>
