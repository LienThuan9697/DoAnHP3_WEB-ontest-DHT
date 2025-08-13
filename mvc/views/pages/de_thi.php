<style>
        #list-question {
            display: block !important;
            visibility: visible !important;
            height: auto !important;
            background-color: #f8f9fa !important;
            padding: 20px;
            border-radius: 8px;
        }
        .question {
            display: block !important;
            opacity: 1 !important;
            margin-bottom: 15px;
        }
        .test-ans {
            display: flex !important;
            gap: 10px;
        }
        .nav {
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .nav-center {
            font-size: 1.1rem;
        }
        .nav-time {
            font-size: 1rem;
            color: #333;
        }
        .btn-hero {
            transition: all 0.3s ease;
        }
        .btn-hero:hover {
            transform: translateY(-1px);
        }
        .sidebar-answer {
            position: sticky;
            top: 80px;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
        }
        .mt-6 {
            margin-top: 5rem !important;
        }
    </style>
</head>
<body>
    <nav class="nav border-bottom bg-white position-fixed top-0 w-100">
        <div class="container d-flex justify-content-between align-items-center py-3 position-relative">
            <!-- Left side: MSSV -->
            <div class="nav-left" style="width: 33%;">
                <div class="fs-6 fw-bold">
                    MSSV: <span style="color: darkcyan;"><?php echo $_SESSION['user_id'] ?></span>
                </div>
            </div>

            <!-- Center: Full Name -->
            <div class="nav-center position-absolute start-50 translate-middle-x text-center">
                <div class="fw-bold fs-5">
                    Họ và Tên: <span style="color: darkred;"><?php echo $_SESSION['user_name'] ?></span>
                </div>
            </div>

            <!-- Right side: Timer and Submit Button -->
            <div class="nav-right d-flex align-items-center justify-content-end" style="width: 33%;">
                <div class="nav-time me-4">
                    <span class="fw-bold"><i class="far fa-clock mx-2"></i><span id="timer">00:00:00</span></span>
                </div>
                <button id="btn-nop-bai" class="btn btn-hero btn-primary" role="button">
                    <i class="far fa-file-lines me-1"></i> Nộp bài
                </button>
            </div>
        </div>
    </nav>

    <div class="container mb-5 mt-6" id="dethicontent" data-id="<?php echo $data['Made']?>">
        <div class="row">
            <div class="col-8" id="list-question">
                <!-- Question content goes here -->
            </div>
            <div class="col-4 bg-white p-3 rounded border h-100 sidebar-answer">
                <ul class="answer">
                    <!-- Answer list goes here -->
                </ul>
            </div>
        </div>
    </div>