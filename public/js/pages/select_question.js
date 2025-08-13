// Cắt tham số URL
const url = location.href.split("/");
const made = url[url.length - 1];
// Thông tin đề thi
let infoTest = null;
// Mảng câu hỏi của đề thi
let arrQuestion = [];
// Mảng danh sách câu hỏi từ DB
let questions = [];
// Tổng số trang
let totalpage = 0;
let currentQuestionLists = [];

function getAnswerListForQuestion(questions) {
  console.log("Questions received:", questions);
  if (!questions || questions.length === 0) {
    $("#list-question").html(`<p class="text-center">Không có câu hỏi</p>`);
    return;
  }
  const arrMaCauHoi = questions.map((q) => q.macauhoi);
  console.log("Sending arrMaCauHoi to get answers:", arrMaCauHoi);
  $.ajax({
    type: "post",
    url: "./question/getAnswersForMultipleQuestions",
    data: { questions: arrMaCauHoi },
    dataType: "json",
    success: function (answers) {
      console.log("Answers received from server:", answers);
      if (!answers || !Array.isArray(answers)) {
        console.error("Invalid answers data:", answers);
        return;
      }
      currentQuestionLists = questions.map((question) => {
        const { macauhoi } = question;
        const cautraloi = answers.filter(
          (answer) => String(answer.macauhoi) === String(macauhoi)
        );
        if (cautraloi.length === 0) {
          console.warn(`No answers found for macauhoi: ${macauhoi}`);
        }
        return {
          ...question,
          cautraloi: cautraloi.map(({ macautl, noidungtl, ladapan }) => ({
            macautl,
            macauhoi,
            noidungtl,
            ladapan,
          })),
        };
      });
      console.log("Updated currentQuestionLists:", currentQuestionLists);
      showListQuestion(questions);
    },
    error: function (xhr) {
      console.error("Error fetching answers:", xhr.status, xhr.responseText);
    },
  });
}
function showListQuestion(questions) {
  let html = "";
  const dokhoText = ["", "Dễ", "TB", "Khó"];
  const dokhoColor = ["", "success", "warning", "danger"];

  if (questions && Array.isArray(questions) && questions.length > 0) {
    questions.forEach((question, index) => {
      if (!question.macauhoi || !question.noidungplaintext) {
        console.warn("Invalid question data:", question);
        return;
      }

      const isChecked =
        arrQuestion.findIndex((item) => item.macauhoi == question.macauhoi) !==
        -1
          ? "checked"
          : "";

      const level = parseInt(question.dokho) || 0;
      const badgeText = dokhoText[level] || "Không xác định";
      const badgeClass = dokhoColor[level] || "secondary";

      html += `
        <li class="list-group-item d-flex justify-content-between align-items-start">
          <div class="form-check w-100">
            <input class="form-check-input item-question" type="checkbox"
              id="q-${question.macauhoi}"
              data-id="${question.macauhoi}"
              data-index="${index}" ${isChecked}>
            <label class="form-check-label text-muted ms-2" for="q-${
              question.macauhoi
            }"
              style="word-break: break-word;">
              ${sanitizeHTML(question.noidungplaintext)}
            </label>
          </div>
          <span class="badge bg-${badgeClass} rounded-pill align-self-center" title="Độ khó">
            ${badgeText}
          </span>
        </li>`;
    });
  } else {
    html = `<p class="text-center text-muted py-3"><i class="fa fa-info-circle me-1"></i> Không có câu hỏi</p>`;
  }

  $("#list-question").html(html);
  console.log("HTML generated for list-question:", html);
}

function getInfoTest() {
  return $.ajax({
    type: "post",
    url: "./test/getDetail",
    data: { made },
    dataType: "json",
    success: function (data) {
      if (data && data.made) {
        infoTest = data;
        console.log("Test info loaded:", infoTest); 
      } else {
        console.error("Invalid test data:", data);
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times me-1",
          message: "Dữ liệu đề thi không hợp lệ!",
        });
      }
    },
    error: function (xhr) {
      console.error("Error fetching test info:", xhr.status, xhr.responseText);
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Lỗi khi lấy thông tin đề thi!",
      });
    },
  });
}

function getQuestionOfTest() {
  console.log("Fetching questions for made:", made);
  return $.ajax({
    type: "post",
    url: "./test/getQuestionOfTestManual",
    data: { made },
    dataType: "json",
    success: function (response) {
      console.log("Response from getQuestionOfTestManual:", response);
      arrQuestion = response || [];
      console.log("arrQuestion updated:", arrQuestion);
    },
    error: function (xhr) {
      console.error(
        "Error fetching test questions:",
        xhr.status,
        xhr.responseText
      );
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Lỗi khi lấy câu hỏi của đề thi: " + xhr.responseText,
      });
    },
  });
}
// Hàm sanitize HTML để tránh XSS
function sanitizeHTML(str) {
  const div = document.createElement("div");
  div.textContent = str;
  return div.innerHTML;
}

// Đợi AJAX hoàn tất
$.when(getInfoTest(), getQuestionOfTest()).done(function () {
  if (!infoTest) return;

  $("#name-test").text(infoTest.tende);
  $("#test-time").text(infoTest.thoigianthi);
  const slgioihan = [
    0,
    parseInt(infoTest.socaude, 10),
    parseInt(infoTest.socautb, 10),
    parseInt(infoTest.socaukho, 10),
  ];

  let arr_slch = countQuantityLevel(arrQuestion);

  showListQuestionOfTest(arrQuestion);
  displayQuantityQuestion();
  toggleSaveButton();

  function countQuantityLevel(arrQuestion) {
    const result = [0, 0, 0, 0];
    arrQuestion.forEach((question) => {
      result[question.dokho]++;
    });
    return result;
  }

  function displayQuantityQuestion() {
    $("#slcaude").text(arr_slch[1]);
    $("#ttcaude").text(infoTest.socaude);
    $("#slcautb").text(arr_slch[2]);
    $("#ttcautb").text(infoTest.socautb);
    $("#slcaukho").text(arr_slch[3]);
    $("#ttcaukho").text(infoTest.socaukho);
    toggleSaveButton();
  }

  function toggleSaveButton() {
    const isValid =
      arr_slch[1] === slgioihan[1] &&
      arr_slch[2] === slgioihan[2] &&
      arr_slch[3] === slgioihan[3];
    console.log(
      "ToggleSaveButton - arr_slch:",
      arr_slch,
      "slgioihan:",
      slgioihan,
      "isValid:",
      isValid
    );
    $("#save-test").prop("disabled", !isValid);
  }

  function showListQuestionOfTest(questions) {
    let html = "";
    if (questions.length === 0) {
      html = `<p class="text-center">Chưa có câu hỏi</p>`;
    } else {
      questions.forEach((question, index) => {
        html += `<div class="question mb-3 d-flex justify-content-between">
                    <div class="question-top px-3">
                        <p class="question-content fw-bold mb-3">${
                          index + 1
                        }. ${sanitizeHTML(question.noidung)}</p>
                        <div class="row">`;
        question.cautraloi.forEach((item, i) => {
          const isCorrect = item.ladapan == 1 ? "text-success fw-bold" : "";
          html += `<div class="col-12 mb-1">
                        <p class="mb-1 ${isCorrect}"><b>${String.fromCharCode(
            i + 65
          )}.</b> ${sanitizeHTML(item.noidungtl)}</p>
                    </div>`;
        });
        html += `</div></div>
                    <div class="btn-group-vertical h-100" role="group">
                        <button type="button" class="btn btn-info btn-up" data-bs-toggle="tooltip" data-bs-placement="left" title="Đưa lên trên" data-index="${index}"><i class="fa fa-arrow-up"></i></button>
                        <button type="button" class="btn btn-info btn-down" data-bs-toggle="tooltip" data-bs-placement="left" title="Đưa xuống dưới" data-index="${index}"><i class="fa fa-arrow-down"></i></button>
                        <button type="button" class="btn btn-info btn-delete" data-bs-toggle="tooltip" data-bs-placement="left" title="Xoá câu hỏi" data-index="${index}"><i class="fa fa-delete-left"></i></button>
                    </div>
                </div>`;
      });
    }
    $("#list-question-of-test").html(html);
    $('[data-bs-toggle="tooltip"]').tooltip();
  }

  $(document).on("click", ".item-question", function () {
    const id = +$(this).data("id");
    const question = currentQuestionLists.find((q) => q.macauhoi == id);
    if (!question) return;

    if ($(this).prop("checked")) {
      if (arr_slch[question.dokho] < slgioihan[question.dokho]) {
        arrQuestion.push(question);
        arr_slch[question.dokho]++;
        displayQuantityQuestion();
        showListQuestionOfTest(arrQuestion);
      } else {
        $(this).prop("checked", false);
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times me-1",
          message: `Số lượng câu hỏi ${
            ["", "Dễ", "TB", "Khó"][question.dokho]
          } đã đủ!`,
        });
      }
    } else {
      const i = arrQuestion.findIndex((q) => q.macauhoi == id);
      arrQuestion.splice(i, 1);
      arr_slch[question.dokho]--;
      displayQuantityQuestion();
      showListQuestionOfTest(arrQuestion);
    }
  });

  $(document).on("click", ".btn-up", function () {
    const index = +$(this).data("index");
    if (index === 0) {
      $(this).tooltip("hide");
      return;
    }
    [arrQuestion[index], arrQuestion[index - 1]] = [
      arrQuestion[index - 1],
      arrQuestion[index],
    ];
    $(this).tooltip("hide");
    showListQuestionOfTest(arrQuestion);
  });

  $(document).on("click", ".btn-down", function () {
    const index = +$(this).data("index");
    if (index === arrQuestion.length - 1) {
      $(this).tooltip("hide");
      return;
    }
    [arrQuestion[index], arrQuestion[index + 1]] = [
      arrQuestion[index + 1],
      arrQuestion[index],
    ];
    $(this).tooltip("hide");
    showListQuestionOfTest(arrQuestion);
  });

  $(document).on("click", ".btn-delete", function () {
    const index = +$(this).data("index");
    const question = arrQuestion[index];
    arr_slch[question.dokho]--;
    $(`#q-${question.macauhoi}`).prop("checked", false);
    arrQuestion.splice(index, 1);
    $(this).tooltip("hide");
    displayQuantityQuestion();
    showListQuestionOfTest(arrQuestion);
  });

  $("#save-test").click(function (e) {
    e.preventDefault();
    console.log("Lưu đề thi với made:", infoTest.made);
    console.log("Câu hỏi để lưu:", arrQuestion);
    const questionsToSave = arrQuestion.map((q, index) => ({
      macauhoi: String(q.macauhoi),
      thutu: index + 1,
    }));
    console.log("Dữ liệu gửi:", {
      made: infoTest.made,
      cauhoi: questionsToSave,
    });

    if (!infoTest.made || isNaN(infoTest.made)) {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Mã đề không hợp lệ!",
      });
      return;
    }
    if (questionsToSave.length === 0) {
      Dashmix.helpers("jq-notify", {
        type: "danger",
        icon: "fa fa-times me-1",
        message: "Vui lòng chọn ít nhất một câu hỏi!",
      });
      return;
    }

    $.ajax({
      type: "post",
      url: "./test/addDetail",
      data: {
        made: infoTest.made,
        cauhoi: questionsToSave,
        action: "create", // Thêm tham số để chỉ rõ là tạo mới
      },
      dataType: "json",
      success: function (response) {
        console.log("Phản hồi từ addDetail:", response);
        if (response && response.success) {
          Dashmix.helpers("jq-notify", {
            type: "success",
            icon: "fa fa-check me-1",
            message: "Thêm câu hỏi thành công!",
          });
          setTimeout(() => {
            // Chuyển hướng đến trang danh sách đề thi
            location.href = "./test"; // Hoặc "./dashboard" tùy theo yêu cầu
          }, 2000);
        } else {
          Dashmix.helpers("jq-notify", {
            type: "danger",
            icon: "fa fa-times me-1",
            message: response?.error || "Lưu câu hỏi thất bại!",
          });
        }
      },
      error: function (xhr) {
        console.error("Lỗi khi lưu câu hỏi:", xhr.status, xhr.responseText);
        Dashmix.helpers("jq-notify", {
          type: "danger",
          icon: "fa fa-times me-1",
          message: "Lỗi khi lưu câu hỏi: " + xhr.responseText,
        });
      },
    });
  });
  function loadDataChapter(mamon) {
    return $.ajax({
      type: "post",
      url: "./subject/getAllChapter",
      data: { mamonhoc: mamon },
      dataType: "json",
      success: function (response) {
        showChapter(response);
      },
      error: function (xhr) {
        console.error("Error loading chapters:", xhr.status, xhr.responseText);
      },
    });
  }

  loadDataChapter(infoTest.monthi);

  function showChapter(data) {
    let html = `<a class="dropdown-item active data-chapter" href="javascript:void(0)" data-id="0">Tất cả</a>`;
    data.forEach((item) => {
      html += `<a class="dropdown-item data-chapter" href="javascript:void(0)" data-id="${
        item.machuong
      }">${sanitizeHTML(item.tenchuong)}</a>`;
    });
    $("#list-chapter").html(html);
  }

  $(document).on("click", ".data-chapter", function () {
    $(".data-chapter.active").removeClass("active");
    $(this).addClass("active");
    const machuong = +$(this).data("id");
    if (machuong === 0) {
      delete mainPagePagination.option.filter.machuong;
    } else {
      mainPagePagination.option.filter.machuong = machuong;
    }
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });

  $(document).on("click", ".data-dokho", function () {
    $(".data-dokho.active").removeClass("active");
    $(this).addClass("active");
    const dokho = +$(this).data("id");
    if (dokho === 0) {
      delete mainPagePagination.option.filter.dokho;
    } else {
      mainPagePagination.option.filter.dokho = dokho;
    }
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });
});

const mainPagePagination = new Pagination(null, null, getAnswerListForQuestion);
mainPagePagination.option.controller = "test";
mainPagePagination.option.model = "DeThiModel";
mainPagePagination.option.limit = 10;
mainPagePagination.option.filter = {};
mainPagePagination.option.custom.function = "getQuestionsForTest";

const waitInfoTest = setInterval(function () {
  if (infoTest) {
    mainPagePagination.option.id = infoTest.nguoitao;
    mainPagePagination.option.mamonhoc = infoTest.monthi;
    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
    clearInterval(waitInfoTest);
  } else if (Date.now() - startTime > 10000) {
    // Timeout sau 10 giây
    clearInterval(waitInfoTest);
    Dashmix.helpers("jq-notify", {
      type: "danger",
      icon: "fa fa-times me-1",
      message: "Không thể tải thông tin đề thi!",
    });
  }
}, 200);
const startTime = Date.now();

// Xóa hàm getAnswer vì không cần thiết
// function getAnswer(macauhoi) { ... }
