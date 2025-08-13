<style>
    #page-footer {
        display: none;
    }

    /* General Styling */
    .form-taodethi {
        display: flex;
        flex-wrap: wrap;
        height: 100%;
        background-color: #f8f9fa;
    }

    /* Sidebar Styling */
    .col-md-4.col-lg-5.col-xl-3.bg-white {
        padding: 15px;
        border-right: 1px solid #e9ecef;
        height: 100vh;
        overflow-y: auto;
    }

    #side-content {
        padding: 10px 0;
    }

    #side-content h3 {
        color: #343a40;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: 1px solid #e9ecef;
    }

    .form-check.form-switch {
        margin-bottom: 10px;
    }

    .form-check-input:checked {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .form-check-label {
        color: #495057;
        font-weight: 500;
    }

    .d-md-none .btn-alt-primary {
        background-color: #6c757d;
        color: #fff;
        border: none;
        border-radius: 5px;
    }

    .d-md-none .btn-alt-primary:hover {
        background-color: #5a6268;
    }

    /* Main Content Styling */
    .col-md-8.col-lg-7.col-xl-9 {
        padding: 20px;
        flex-grow: 1;
    }

    .block.block-rounded.form-tao-de {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .block-header {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .block-title {
        color: #343a40;
        font-weight: 700;
        font-size: 1.25rem;
    }

    .form-label {
        color: #495057;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 0.95rem;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
        outline: none;
    }

    .input-group-text {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 5px 0 0 5px;
    }

    .js-flatpickr.form-control {
        padding: 8px;
    }

    .block.block-rounded.border {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .js-select2.form-select {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 8px;
    }

    .row .col-4 {
        padding: 0 10px;
    }

    .row .col-4 input {
        text-align: center;
    }

    /* Button Styling */
    .btn-hero.btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 8px 15px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .btn-hero.btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-hero.btn-success {
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 8px 15px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .btn-hero.btn-success:hover {
        background-color: #218838;
    }

    .btn-hero i {
        margin-right: 5px;
    }

    @media (max-width: 768px) {
        .col-md-4.col-lg-5.col-xl-3.bg-white {
            height: auto;
            border-right: none;
            border-bottom: 1px solid #e9ecef;
        }

        .col-md-8.col-lg-7.col-xl-9 {
            padding: 15px;
        }

        .block.block-rounded.form-tao-de {
            padding: 15px;
        }

        .row .col-4 {
            padding: 0 5px;
            margin-bottom: 10px;
        }
    }
</style>

<form class="row g-0 flex-md-grow-1 form-taodethi">
    <div class="col-md-4 col-lg-5 col-xl-3 order-md-1 bg-white">
        <div class="content px-2">
            <div class="d-md-none push">
                <button type="button" class="btn w-100 btn-alt-primary" data-toggle="class-toggle" data-target="#side-content" data-class="d-none">
                    <i class="fa fa-cog text-white"></i> CẤU HÌNH
                </button>
            </div>
            <div id="side-content" class="d-none d-md-block push">
                <h3 class="fs-5"><i class="fa fa-wrench text-primary"></i> CẤU HÌNH</h3>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="tudongsoande" name="tudongsoande" checked>
                    <label class="form-check-label" for="tudongsoande"><i class="fa fa-check-circle text-success"></i> Tự động lấy từ ngân hàng đề</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="xemdiem" name="xemdiem">
                    <label class="form-check-label" for="xemdiem"><i class="fa fa-eye text-info"></i> Xem điểm sau khi thi xong</label>
                </div>
                <div class="form-check form-switch mb-2 d-none">
                    <input class="form-check-input" type="checkbox" id="xemda" name="xemda">
                    <label class="form-check-label" for="xemda"><i class="fa fa-list text-warning"></i> Xem đáp án khi thi xong</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="xembailam" name="xembailam">
                    <label class="form-check-label" for="xembailam"><i class="fa fa-file text-secondary"></i> Xem bài làm khi thi xong</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="daocauhoi" name="daocauhoi">
                    <label class="form-check-label" for="daocauhoi"><i class="fa fa-random text-danger"></i> Đảo câu hỏi</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="daodapan" name="daodapan">
                    <label class="form-check-label" for="daodapan"><i class="fa fa-exchange text-primary"></i> Đảo đáp án</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="tudongnop" name="tudongnop">
                    <label class="form-check-label" for="tudongnop"><i class="fa fa-upload text-warning"></i> Tự động nộp bài khi chuyển tab</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-lg-7 col-xl-9 order-md-0">
        <div class="content content-full">
            <form class="block block-rounded form-tao-de">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        <i class="fa fa-plus-circle text-success"></i> <?php echo $data["Action"] == "create" ? "Tạo mới đề thi" : "Cập nhật đề thi"; ?>
                    </h3>
                </div>
                <div class="block-content">
                    <div class="mb-4">
                        <label class="form-label"><i class="fa fa-font text-primary"></i> Tên đề kiểm tra</label>
                        <input type="text" class="form-control" id="name-exam" name="tende" placeholder="Nhập tên đề kiểm tra">
                    </div>
                   <div class="row mb-4">
    <div class="col-xl-6">
        <label for="time-start" class="form-label">
            <i class="fa fa-play text-success"></i> Thời gian bắt đầu
        </label>
        <input type="text" class="js-flatpickr form-control" id="time-start" name="thoigianbatdau"
               data-enable-time="true" data-time_24hr="true" placeholder="Từ">
    </div>

    <div class="col-xl-6">
        <label for="time-end" class="form-label">
            <i class="fa fa-stop text-danger"></i> Thời gian kết thúc
        </label>
        <input type="text" class="js-flatpickr form-control" id="time-end" name="thoigianketthuc"
               data-enable-time="true" data-time_24hr="true" placeholder="Đến">
    </div>
</div>

                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-stopwatch text-secondary"></i></span>
                            <input type="number" class="form-control text-center" id="exam-time" name="thoigianthi" placeholder="00">
                            <span class="input-group-text">phút</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="block block-rounded border">
                            <div class="block-header block-header-default">
                                <h3 class="block-title"><i class="fa fa-users text-success"></i> Giao cho</h3>
                                <div class="block-option">
                                    <select class="js-select2 form-select" id="nhom-hp" name="manhom" style="width: 100%;" data-placeholder="Chọn nhóm học phần giảng dạy..." <?php if ($data["Action"] == "update") {
                                        echo "disabled";
                                    } ?>>
                                    </select>
                                    <input type="hidden" name="mamonhoc" id="mamonhoc" value="">
                                </div>
                            </div>
                            <div class="block-content pb-3">
                                <div class="row" id="list-group">
                                    <div class="text-center fs-sm"><img style="width:100px" src="./public/media/svg/empty_data.png" alt=""></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 show-chap" id="chuong-container">
  <label for="chuong" class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
    <i class="fa fa-book-open text-primary me-2 fs-5"></i> Chọn chương
  </label>
  <div id="chuong" class="d-flex flex-column gap-2"></div>
</div>

                    


                    
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label"><i class="fa fa-check-circle text-success"></i> Số câu dễ</label>
                                <input type="number" class="form-control" name="socaude" id="coban" min="0" step="1">
                            </div>
                            <div class="col-4">
                                <label class="form-label"><i class="fa fa-minus-circle text-info"></i> Số câu trung bình</label>
                                <input type="number" class="form-control" name="socautb" id="trungbinh" min="0" step="1">
                            </div>
                            <div class="col-4">
                                <label class="form-label"><i class="fa fa-times-circle text-danger"></i> Số câu khó</label>
                                <input type="number" class="form-control" name="socaukho" id="kho" min="0" step="1">
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <?php if ($data["Action"] == "create"): ?>
                            <button type="submit" class="btn btn-hero btn-primary" id="btn-add-test">
                                <i class="fa fa-plus-circle text-white"></i> Tạo đề
                            </button>
                        <?php elseif ($data["Action"] == "update"): ?>
                            <button type="submit" class="btn btn-hero btn-primary" id="btn-update-test">
                                <i class="fa fa-save text-white"></i> Cập nhật đề
                            </button>
                        <?php endif; ?>
                        <a class="btn btn-hero btn-success" id="btn-update-quesoftest" data-made="">
                            <i class="fa fa-edit text-white"></i> Chỉnh sửa danh sách câu hỏi
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</form>