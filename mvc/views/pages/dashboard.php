<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hệ thống tạo và quản lý bài thi</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      padding: 20px;
    }

    .content {
      max-width: 960px;
      margin: 0 auto;
    }

    .fixed-slider-img {
      width: 100%;
      height: 400px;
      object-fit: cover;
      border-radius: 10px;
    }

    .slick-prev:before, .slick-next:before {
     color: white !important;
      font-size: 30px;
    }

    .slick-dots li button:before {
      color: #999;
    }

    .slick-dots li.slick-active button:before {
      color: #333;
    }

    
  </style>
</head>
<body>

  <div class="content py-4">
    <h2 id="slider-title" style="text-align:center; margin-bottom:20px;">Hệ thống tạo và quản lý bài thi cá nhân hóa</h2>
    <div class="js-slider">
      <div>
        <img class="img-fluid fixed-slider-img" src="https://png.pngtree.com/png-clipart/20240412/original/pngtree-online-test-with-laptop-and-stopwatch-vector-illustration-png-image_14794593.png" alt="Top phần mềm thi online">
      </div>
      <div>
        <img class="img-fluid fixed-slider-img" src="https://file.unica.vn/storage/fc2ee9cfd8f54fd092257c83fa8d328ec9fbcefa/cong-cu-tao-de-thi-online-1.jpeg" alt="Tạo đề thi online">
      </div>
      <div>
        <img class="img-fluid fixed-slider-img" src="https://phubinh.vn/uploads/news/2022_10/phan-mem-thi-trac-nghiem-online.jpg" alt="Công cụ tạo bài thi">
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

  <script>
    $(document).ready(function () {
      const titles = [
        "Khám phá các phần mềm thi trắc nghiệm online hiệu quả",
        "Tạo đề thi cá nhân hóa nhanh chóng và dễ dàng",
        "Quản lý bài thi chuyên nghiệp với công cụ hiện đại"
      ];

      $('.js-slider').slick({
        dots: true,
        arrows: true,
        autoplay: true,
        autoplaySpeed: 2000,
        infinite: true,
        fade: false,
        prevArrow: '<button type="button" class="slick-prev">‹</button>',
        nextArrow: '<button type="button" class="slick-next">›</button>'
      });

      function updateTitle(index) {
        $('#slider-title').text(titles[index] || "Hệ thống tạo và quản lý bài thi cá nhân hóa");
      }

      updateTitle(0);

      $('.js-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        updateTitle(nextSlide);
      });
    });
  </script>

  <div class="modal fade" id="modal-onboarding" tabindex="-1" role="dialog" aria-labelledby="modal-onboarding"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content rounded overflow-hidden bg-image bg-image-bottom border-0"
        style="background-image: url('public/media/photos/photo23.jpg');">
        <div class="row">
          <div class="col-md-5">
            <div class="p-3 text-end text-md-start">
              <a class="fw-semibold text-white" href="#" data-bs-dismiss="modal" aria-label="Close">
                Chuyển tiếp
              </a>
            </div>
          </div>
          <div class="col-md-7 mx-auto">
            <div class="bg-body-extra-light shadow-lg rounded-4 p-4">
              <div class="slick-dotted-inner text-center" data-dots="true" data-arrows="false" data-infinite="false">
                <div class="p-5">
                  <i class="fa fa-user-check fa-3x text-primary my-4"></i>
                  <h3 class="fs-2 fw-bold mb-2">Chào mừng bạn đến với <span class="text-success">DHT OnTest</span></h3>
                  <p class="fs-5 text-muted mb-4">Vui lòng cập nhật địa chỉ email của bạn để tiếp tục</p>

                  <form class="mb-4">
                    <div class="mb-3">
                      <input type="email" class="form-control form-control-lg form-control-alt text-center" id="email"
                        placeholder="Nhập địa chỉ email của bạn" required>
                    </div>
                  </form>

                  <button type="button" class="btn btn-primary btn-lg px-4" id="btn-email">
                    Cập nhật <i class="fa fa-check opacity-75 ms-2"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>