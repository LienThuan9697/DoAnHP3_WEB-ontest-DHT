<?php

class Statistic extends Controller
{
    public $thongke;

    public function __construct()
    {
        $this->thongke = $this->model("ThongKeModel");
        parent::__construct();
    }

    public function default()
    {
        AuthCore::checkAuthentication();
        if (!AuthCore::checkPermission("thongke", "view")) {
            $this->view("single_layout", [
                "Page" => "error/page_404",
                "Title" => "Lỗi: Không có quyền truy cập"
            ]);
            return;
        }

        $user_id = $_SESSION['user_id'];
        $made = isset($_GET['made']) ? $_GET['made'] : null;
        $mahocky = isset($_GET['mahocky']) ? $_GET['mahocky'] : null;
        $namhoc = isset($_GET['namhoc']) ? $_GET['namhoc'] : null;

        if (empty($made) || !is_numeric($made)) {
            $semesters = $this->thongke->getSemesters($user_id);
            $academic_years = $this->thongke->getAcademicYears($user_id);
            $subjects = [];
            $groups = [];
            if ($mahocky && $namhoc) {
                $subjects = $this->thongke->getSubjectsByCreator($user_id, $mahocky, $namhoc);
                $groups = $this->thongke->getGroupsByCreator($user_id, $mahocky, $namhoc);
            }

            $this->view("main_layout", [
                "Page" => "statistic",
                "Title" => "Thống kê tổng hợp",
                "Semesters" => $semesters,
                "AcademicYears" => $academic_years,
                "Subjects" => $subjects,
                "Groups" => $groups,
                "ShowAggregate" => true,
                "Plugin" => ["sweetalert2" => 1, "chartjs" => 1, "notify" => 1],
                "Script" => "statistic",
                "user_id" => $user_id
            ]);
            return;
        }

        $test = $this->thongke->getTestInfo($made, $user_id);
        if (!$test) {
            $this->view("single_layout", [
                "Page" => "error/page_404",
                "Title" => "Lỗi: Đề thi không tồn tại hoặc bạn không có quyền truy cập"
            ]);
            return;
        }

        $nhom = $this->thongke->getNhomByTest($made);

        $this->view("main_layout", [
            "Page" => "statistic",
            "Title" => "Thống kê điểm thi - " . htmlspecialchars($test['tende']),
            "Test" => $test,
            "Nhom" => $nhom,
            "ShowAggregate" => false,
            "Plugin" => ["sweetalert2" => 1, "chartjs" => 1, "notify" => 1],
            "Script" => "statistic",
            "user_id" => $user_id
        ]);
    }

    public function getStatictical()
    {
        if (!AuthCore::checkPermission("thongke", "view")) {
            echo json_encode(['error' => 'Không có quyền truy cập']);
            return;
        }

        $user_id = $_SESSION['user_id'];
        $made = $_POST['made'] ?? null;
        $manhom = isset($_POST['manhom']) && !empty($_POST['manhom']) ? $_POST['manhom'] : 0;

        if (empty($made) || !is_numeric($made)) {
            echo json_encode(['error' => 'Mã đề thi không hợp lệ']);
            return;
        }

        $test = $this->thongke->getTestInfo($made, $user_id);
        if (!$test) {
            echo json_encode(['error' => 'Đề thi không tồn tại hoặc bạn không có quyền truy cập']);
            return;
        }

        $data = $this->thongke->getStatisticalData($made, $manhom);
        echo json_encode($data);
    }

    public function getAggregatedStatistical()
    {
        if (!AuthCore::checkPermission("thongke", "view")) {
            echo json_encode(['error' => 'Không có quyền truy cập']);
            return;
        }

        $user_id = $_SESSION['user_id'];
        $mahocky = $_POST['mahocky'] ?? null;
        $namhoc = $_POST['namhoc'] ?? null;
        $mamonhoc = isset($_POST['mamonhoc']) && !empty($_POST['mamonhoc']) ? $_POST['mamonhoc'] : null;
        $manhom = isset($_POST['manhom']) && !empty($_POST['manhom']) ? $_POST['manhom'] : null;

        if (empty($mahocky) || empty($namhoc)) {
            echo json_encode(['error' => 'Vui lòng chọn học kỳ và năm học']);
            return;
        }

        $data = $this->thongke->getAggregatedStatisticalData($user_id, $mahocky, $namhoc, $mamonhoc, $manhom);
        echo json_encode($data);
    }

    public function getFilters()
    {
        if (!AuthCore::checkPermission("thongke", "view")) {
            echo json_encode(['error' => 'Không có quyền truy cập']);
            return;
        }

        $user_id = $_SESSION['user_id'] ?? null;
        $mahocky = $_POST['mahocky'] ?? null;
        $namhoc = $_POST['namhoc'] ?? null;

        if (!$user_id || !$mahocky || !$namhoc) {
            error_log("getFilters: Missing required parameters - user_id: $user_id, mahocky: $mahocky, namhoc: $namhoc");
            echo json_encode(['error' => 'Vui lòng chọn học kỳ và năm học']);
            return;
        }

        try {
            $subjects = $this->thongke->getSubjectsByCreator($user_id, $mahocky, $namhoc);
            $groups = $this->thongke->getGroupsByCreator($user_id, $mahocky, $namhoc);

            echo json_encode([
                'subjects' => $subjects,
                'groups' => $groups
            ]);
        } catch (Exception $e) {
            error_log("getFilters Error: " . $e->getMessage());
            echo json_encode(['error' => 'Lỗi server, vui lòng thử lại sau']);
        }
    }
    public function getGroupsBySubject()
    {
        if (!AuthCore::checkPermission("thongke", "view")) {
            echo json_encode(['error' => 'Không có quyền truy cập']);
            return;
        }

        $user_id = $_SESSION['user_id'] ?? null;
        $mahocky = $_POST['mahocky'] ?? null;
        $namhoc = $_POST['namhoc'] ?? null;
        $mamonhoc = $_POST['mamonhoc'] ?? null;

        if (!$user_id || !$mahocky || !$namhoc || !$mamonhoc) {
            echo json_encode(['error' => 'Thiếu thông tin để lọc nhóm học phần']);
            return;
        }

        try {
            $groups = $this->thongke->getGroupsByCreator($user_id, $mahocky, $namhoc, $mamonhoc);
            echo json_encode($groups);
        } catch (Exception $e) {
            error_log("getGroupsBySubject Error: " . $e->getMessage());
            echo json_encode(['error' => 'Lỗi server khi lấy danh sách nhóm học phần']);
        }
    }


}
