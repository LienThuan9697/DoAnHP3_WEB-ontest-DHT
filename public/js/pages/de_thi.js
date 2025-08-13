$(document).ready(function () {
  let questions = [];
  const made = $("#dethicontent").data("id");
  const dethi = "dethi" + made;
  const cautraloi = "cautraloi" + made;

  function getQuestion() {
    return $.ajax({
      type: "post",
      url: "./test/getQuestion",
      data: { made: made },
      dataType: "json",
      success: function (response) {
        console.log("getQuestion response:", response);
        if (response && Array.isArray(response) && response.length > 0) {
          questions = response;
        } else {
          console.error("No valid questions received:", response);
          $("#list-question").html(
            "<p class='text-center text-danger'>Không tải được câu hỏi! Vui lòng kiểm tra mã đề hoặc liên hệ quản trị viên.</p>"
          );
        }
      },
      error: function (xhr) {
        console.error(
          "Error fetching questions:",
          xhr.status,
          xhr.responseText
        );
        $("#list-question").html(
          "<p class='text-center text-danger'>Lỗi khi tải câu hỏi: " +
            (xhr.responseText || "Kết nối thất bại") +
            "</p>"
        );
      },
    });
  }

  function showListQuestion(questions, answers) {
    let html = "";
    if (!questions || !Array.isArray(questions) || questions.length === 0) {
      $("#list-question").html(
        "<p class='text-center text-danger'>Không có câu hỏi</p>"
      );
      console.error("No questions available:", questions);
      return;
    }
    if (
      !answers ||
      !Array.isArray(answers) ||
      answers.length !== questions.length
    ) {
      console.error("Invalid answers data:", answers);
      return;
    }
    questions.forEach((question, index) => {
      console.log(`Rendering question ${index + 1}:`, question);
      html += `<div class="question rounded border mb-3 bg-white" id="c${
        index + 1
      }">
                <div class="question-top p-3">
                    <p class="question-content fw-bold mb-3">${index + 1}. ${
        question.noidung
      }</p>
                    <div class="row">`;
      question.cautraloi.forEach((ctl, i) => {
        console.log(`Rendering answer ${String.fromCharCode(i + 65)}:`, ctl);
        html += `<div class="col-6 mb-1">
                    <span class="mb-1"><b>${String.fromCharCode(i + 65)}.</b> ${
          ctl.noidungtl
        }</span>
                </div>`;
      });
      html += `</div></div><div class="test-ans bg-primary rounded-bottom py-2 px-3 d-flex align-items-center"><p class="mb-0 text-white me-4">Đáp án của bạn:</p><div>`;
      question.cautraloi.forEach((ctl, i) => {
        let check = answers[index].cautraloi == ctl.macautl ? "checked" : "";
        html += `<input type="radio" class="btn-check" name="options-c${
          index + 1
        }" id="ctl-${ctl.macautl}" autocomplete="off" data-index="${
          index + 1
        }" data-macautl="${ctl.macautl}" ${check}>
                        <label class="btn btn-light rounded-pill me-2 btn-answer" for="ctl-${
                          ctl.macautl
                        }">${String.fromCharCode(i + 65)}</label>`;
      });
      html += `</div></div></div>`;
    });
    $("#list-question").html(html);
    console.log("HTML generated for list-question:", html);
  }

  function initListAnswer(questions) {
    let listAns = questions.map((item) => {
      let itemAns = {};
      itemAns.macauhoi = item.macauhoi;
      itemAns.cautraloi = 0;
      return itemAns;
    });
    return listAns;
  }

  function changeAnswer(index, dapan) {
    let listAns = JSON.parse(localStorage.getItem(cautraloi));
    listAns[index].cautraloi = dapan;
    localStorage.setItem(cautraloi, JSON.stringify(listAns));
  }

  $.when(getQuestion()).done(function () {
    if (localStorage.getItem(dethi) == null) {
      localStorage.setItem(dethi, JSON.stringify(questions));
    }
    if (localStorage.getItem(cautraloi) == null) {
      localStorage.setItem(
        cautraloi,
        JSON.stringify(initListAnswer(questions))
      );
    }

    let listQues = JSON.parse(localStorage.getItem(dethi));
    let listAns = JSON.parse(localStorage.getItem(cautraloi));
    showListQuestion(listQues, listAns);
    showBtnSideBar(listQues, listAns);
  });

  function showBtnSideBar(questions, answers) {
    let html = ``;
    questions.forEach((q, i) => {
      let isActive = answers[i].cautraloi == 0 ? "" : " active";
      html += `<li class="answer-item p-1"><a href="javascript:void(0)" class="answer-item-link btn btn-outline-primary w-100 btn-sm${isActive}" data-index="${
        i + 1
      }">${i + 1}</a></li>`;
    });
    $(".answer").html(html);
  }

  $(document).on("click", ".btn-check", function () {
    let ques = $(this).data("index");
    $(`[data-index='${ques}']`).addClass("active");
    changeAnswer(ques - 1, $(this).data("macautl"));
  });

  $(document).on("click", ".answer-item-link", function () {
    let ques = $(this).data("index");
    document.getElementById(`c${ques}`).scrollIntoView();
  });

  $("#btn-nop-bai").click(function (e) {
    e.preventDefault();

    let listAns = JSON.parse(localStorage.getItem(cautraloi));
    let unanswered = listAns.filter((ans) => ans.cautraloi === 0);

    if (unanswered.length > 0) {
      Swal.fire({
        icon: "warning",
        title: "Chưa hoàn thành!",
        html: `<p class="fs-6 text-center mb-0">Bạn chưa chọn đáp án cho <strong>${unanswered.length}</strong> câu hỏi.<br>Vui lòng hoàn thành tất cả trước khi nộp bài.</p>`,
        confirmButtonText: "OK",
      });
      return;
    }

    Swal.fire({
      title: "<center><p class='fs-3 mb-0'>Bạn có chắc chắn muốn nộp bài?</p>",
      html: "<p class='text-muted fs-6 text-center mb-0'>Khi xác nhận nộp bài, bạn sẽ không thể sửa lại bài thi của mình!</p>",
      icon: "info",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Vâng, chắc chắn!",
      cancelButtonText: "Huỷ",
    }).then((result) => {
      if (result.isConfirmed) {
        nopbai();
      }
    });
  });

  function nopbai() {
    let dethiCheck = $("#dethicontent").data("id");
    let thoigian = new Date();
    $.ajax({
      type: "post",
      url: "./test/submit",
      data: {
        listCauTraLoi: JSON.parse(localStorage.getItem(cautraloi)),
        thoigianlambai: thoigian,
        made: dethiCheck,
      },
      success: function (response) {
        localStorage.removeItem(cautraloi);
        localStorage.removeItem(dethi);
        location.href = `./test/start/${made}`;
      },
      error: function (response) {
        localStorage.removeItem(cautraloi);
        localStorage.removeItem(dethi);
        location.href = `./test/start/${made}`;
      },
    });
  }

  $("#btn-thoat").click(function (e) {
    e.preventDefault();
    Swal.fire({
      title: "Bạn có chắc muốn thoát?",
      html: "<p class='text-muted fs-6 text-center mb-0'>Khi xác nhận thoát, bạn sẽ không được tiếp tục làm bài ở lần thi này. Kết quả bài làm vẫn sẽ được nộp</p>",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Vâng, chắc chắn!",
      cancelButtonText: "Huỷ",
    }).then((result) => {
      if (result.isConfirmed) {
        nopbai();
        location.href = "./dashboard";
      }
    });
  });

  var endTime = -1;
  getTimeTest();

  function getTimeTest() {
    let dethi = $("#dethicontent").data("id");
    $.ajax({
      type: "post",
      url: "./test/getTimeTest",
      data: {
        dethi: dethi,
      },
      success: function (response) {
        endTime = new Date(response).getTime();
        let curTime = new Date().getTime();
        if (curTime > endTime) {
          localStorage.removeItem(cautraloi);
          localStorage.removeItem(dethi);
          location.href = `./test/start/${made}`;
        } else {
          $.ajax({
            type: "post",
            url: "./test/getTimeEndTest",
            data: {
              dethi: dethi,
            },
            success: function (responseEnd) {
              let endTimeTest = new Date(responseEnd).getTime();
              if (endTimeTest < endTime) {
                endTime = endTimeTest;
              }
            },
          });
          countDown();
        }
      },
    });
  }

  function countDown() {
    var x = setInterval(function () {
      var now = new Date().getTime();
      var distance = endTime - now;
      var hours = Math.floor(
        (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
      );
      if (hours < 10) hours = "0" + hours;
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      if (minutes < 10) minutes = "0" + minutes;
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      if (seconds < 10) seconds = "0" + seconds;
      $("#timer").html(hours + ":" + minutes + ":" + seconds);

      if (distance <= 30000) {
        $("#timer").css("color", "red").css("font-weight", "bold");
      }

      if (distance <= 1000 && distance >= 0) {
        nopbai();
        clearInterval(x);
      }
    }, 1000);
  }

  // Logic xử lý chuyển tab
  //nộp bài ngay khi chuyển tab nếu bài thi đó có rán giá trị nộp bài chuyển tab(nopbaichuyentab) là 1
  $(window).on("blur", function () {
    //lắng nghe sự kiện khi người dùng chuyển tab
    $.ajax({
      type: "post",
      url: "./test/chuyentab", //gọi đến hàm này trong controller test.php .← Gửi yêu cầu đến server kiểm tra số lần chuyển tab
      data: {
        made: $("#dethicontent").data("id"),
      },
      success: function (response) {
        if (response == 1) {
          nopbai();
        } else {
          localStorage.setItem("isTabSwitched_" + made, "1");
        }
      },
      error: function () {
        localStorage.setItem("isTabSwitched_" + made, "1");
      },
    });
  });
  // hàm phát hiện chuyển tab trong khi thi
  $(window).on("focus", function () {
    if (localStorage.getItem("isTabSwitched_" + made) === "1") {
      // lắng nghe sự kiện khi người dùng chuyển tab quay lại trang
      let curTime = new Date().getTime();
      if (curTime < endTime) {
        Swal.fire({
          icon: "warning",
          title: "Bạn đã rời khỏi trang thi",
          html: "<p class='fs-6 text-center mb-0'>Hệ thống phát hiện bạn đã chuyển tab trước đó. Bạn vẫn được thi tiếp vì còn thời gian làm bài.</p>",
          confirmButtonText: "Tiếp tục",
        });
        localStorage.removeItem("isTabSwitched_" + made);
      } else {
        nopbai();
      }
    }
  });
});
