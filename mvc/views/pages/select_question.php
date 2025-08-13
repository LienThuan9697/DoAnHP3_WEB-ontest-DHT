<div class="row g-0 flex-md-grow-1">
    <div class="col-lg-4 col-xl-4 h100-scroll">
        <div class="content px-1">
            <div class="row g-sm d-lg-none push">
                <div class="col-6">
                    <button type="button" class="btn btn-primary w-100" data-toggle="layout"
                        data-action="sidebar_toggle">
                        <i class="fa fa-bars opacity-50 me-1"></i> Menu
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-alt-primary w-100" data-toggle="class-toggle"
                        data-target="#side-content" data-class="d-none">
                        <i class="fa fa-envelope opacity-50 me-1"></i> Câu hỏi
                    </button>
                </div>
            </div>
            <div id="side-content" class="d-none d-lg-block push">
                <form action="#" method="POST" id="search-form" onsubmit="return false;">
                    <div class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-input" name="search-input" placeholder="Tìm kiếm câu hỏi...">
                            <!-- <input type="text" class="form-control" placeholder="Tìm kiếm câu hỏi.." id="search-content"> -->
                            <span class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
                <div class="d-flex justify-content-between mb-2">
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-link fw-semibold dropdown-toggle"
                            id="inbox-msg-sort" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Chương
                        </button>
                        <div class="dropdown-menu fs-sm" aria-labelledby="inbox-msg-sort" id="list-chapter">
                            <a class="dropdown-item" href="javascript:void(0)">1</a>
                            <a class="dropdown-item" href="javascript:void(0)">2</a>
                            <a class="dropdown-item" href="javascript:void(0)">Tất cả</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-link fw-semibold dropdown-toggle"
                            id="inbox-msg-filter" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Độ khó
                        </button>
                        <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="inbox-msg-filter">
                            <a class="dropdown-item active data-dokho" href="javascript:void(0)" data-id="0">Tất cả</a>
                            <a class="dropdown-item data-dokho" href="javascript:void(0)" data-id="1">Dễ</a>
                            <a class="dropdown-item data-dokho" href="javascript:void(0)" data-id="2">Trung bình</a>
                            <a class="dropdown-item data-dokho" href="javascript:void(0)" data-id="3">Khó</a>
                        </div>
                    </div>
                </div>
                <ul class="list-group fs-sm" id="list-question">
                    <!-- Danh sách câu hỏi -->
                </ul>
                <?php if (isset($data["Plugin"]["pagination"])) {
                    require "./mvc/views/inc/pagination.php";
                }?>
            </div>
        </div>
    </div>
   <div class="col-lg-8 col-xl-8 h100-scroll bg-body-dark">
    <div class="content px-4 py-4">
        <div class="block block-rounded shadow-sm border border-light-subtle bg-white">
            <!-- Header chọn số lượng -->
            <div class="block-content bg-light border-bottom rounded-top py-4 px-3">
                <div class="d-flex flex-column align-items-center text-center gap-3">

                    <span class="fw-bold fs-5 text-dark">Số lượng:</span>

                    <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
                        <!-- Dễ -->
                        <button type="button" class="btn btn-outline-success d-flex align-items-center gap-2 rounded-pill px-3 py-2">
                            <span class="fw-semibold">Dễ</span>
                            <span class="badge bg-success text-white rounded-pill px-2">
                                <span id="slcaude">0</span>/<span id="ttcaude">0</span>
                            </span>
                        </button>

                        <!-- Trung bình -->
                        <button type="button" class="btn btn-outline-warning d-flex align-items-center gap-2 rounded-pill px-3 py-2">
                            <span class="fw-semibold">Trung bình</span>
                            <span class="badge bg-warning text-dark rounded-pill px-2">
                                <span id="slcautb">0</span>/<span id="ttcautb">0</span>
                            </span>
                        </button>

                        <!-- Khó -->
                        <button type="button" class="btn btn-outline-danger d-flex align-items-center gap-2 rounded-pill px-3 py-2">
                            <span class="fw-semibold">Khó</span>
                            <span class="badge bg-danger text-white rounded-pill px-2">
                                <span id="slcaukho">0</span>/<span id="ttcaukho">0</span>
                            </span>
                        </button>

                        <!-- Nút tạo đề thi -->
                        <button type="button" class="btn btn-primary btn-lg d-flex align-items-center gap-2 rounded-pill px-4" id="save-test">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span class="fw-semibold">Tạo đề thi</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="block-content px-4 py-4">
                <h4 class="text-center text-primary mb-2 fw-bold" id="name-test">Tên đề thi</h4>
                <p class="text-center text-muted mb-4">Thời gian làm bài: <span id="test-time">--</span> phút</p>
                <div id="list-question-of-test">
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<style>
header,
footer {
    display: none !important
}

#page-container.page-header-fixed #main-container {
    padding-top: 0 !important
}
</style>