Dashmix.helpersOnLoad(["js-flatpickr", "jq-datepicker", "jq-select2"]);

Dashmix.onLoad(() =>
  class {
    static initValidation() {
      Dashmix.helpers("jq-validation"),
        jQuery(".form-taothongbao").validate({
          rules: {
            "name-exam": {
              required: true,
            },
            "nhom-hp": {
              required: true,
            },
          },
          messages: {
            "name-exam": {
              required: "Nhập nội dung thông báo cần gửi",
            },
            "nhom-hp": {
              required: "Vui lòng chọn nhóm học phần",
            },
          },
        });
    }

    static init() {
      this.initValidation();
    }
  }.init()
);

let groups = [];

function showListAnnounce(announces) {
  let html = "";
  if (announces.length !== 0) {
    html += `
      <div class="block block-rounded shadow-sm">
        <div class="block-header block-header-default bg-body-light">
          <h3 class="block-title fw-bold text-primary">
            <i class="fa fa-bullhorn me-2 text-warning"></i> Danh sách thông báo
          </h3>
        </div>
        <div class="block-content">
          <table class="table table-bordered table-striped table-hover table-vcenter">
            <thead class="table-light">
              <tr class="text-center fw-semibold text-uppercase">
                <th style="width: 35%;">Nội dung</th>
                <th style="width: 30%;">Học phần</th>
                <th style="width: 20%;">Tạo lúc</th>
                <th style="width: 15%;">Hành động</th>
              </tr>
            </thead>
            <tbody>
    `;
    announces.forEach((announce) => {
      html += `
              <tr>
                <td class="fw-semibold text-dark">
                  <i class="fa fa-comment-dots text-muted me-1"></i> ${
                    announce.noidung
                  }
                </td>
                <td class="text-center text-secondary">
                  <i class="fa fa-layer-group me-1 text-info"></i>
                  <strong data-bs-toggle="tooltip" data-bs-animation="true" data-bs-placement="top" style="cursor:pointer"
                    title="${announce.nhom}">
                    ${announce.tenmonhoc} - NH${announce.namhoc} - HK${
        announce.hocky
      }
                  </strong>
                </td>
                <td class="text-center">
                  <i class="fa fa-clock me-1 text-muted"></i> ${formatDate(
                    announce.thoigiantao
                  )}
                </td>
                <td class="text-center">
                  <a class="btn btn-sm btn-alt-primary rounded-pill px-3 me-1 my-1" 
                     href="./teacher_announcement/update/${announce.matb}"
                     data-role="thongbao" data-action="update">
                    <i class="fa fa-edit me-1"></i> Sửa
                  </a>
                  <a class="btn btn-sm btn-alt-danger rounded-pill px-3 my-1 btn-delete"
                     href="javascript:void(0)" 
                     data-role="thongbao" data-action="delete" 
                     data-id="${announce.matb}">
                    <i class="fa fa-trash-alt me-1"></i> Xoá
                  </a>
                </td>
              </tr>
      `;
    });
    html += `
            </tbody>
          </table>
        </div>
      </div>
    `;
  } else {
    html += `<div class="alert alert-info text-center py-3 mb-0">
               <i class="fa fa-info-circle me-1"></i> Không có thông báo nào được tìm thấy.
             </div>`;
    $(".pagination").hide();
  }

  $(".list-announces").html(html);
  $('[data-bs-toggle="tooltip"]').tooltip();
}
function loadFilterSemesters() {
  $.ajax({
    type: "POST",
    url: "./module/loadData",
    data: { hienthi: 2 },
    dataType: "json",
    success: function (response) {
      let html = '<option value="">Tất cả học kỳ</option>';
      const seen = new Set();

      response.forEach((item) => {
        const key = `${item.namhoc}-${item.hocky}`;
        if (!seen.has(key)) {
          seen.add(key);
          html += `<option value="${key}">${item.namhoc} - HK${item.hocky}</option>`;
        }
      });

      $("#filter-kihoc").html(html);
    },
    error: function (xhr, status, error) {
      console.error("Error loading semesters:", status, error);
    },
  });
}

function loadFilterSubjects(kihoc = null) {
  return new Promise((resolve, reject) => {
    const data = { hienthi: 1 };
    if (kihoc) {
      const [namhoc, hocky] = kihoc.split("-");
      data.namhoc = namhoc;
      data.hocky = hocky;
    }

    $.ajax({
      type: "POST",
      url: "./module/loadData",
      data: data,
      dataType: "json",
      success: function (response) {
        let html = '<option value="">Tất cả học phần</option>';
        const seen = new Set();
        response.forEach((item) => {
          if (!seen.has(item.tenmonhoc)) {
            seen.add(item.tenmonhoc);
            html += `<option value="${item.mamonhoc}">${item.tenmonhoc}</option>`;
          }
        });
        $("#filter-nhomhocphan").html(html);
        resolve();
      },
      error: function (xhr, status, error) {
        console.error("Error loading subjects:", status, error);
        reject(error);
      },
    });
  });
}

$(document).ready(function () {
  function applyFilters() {
    const keyword = $("#search-input").val().trim();
    const kihoc = $("#filter-kihoc").val();
    const mamonhoc = $("#filter-nhomhocphan").val();

    const filter = {};

    // Nếu có học kỳ, tách năm học và học kỳ ra
    if (kihoc) {
      const [namhoc, hocky] = kihoc.split("-");
      filter.namhoc = namhoc;
      filter.hocky = hocky;
    }

    // Nếu có môn học được chọn
    if (mamonhoc) {
      filter.mamonhoc = mamonhoc;
    }
    if (keyword) {
      filter.keyword = keyword;
    }

    console.log("Applying filters:", filter);

    mainPagePagination.getPagination(
      {
        ...mainPagePagination.option,
        input: keyword,
        filter: filter,
      },
      1
    );
  }

  function showGroup() {
    let html = "<option value='' disabled selected>Chọn nhóm học phần</option>";
    $.ajax({
      type: "POST",
      url: "./module/loadData",
      async: false,
      data: { hienthi: 1 },
      dataType: "json",
      success: function (response) {
        console.log("Dữ liệu nhóm học phần:", response); // Debug
        groups = response;
        response.forEach((item, index) => {
          html += `<option value="${index}">${item.mamonhoc} - ${item.tenmonhoc} - NH${item.namhoc} - HK${item.hocky}</option>`;
        });
        $("#nhom-hp").html(html);
      },
      error: function (xhr, status, error) {
        console.error("Error loading groups:", status, error, xhr.responseText);
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times-circle me-1",
          message: "Lỗi khi tải danh sách nhóm học phần!",
        });
      },
    });
    loadFilterSubjects();
  }
  function showListGroup(index) {
    let html = ``;
    if (groups[index].nhom.length > 0) {
      html += `<div class="col-12 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="select-all-group">
                <label class="form-check-label" for="select-all-group">Chọn tất cả</label>
            </div></div>`;
      groups[index].nhom.forEach((item) => {
        html += `<div class="col-4">
                    <div class="form-check">
                        <input class="form-check-input select-group-item" type="checkbox" value="${item.manhom}"
                            id="nhom-${item.manhom}" name="nhom-${item.manhom}">
                        <label class="form-check-label" for="nhom-${item.manhom}">${item.tennhom}</label>
                    </div>
                </div>`;
      });
    } else {
      html += `<div class="text-center fs-sm"><img style="width:100px" src="./public/media/svg/empty_data.png" alt=""></div>`;
    }
    $("#list-group").html(html);
  }

  showGroup();
  loadFilterSemesters();

  $("#nhom-hp").on("change", function () {
    let index = $(this).val();
    if (index) showListGroup(index);
  });

  $(document).on("click", "#select-all-group", function () {
    let check = $(this).prop("checked");
    $(".select-group-item").prop("checked", check);
  });

  function getGroupSelected() {
    let result = [];
    $(".select-group-item").each(function () {
      if ($(this).prop("checked")) {
        result.push($(this).val());
      }
    });
    return result;
  }

  $("#btn-send-announcement").click(function (e) {
    e.preventDefault();
    if ($(".form-taothongbao").valid()) {
      if (getGroupSelected().length !== 0) {
        let nowDate = new Date();
        let format = `${nowDate.getFullYear()}/${
          nowDate.getMonth() + 1
        }/${nowDate.getDate()} ${nowDate.getHours()}:${nowDate.getMinutes()}:${nowDate.getSeconds()}`;

        $.ajax({
          type: "POST",
          url: "./teacher_announcement/sendAnnouncement",
          data: {
            noticeText: $("#name-exam").val(),
            mamonhoc: groups[$("#nhom-hp").val()].mamonhoc,
            manhom: getGroupSelected(),
            thoigiantao: format,
          },
          dataType: "json",
          success: function (response) {
            if (response) {
              Dashmix.helpers("jq-notify", {
                type: "success",
                icon: "fa fa-check-circle me-1",
                message: "Đã gửi thông báo thành công!",
              });
              setTimeout(() => {
                location.href = "./teacher_announcement";
              }, 1500);
            } else {
              Dashmix.helpers("jq-notify", {
                type: "danger",
                icon: "fa fa-times-circle me-1",
                message: "Gửi thông báo thất bại! Vui lòng kiểm tra lại.",
              });
            }
          },
          error: function (xhr, status, error) {
            Dashmix.helpers("jq-notify", {
              type: "danger",
              icon: "fa fa-exclamation-triangle me-1",
              message: "Lỗi hệ thống! Vui lòng thử lại sau.",
            });
            console.error("Lỗi AJAX:", status, error);
          },
        });
      } else {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times-circle me-1",
          message: "Vui lòng chọn ít nhất một nhóm học phần!",
        });
      }
    } else {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times-circle me-1",
        message:
          "Vui lòng nhập đầy đủ nội dung thông báo và chọn nhóm học phần!",
      });
    }
  });

  function loadListAnnounces() {
    return $.ajax({
      type: "POST",
      url: "./teacher_announcement/getListAnnounce",
      dataType: "json",
      success: function (data) {
        console.log("Announces:", data);
        showListAnnounce(data);
      },
      error: function (xhr, status, error) {
        console.error("Error loading announces:", status, error);
      },
    });
  }

  let e = Swal.mixin({
    buttonsStyling: false,
    target: "#page-container",
    customClass: {
      confirmButton: "btn btn-success m-1",
      cancelButton: "btn btn-danger m-1",
      input: "form-control",
    },
  });

  $(document).on("click", ".btn-delete", function () {
    e.fire({
      title: "Are you sure?",
      text: "Bạn có chắc chắn muốn xoá thông báo?",
      icon: "warning",
      showCancelButton: true,
      customClass: {
        confirmButton: "btn btn-danger m-1",
        cancelButton: "btn btn-secondary m-1",
      },
      confirmButtonText: "Vâng, tôi chắc chắn!",
      html: false,
      preConfirm: () =>
        new Promise((resolve) => {
          setTimeout(() => {
            resolve();
          }, 50);
        }),
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          url: "./teacher_announcement/deleteAnnounce",
          data: {
            matb: $(this).data("id"),
          },
          dataType: "json",
          success: function (response) {
            if (response) {
              e.fire("Deleted!", "Xóa thông báo thành công!", "success");
              mainPagePagination.getPagination(
                mainPagePagination.option,
                mainPagePagination.valuePage.curPage
              );
            } else {
              e.fire("Lỗi!", "Xoá thông báo không thành công!", "error");
            }
          },
          error: function (xhr, status, error) {
            console.error("Error deleting announce:", status, error);
            e.fire("Lỗi!", "Lỗi hệ thống! Vui lòng thử lại.", "error");
          },
        });
        applyFilters();
      }
    });
  });
  $("#filter-kihoc").on("change", function () {
    const selectedSemester = $(this).val(); // ví dụ "2025-1"
    loadFilterSubjects(selectedSemester).then(() => {
      applyFilters();
    });
  });

  $("#filter-kihoc, #filter-nhomhocphan").on("change", function () {
    applyFilters();
  });

  $("#search-input").on("keypress", function (e) {
    if (e.which === 13) {
      applyFilters();
    }
  });

  const container = document.querySelector(".content");
  const currentUser = container.dataset.id;
  delete container.dataset.id;

  const mainPagePagination = new Pagination(null, null, showListAnnounce);
  mainPagePagination.option.controller = "teacher_announcement";
  mainPagePagination.option.model = "AnnouncementModel";
  mainPagePagination.option.id = currentUser;
  mainPagePagination.getPagination(
    mainPagePagination.option,
    mainPagePagination.valuePage.curPage
  );
});
