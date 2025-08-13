Dashmix.helpersOnLoad(["js-flatpickr", "jq-datepicker", "jq-select2"]);

let groups = [];

function getToTalQuestionOfChapter(chapters, monhoc, dokho) {
  var result = 0;
  console.log(
    `Gửi yêu cầu: chapters=${JSON.stringify(
      chapters
    )}, monhoc=${monhoc}, dokho=${dokho}`
  );
  $.ajax({
    url: "./question/getsoluongcauhoi",
    type: "post",
    data: {
      chuong: Array.isArray(chapters) ? chapters : [chapters].filter(Boolean),
      monhoc: monhoc,
      dokho: dokho,
    },
    async: false,
    success: function (response) {
      result = parseInt(response) || 0;
      console.log(`Kết quả: ${result} câu cho dokho=${dokho}`);
    },
    error: function (xhr, status, error) {
      console.error("Lỗi AJAX: ", error, xhr.responseText);
    },
  });
  return result;
}
function updateQuestionCounts() {
  let chapters = getSelectedChapters();
  let m = $("#nhom-hp").val() ? groups[$("#nhom-hp").val()].mamonhoc : 0;
  let isAuto = $("#tudongsoande").prop("checked");

  // Kiểm tra cho cả đề thủ công và tự động
  if (chapters.length === 0 || !m) {
    $("#coban").val(0);
    $("#trungbinh").val(0);
    $("#kho").val(0);
    $("#coban-error").text("Vui lòng chọn chương và môn học");
    $("#trungbinh-error").text("Vui lòng chọn chương và môn học");
    $("#kho-error").text("Vui lòng chọn chương và môn học");
    return;
  }

  let availableEasy = getToTalQuestionOfChapter(chapters, m, 1);
  let availableMedium = getToTalQuestionOfChapter(chapters, m, 2);
  let availableHard = getToTalQuestionOfChapter(chapters, m, 3);

  $("#coban-error").text(`Có ${availableEasy} câu dễ`);
  $("#trungbinh-error").text(`Có ${availableMedium} câu trung bình`);
  $("#kho-error").text(`Có ${availableHard} câu khó`);

  jQuery(".form-taodethi").valid();
}
$.validator.addMethod(
  "validSoLuong",
  function (value, element, param) {
    let chapters = getSelectedChapters();
    let m = $("#nhom-hp").val() ? groups[$("#nhom-hp").val()].mamonhoc : 0;
    let parsedValue = parseFloat(value);

    if (parsedValue < 0) {
      return false;
    }
    if (parsedValue % 1 !== 0) {
      return false;
    }

    // Kiểm tra số lượng câu hỏi cho cả đề thủ công và tự động
    let result = getToTalQuestionOfChapter(chapters, m, param);
    console.log(`Validating: Value=${value}, Result=${result}, Dokho=${param}`);
    return result >= parseInt(value);
  },
  function (params, element) {
    let value = parseFloat($(element).val());
    if (value < 0) {
      return "Số câu hỏi không được âm";
    }
    if (value % 1 !== 0) {
      return "Số câu hỏi phải là số nguyên";
    }
    let chapters = getSelectedChapters();
    let m = $("#nhom-hp").val() ? groups[$("#nhom-hp").val()].mamonhoc : 0;
    let result = getToTalQuestionOfChapter(chapters, m, params);
    return `Chỉ có ${result} câu hỏi mức độ ${
      params == 1 ? "dễ" : params == 2 ? "trung bình" : "khó"
    }, bạn yêu cầu ${$(element).val()} câu`;
  }
);
function getMinutesBetweenDates(start, end) {
  const startDate = new Date(start);
  const endDate = new Date(end);
  const diffMs = endDate.getTime() - startDate.getTime();
  return Math.round(diffMs / 60000);
}

function showGroup() {
  let html = "<option></option>";
  $.ajax({
    type: "post",
    url: "./module/loadData",
    async: false,
    data: {
      hienthi: 1,
    },
    dataType: "json",
    success: function (response) {
      groups = response;
      response.forEach((item, index) => {
        html += `<option value="${index}">${
          item.mamonhoc +
          " - " +
          item.tenmonhoc +
          " - NH" +
          item.namhoc +
          " - HK" +
          item.hocky
        }</option>`;
      });
      $("#nhom-hp").html(html);
    },
  });
}
$("#nhom-hp").on("change", function () {
  let index = $(this).val();
  let mamonhoc = groups[index].mamonhoc;
  showListGroup(index);
  showChapter(mamonhoc);
});

function showChapter(mamonhoc) {
  let html = `
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="select-all-chapters">
      <label class="form-check-label fw-semibold" for="select-all-chapters">
        <i class="me-1 text-success"></i> Chọn tất cả chương
      </label>
    </div>`;

  $.ajax({
    type: "post",
    url: "./subject/getAllChapter",
    async: false,
    data: { mamonhoc: mamonhoc },
    dataType: "json",
    success: function (data) {
      data.forEach((item) => {
        html += `
          <div class="form-check">
            <input class="form-check-input select-chapter-item" type="checkbox"
              value="${item.machuong}" id="chuong-${item.machuong}">
            <label class="form-check-label" for="chuong-${item.machuong}">
              ${item.tenchuong}
            </label>
          </div>`;
      });
      $("#chuong").html(html);
    },
  });
}

$(document).on("click", "#select-all-chapters", function () {
  let check = $(this).prop("checked");
  $(".select-chapter-item").prop("checked", check);
  updateQuestionCounts();
});

function getSelectedChapters() {
  let result = [];
  $(".select-chapter-item").each(function () {
    if ($(this).prop("checked")) {
      result.push($(this).val());
    }
  });
  return result;
}
function showListGroup(index) {
  let html = "";
  if (groups[index] && groups[index].nhom.length > 0) {
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

function getGroupSelected() {
  let result = [];
  $(".select-group-item").each(function () {
    if ($(this).prop("checked")) {
      result.push($(this).val());
    }
  });
  return result;
}

Dashmix.onLoad(() =>
  class {
    static initValidation() {
      Dashmix.helpers("jq-validation");
      $.validator.addMethod(
        "validTimeEnd",
        function (value) {
          var startTime = new Date($("#time-start").val());
          var currentTime = new Date();
          var endTime = new Date(value);
          return endTime > startTime && endTime > currentTime;
        },
        "Thời gian kết thúc phải lớn hơn thời gian bắt đầu và không bé hơn thời gian hiện tại"
      );
      $.validator.addMethod(
        "atLeastOneQuestion",
        function () {
          const easy = parseInt($("#coban").val()) || 0;
          const medium = parseInt($("#trungbinh").val()) || 0;
          const hard = parseInt($("#kho").val()) || 0;
          return easy + medium + hard > 0;
        },
        "Phải có ít nhất 1 câu hỏi."
      );

      $.validator.addMethod(
        "validTimeStart",
        function (value) {
          var startTime = new Date(value);
          var currentTime = new Date();
          return startTime > currentTime;
        },
        "Thời gian bắt đầu không được bé hơn thời gian hiện tại"
      );

      $.validator.addMethod(
        "validThoigianthi",
        function (value) {
          let startTime = new Date($("#time-start").val());
          let endTime = new Date($("#time-end").val());
          return (
            startTime < endTime &&
            parseInt(getMinutesBetweenDates(startTime, endTime)) >=
              parseInt(value)
          );
        },
        "Thời gian làm bài không hợp lệ"
      );

      jQuery(".form-taodethi").validate({
        rules: {
          "name-exam": { required: true },
          "time-start": { required: true, validTimeStart: true },
          "time-end": { required: true, validTimeEnd: true },
          "exam-time": { required: true, digits: true, validThoigianthi: true },
          "nhom-hp": { required: true },
          user_nhomquyen: { required: true },
          chuong: {
            required: function () {
              return getSelectedChapters().length > 0;
            },
          },
          coban: {
            required: true,
            digits: true,
            validSoLuong: 1,
            atLeastOneQuestion: true,
          },
          trungbinh: {
            required: true,
            digits: true,
            validSoLuong: 2,
            atLeastOneQuestion: true,
          },
          kho: {
            required: true,
            digits: true,
            validSoLuong: 3,
            atLeastOneQuestion: true,
          },
        },
        messages: {
          "name-exam": { required: "Vui lòng nhập tên đề kiểm tra" },
          "time-start": {
            required: "Vui lòng chọn thời điểm bắt đầu của bài kiểm tra",
            validTimeStart:
              "Thời gian bắt đầu không được bé hơn thời gian hiện tại",
          },
          "time-end": {
            required: "Vui lòng chọn thời điểm kết thúc của bài kiểm tra",
            validTimeEnd: "Thời gian kết thúc không hợp lệ",
          },
          "exam-time": {
            required: "Vui lòng chọn thời gian làm bài kiểm tra",
          },
          "nhom-hp": { required: "Vui lòng chọn nhóm học phần giảng dạy" },
          chuong: {
            required: "Vui lòng chọn ít nhất một chương cho đề kiểm tra",
          },
          coban: {
            required: "Vui lòng cho biết số câu dễ",
            digits: "Vui lòng nhập số",
          },
          trungbinh: {
            required: "Vui lòng cho biết số câu trung bình",
            digits: "Vui lòng nhập số",
          },
          kho: {
            required: "Vui lòng cho biết số câu khó",
            digits: "Vui lòng nhập số",
          },
        },
      });
    }
    static init() {
      this.initValidation();
    }
  }.init()
);

$(document).ready(function () {
  // Xử lý cắt URL để lấy mã đề thi
  let url = location.href.split("/");
  let param = url[url.length - 2] == "update" ? url[url.length - 1] : 0;
  if (param) {
    getDetail(param);
  }

  // Sự kiện thay đổi nhóm học phần
  $("#nhom-hp").on("select2:select", function () {
    let index = $(this).val();
    if (index && groups[index]) {
      let mamonhoc = groups[index].mamonhoc;
      showListGroup(index);
      showChapter(mamonhoc);
      updateQuestionCounts();
    }
  });

  // Sự kiện thay đổi chương
  $("#chuong").on("select2:select", function () {
    updateQuestionCounts();
  });

  $("#tudongsoande").on("change", function () {
    $(".show-chap").toggle();
    if (!$(this).prop("checked")) {
      $("#chuong").val("").trigger("change");
      $("#coban").val(0);
      $("#trungbinh").val(0);
      $("#kho").val(0);
    }
    updateQuestionCounts();
  });

  // Sự kiện thay đổi các checkbox khác
  $("#xemdiem, #xemda, #xembailam, #daocauhoi, #daodapan, #tudongnop").on(
    "change",
    function () {
      updateQuestionCounts();
    }
  );

  // Chọn hoặc bỏ chọn tất cả nhóm
  $(document).on("click", "#select-all-group", function () {
    let check = $(this).prop("checked");
    $(".select-group-item").prop("checked", check);
  });

  // Xử lý nút tạo đề
  $("#btn-add-test").click(function (e) {
    e.preventDefault();
    if ($(".form-taodethi").valid()) {
      let chapters = getSelectedChapters();
      let m = $("#nhom-hp").val() ? groups[$("#nhom-hp").val()].mamonhoc : 0;
      let socaude = parseInt($("#coban").val()) || 0;
      let socautb = parseInt($("#trungbinh").val()) || 0;
      let socaukho = parseInt($("#kho").val()) || 0;

      if (socaude + socautb + socaukho === 0) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times me-1",
          message: "Phải có ít nhất 1 câu hỏi!",
        });
        return;
      }

      let valid = true;
      // Kiểm tra số lượng câu hỏi cho cả đề thủ công và tự động
      let availableEasy = getToTalQuestionOfChapter(chapters, m, 1);
      let availableMedium = getToTalQuestionOfChapter(chapters, m, 2);
      let availableHard = getToTalQuestionOfChapter(chapters, m, 3);

      if (availableEasy < socaude) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times me-1",
          message: `Chỉ có ${availableEasy} câu dễ, bạn yêu cầu ${socaude}!`,
        });
        valid = false;
      }
      if (availableMedium < socautb) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times me-1",
          message: `Chỉ có ${availableMedium} câu trung bình, bạn yêu cầu ${socautb}!`,
        });
        valid = false;
      }
      if (availableHard < socaukho) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times me-1",
          message: `Chỉ có ${availableHard} câu khó, bạn yêu cầu ${socaukho}!`,
        });
        valid = false;
      }

      if (valid && getGroupSelected().length > 0) {
        $.ajax({
          type: "post",
          url: "./test/addTest",
          data: {
            mamonhoc: m,
            tende: $("#name-exam").val(),
            thoigianthi: $("#exam-time").val(),
            thoigianbatdau: $("#time-start").val(),
            thoigianketthuc: $("#time-end").val(),
            socaude: socaude,
            socautb: socautb,
            socaukho: socaukho,
            chuong: chapters,
            loaide: $("#tudongsoande").prop("checked") ? 1 : 0,
            xemdiem: $("#xemdiem").prop("checked") ? 1 : 0,
            xemdapan: $("#xemda").prop("checked") ? 1 : 0,
            xembailam: $("#xembailam").prop("checked") ? 1 : 0,
            daocauhoi: $("#daocauhoi").prop("checked") ? 1 : 0,
            daodapan: $("#daodapan").prop("checked") ? 1 : 0,
            tudongnop: $("#tudongnop").prop("checked") ? 1 : 0,
            manhom: getGroupSelected(),
          },
          dataType: "json",
          success: function (response) {
            console.log("Response:", response);
            if (response && response.success && response.made) {
              Dashmix.helpers("jq-notify", {
                type: "success",
                icon: "fa fa-check me-1",
                message: "Tạo đề thi thành công!",
              });
              setTimeout(function () {
                if ($("#tudongsoande").prop("checked")) {
                  location.href = "./test";
                } else {
                  location.href = `./test/select/${response.made}`;
                }
              }, 2000);
            } else {
              Dashmix.helpers("jq-notify", {
                type: "danger",
                icon: "fa fa-times me-1",
                message:
                  response.error ||
                  "Tạo đề thi không thành công! Vui lòng kiểm tra dữ liệu hoặc liên hệ quản trị viên.",
              });
            }
          },
          error: function (xhr, status, error) {
            console.error("Error creating test:", error, xhr.responseText);
            Dashmix.helpers("jq-notify", {
              type: "danger",
              icon: "fa fa-times me-1",
              message: `Lỗi hệ thống khi tạo đề thi: ${
                xhr.responseText || error
              }`,
            });
          },
        });
      } else if (getGroupSelected().length === 0) {
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times me-1",
          message: "Bạn phải chọn ít nhất một nhóm học phần!",
        });
      }
    }
  });
  // Trong $("#btn-update-test").click
  $("#btn-update-test").click(function (e) {
    e.preventDefault();

    if (
      (!checkDate(infodethi.thoigianbatdau) && $(".form-taodethi").valid()) ||
      validUpdate()
    ) {
      let loaide = $("#tudongsoande").prop("checked") ? 1 : 0;
      let made = $(this).data("id");
      let socaude = $("#coban").val();
      let socautb = $("#trungbinh").val();
      let socaukho = $("#kho").val();

      $.ajax({
        type: "post",
        url: "./test/updateTest",
        data: {
          made: made,
          mamonhoc: groups[$("#nhom-hp").val()].mamonhoc,
          tende: $("#name-exam").val(),
          thoigianthi: $("#exam-time").val(),
          thoigianbatdau: $("#time-start").val(),
          thoigianketthuc: $("#time-end").val(),
          socaude: socaude,
          socautb: socautb,
          socaukho: socaukho,
          chuong: $("#chuong").val(),
          loaide: loaide,
          xemdiem: $("#xemdiem").prop("checked") ? 1 : 0,
          xemdapan: $("#xemda").prop("checked") ? 1 : 0,
          xembailam: $("#xembailam").prop("checked") ? 1 : 0,
          daocauhoi: $("#daocauhoi").prop("checked") ? 1 : 0,
          daodapan: $("#daodapan").prop("checked") ? 1 : 0,
          tudongnop: $("#tudongnop").prop("checked") ? 1 : 0,
          manhom: getGroupSelected(),
        },
        success: function (response) {
          if (response) {
            Dashmix.helpers("jq-notify", {
              type: "success",
              icon: "fa fa-check me-1",
              message: "Cập nhật đề thi thành công!",
            });

            setTimeout(function () {
              if (
                (infodethi.loaide == 1 && loaide == 0) ||
                (loaide == 0 &&
                  (infodethi.socaude != socaude ||
                    infodethi.socautb != socautb ||
                    infodethi.socaukho != socaukho))
              ) {
                location.href = `./test/select/${made}`;
              } else {
                location.href = `./test`;
              }
            }, 2000);
          } else {
            Dashmix.helpers("jq-notify", {
              type: "danger",
              icon: "fa fa-times me-1",
              message: "Cập nhật đề thi không thành công!",
              delay: 10000,
            });
          }
        },
      });
    }
  });

  function checkDate(time) {
    let dateToCompare = new Date(time);
    let currentTime = new Date();
    return dateToCompare.getTime() < currentTime.getTime();
  }

  function showInfo(dethi) {
    let checkD = checkDate(dethi.thoigianbatdau);
    $("#name-exam").val(dethi.tende);
    $("#exam-time").val(dethi.thoigianthi);
    $("#exam-time").prop("disabled", checkD);
    $("#time-start").flatpickr({
      enableTime: true,
      altInput: true,
      allowInput: checkD,
      defaultDate: dethi.thoigianbatdau,
      onReady: function (selectedDates, dateStr, instance) {
        if (checkD) {
          $(instance.input).prop("disabled", true);
          instance._input.disabled = true;
        }
      },
    });
    $("#time-end").flatpickr({
      enableTime: true,
      altInput: true,
      allowInput: true,
      defaultDate: dethi.thoigianketthuc,
    });
    $("#coban").val(dethi.socaude);
    $("#coban").prop("disabled", checkD);
    $("#trungbinh").val(dethi.socautb);
    $("#trungbinh").prop("disabled", checkD);
    $("#kho").val(dethi.socaukho);
    $("#kho").prop("disabled", checkD);
    $("#tudongsoande").prop("checked", dethi.loaide == "1");
    $("#tudongsoande").prop("disabled", checkD);
    $("#xemdiem").prop("checked", dethi.xemdiemthi == "1");
    $("#xemda").prop("checked", dethi.xemdapan == "1");
    $("#xembailam").prop("checked", dethi.hienthibailam == "1");
    $("#daocauhoi").prop("checked", dethi.troncauhoi == "1");
    $("#daodapan").prop("checked", dethi.trondapan == "1");
    $("#tudongnop").prop("checked", dethi.nopbaichuyentab == "1");
    $("#btn-update-test").data("id", dethi.made);
    $.when(showGroup(), showChapter(dethi.monthi)).done(function () {
      $("#nhom-hp").val(findIndexGroup(dethi.nhom[0])).trigger("change");
      setGroup(dethi.nhom, dethi.thoigianbatdau);

      if (dethi.loaide == "1") {
        $(".show-chap").show();

        if (Array.isArray(dethi.chuong)) {
          dethi.chuong.forEach(function (machuong) {
            $("#chuong input[type=checkbox][value='" + machuong + "']").prop(
              "checked",
              true
            );
          });
        }

        if (checkD) {
          $("#chuong-container")
            .find("input, select, textarea, button")
            .prop("disabled", true)
            .css({ "pointer-events": "none", opacity: "0.6" });
        }
      } else {
        $(".show-chap").hide();
      }
    });
  }
  function findIndexGroup(manhom) {
    let i = 0;
    let index = -1;
    while (i < groups.length && index == -1) {
      index = groups[i].nhom.findIndex((item) => item.manhom == manhom);
      if (index == -1) i++;
    }
    return i;
  }

  function setGroup(list, date) {
    let v = checkDate(date);
    $("#select-all-group").prop("disabled", v);
    list.forEach((item) => {
      $(`.select-group-item[value='${item}']`)
        .prop("checked", true)
        .prop("disabled", v);
    });
  }

  function validUpdate() {
    let check = true;
    if ($("#name-exam").val() == "") {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Tên đề không được để trống",
      });
      check = false;
    }
    let startTime = new Date($("#time-start").val());
    let endTime = new Date($("#time-end").val());

    if (endTime <= startTime) {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Thời gian kết thúc không được bé hơn thời gian bắt đầu",
      });
      check = false;
    }

    if (endTime < new Date(infodethi.thoigianketthuc)) {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Thời gian kết thúc không được bé hơn thời gian kết thúc cũ",
      });
      check = false;
    }

    if (
      endTime > startTime &&
      getMinutesBetweenDates(startTime, endTime) <
        parseInt(infodethi.thoigianthi)
    ) {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Thời gian làm bài không hợp lệ",
      });
      check = false;
    }

    return check;
  }

  showGroup();

  $("#btn-update-quesoftest").hide();
  // Khởi tạo biến đề thi để chứa thông tin đề
  let infodethi;
  function getDetail(made) {
    return $.ajax({
      type: "post",
      url: "./test/getDetail",
      data: {
        made: made,
      },
      dataType: "json",
      success: function (response) {
        if (response.loaide == 0) {
          $("#btn-update-quesoftest").show();
          $("#btn-update-quesoftest").attr(
            "href",
            `./test/select/${response.made}`
          );
        }
        console.log("Dữ liệu từ server:", response);
        console.log("Giá trị xembailam:", response.xembailam);
        infodethi = response;
        showInfo(response);
      },
    });
  }
});
