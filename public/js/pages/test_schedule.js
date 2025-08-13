function showData(data) {
  console.log("Dữ liệu nhận được:", JSON.stringify(data, null, 2));
  const $list = $(".list-test");

  if (data.length === 0) {
    $list.html(`<tr><td colspan="8" class="text-center py-3 text-muted">
                  <i class="fa fa-info-circle me-1"></i> Không có dữ liệu
                </td></tr>`);
    $(".pagination").hide();
    return;
  }

  const format = new Intl.DateTimeFormat(navigator.language, {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
  });

  let html = "";

  const uniqueTests = [
    ...new Map(data.map((test) => [test.made, test])).values(),
  ];

  uniqueTests.forEach((test) => {
    const open = new Date(test.thoigianbatdau);
    const close = new Date(test.thoigianketthuc);

    // Trạng thái
    let state = { color: "secondary", text: "Chưa mở" };
    const now = Date.now();
    const daThi = test.dathi == 1;

    if (daThi) {
      state = { color: "success", text: "Đã hoàn thành" };
    } else if (now >= +open && now <= +close) {
      state = { color: "primary", text: "Chưa làm" };
    } else if (now > +close) {
      state = { color: "danger", text: "Quá hạn" };
    }

    // Nhóm
    const tennhomArray =
      test.tennhom?.split(", ").filter((n) => n.trim()) || [];
    const tennhom =
      tennhomArray.length > 0 ? tennhomArray.join(", ") : "Chưa gán nhóm";

    html += `
      <tr>
        <td class="fw-semibold">
          <i class="fa fa-file-alt text-muted me-1"></i>
          ${test.tende || "<i>Chưa có tên đề</i>"}
        </td>
        <td>${test.tenmonhoc || "<i>Chưa rõ môn</i>"}</td>
        <td><i class="fa fa-calendar-alt me-1 text-muted"></i>${format.format(
          open
        )}</td>
        <td><i class="fa fa-calendar-check me-1 text-muted"></i>${format.format(
          close
        )}</td>
        <td>
          <i class="fa fa-users text-muted me-1"></i>
          ${tennhom}
        </td>
<td>${
      test.xemdiemthi == 0
        ? "Không được xem"
        : test.diemthi != null
        ? test.diemthi
        : "-"
    }</td>
        <td>
          <span class="badge bg-${state.color} rounded-pill px-3 py-2">
            <i class="fa fa-circle small me-1"></i>${state.text}
          </span>
        </td>
        <td>
          <a href="./test/start/${
            test.made
          }" class="btn btn-sm btn-alt-info rounded-pill px-3">
            <i class="fa fa-arrow-right me-1"></i> Xem chi tiết
          </a>
        </td>
      </tr>
    `;
  });

  $list.html(
    html ||
      `<tr><td colspan="8" class="text-center py-3 text-muted">
       <i class="fa fa-exclamation-circle me-1"></i> Không có dữ liệu hợp lệ
     </td></tr>`
  );

  $(".pagination").toggle(html !== "");
}

// Get current user ID
const container = document.querySelector(".content");
const currentUser = container.dataset.id;
delete container.dataset.id;

$(document).ready(function () {
  $(".filtered-by-state").click(function (e) {
    e.preventDefault();
    $(".btn-filtered-by-state").text($(this).text());
    const state = $(this).data("value");
    if (state !== "4") {
      mainPagePagination.option.filter = state;
    } else {
      delete mainPagePagination.option.filter;
    }

    mainPagePagination.getPagination(
      mainPagePagination.option,
      mainPagePagination.valuePage.curPage
    );
  });
});

// Pagination
const mainPagePagination = new Pagination();
mainPagePagination.option.controller = "client";
mainPagePagination.option.model = "DeThiModel";
mainPagePagination.option.manguoidung = currentUser;
mainPagePagination.option.custom.function = "getUserTestSchedule";
mainPagePagination.getPagination(
  mainPagePagination.option,
  mainPagePagination.valuePage.curPage
);
