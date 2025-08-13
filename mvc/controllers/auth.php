<?php

class Auth extends Controller
{
    public $userModel;
    public $mailAuth;

    public function __construct()
    {
        $this->userModel = $this->model("NguoiDungModel");
        $this->mailAuth = $this->model("MailAuth");
        parent::__construct();
    }

    public function default()
    {
        header("Location: ./auth/signin");
    }

    public function signin()
    {
        AuthCore::onLogin();

        $this->view("single_layout", [
            "Page" => "auth/signin",
            "Title" => "Đăng nhập",
            "Script" => "signin",
            "Plugin" => [
                "jquery-validate" => 1,
                "notify" => 1
            ]
        ]);
    }

    public function signup()
    {
        // Không gọi AuthCore::onLogin() để cho phép người dùng chưa đăng nhập truy cập trang đăng ký
        $this->view("single_layout", [
            "Page" => "auth/signup",
            "Title" => "Đăng ký tài khoản",
            "Script" => "signup",
            "Plugin" => [
                "jquery-validate" => 1,
                "notify" => 1,
                "sweetalert2" => 1
            ]
        ]);
    }

    public function recover()
    {
        AuthCore::onLogin();
        $this->view("single_layout", [
            "Page" => "auth/recover",
            "Title" => "Khôi phục tài khoản",
            "Script" => "recover",
            "Plugin" => [
                "jquery-validate" => 1,
                "notify" => 1
            ]
        ]);
    }

    public function otp()
    {
        AuthCore::onLogin();
        if (isset($_SESSION['checkMail'])) {
            $this->view("single_layout", [
                "Page" => "auth/otp",
                "Title" => "Nhập mã OTP",
                "Script" => "recover",
                "Plugin" => [
                    "jquery-validate" => 1,
                    "notify" => 1
                ]
            ]);
        } else {
            header("Location: ./recover");
        }
    }

    public function changepass()
    {
        AuthCore::onLogin();
        if (isset($_SESSION['checkMail'])) {
            $this->view("single_layout", [
                "Page" => "auth/changepass",
                "Title" => "Nhập mật khẩu mới",
                "Script" => "recover",
                "Plugin" => [
                    "jquery-validate" => 1,
                    "notify" => 1
                ]
            ]);
        } else {
            header("Location: ./recover");
        }
    }

    public function addUser()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? '';
            $id = $_POST['id'] ?? ''; // Mã sinh viên
            $hoten = $_POST['hoten'] ?? '';
            $gioitinh = $_POST['gioitinh'] ?? null;
            $ngaysinh = $_POST['ngaysinh'] ?? '1990-01-01';
            $sodienthoai = $_POST['sodienthoai'] ?? null;
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Kiểm tra dữ liệu đầu vào
            // if (empty($email) || empty($id) || empty($hoten) || empty($password) || empty($confirm_password)) {
            //     echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin']);
            //     return;
            // }

            // Kiểm tra định dạng email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ']);
                return;
            }

            // Kiểm tra mật khẩu khớp
            if ($password !== $confirm_password) {
                echo json_encode(['status' => 'error', 'message' => 'Mật khẩu xác nhận không khớp']);
                return;
            }

            // Kiểm tra độ dài mật khẩu
            if (strlen($password) < 6) {
                echo json_encode(['status' => 'error', 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
                return;
            }

            // Kiểm tra email hoặc mã sinh viên đã tồn tại
            if ($this->userModel->getByEmail($email)) {
                echo json_encode(['status' => 'error', 'message' => 'Email đã được sử dụng']);
                return;
            }
            if ($this->userModel->getById($id)) {
                echo json_encode(['status' => 'error', 'message' => 'Mã sinh viên đã được sử dụng']);
                return;
            }

            // Mã hóa mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Thêm người dùng
            $result = $this->userModel->create($email, $id, $hoten, $gioitinh, $ngaysinh, $sodienthoai, $hashed_password);
            echo json_encode([
                'status' => $result ? 'success' : 'error',
                'message' => $result ? 'Đăng ký thành công' : 'Đăng ký thất bại'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
        }
    }

    public function getUser()
    {
        if (isset($_POST['email'])) {
            $user = $this->userModel->getByEmail($_POST['email']);
            echo json_encode($user);
        }
    }

    public function checkLogin()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $masinhvien = $_POST['masinhvien'] ?? '';
            $password = $_POST['password'] ?? '';
            $result = $this->userModel->checkLogin($masinhvien, $password);

            echo json_encode($result);
        }
    }

    public function checkEmail()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? '';
            $check = $this->userModel->getByEmail($email);
            echo json_encode($check ? true : false);
        }
    }

    public function checkOpt()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $otp = $_POST['otp'] ?? '';
            $email = $_SESSION['checkMail'] ?? '';

            if (empty($email)) {
                echo json_encode(false);
                return;
            }

            $check = $this->userModel->checkOtp($email, $otp);
            echo json_encode($check);
            return;
        }
    }


    public function changePassword()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password = $_POST['password'] ?? '';
            $email = $_SESSION['checkMail'] ?? '';

            if (empty($email)) {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy người dùng']);
                return;
            }

            $newPasswordHash = password_hash($password, PASSWORD_DEFAULT);
            $check = $this->userModel->changePassword($email, $newPasswordHash);
            $resetOTP = $this->userModel->updateOpt($email, null);

            if ($check) {
                session_destroy();
                echo json_encode(['status' => 'success', 'message' => 'Đổi mật khẩu thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Đổi mật khẩu thất bại']);
            }
        }
    }

    public function sendOptAuth()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $email = mysqli_real_escape_string($this->userModel->con, $_POST['reminder-credential'] ?? '');

            if (empty($email)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Vui lòng nhập email.'
                ]);
                return;
            }

            if (!$this->userModel->checkEmailExist($email)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email không tồn tại trong hệ thống.'
                ]);
                return;
            }

            $otp = rand(111111, 999999);

            $resultOTP = $this->userModel->updateOpt($email, $otp);

            if (!$resultOTP) {
                unset($_SESSION['checkMail']);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Lỗi khi lưu OTP. Vui lòng thử lại.'
                ]);
                return;
            }

            $sendOTP = $this->mailAuth->sendOpt($email, $otp);

            if ($sendOTP) {
                $_SESSION['checkMail'] = $email;

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Gửi OTP thành công!!'
                ]);
            } else {
                unset($_SESSION['checkMail']);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Lỗi khi gửi email OTP. Vui lòng thử lại.'
                ]);
            }

        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Yêu cầu không hợp lệ.'
            ]);
        }
    }

    public function resendOtpAuth()
    {
        if (!isset($_SESSION['checkMail']) || empty($_SESSION['checkMail'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Không tìm thấy email để gửi lại OTP.'
            ]);
            return;
        }

        $email = $_SESSION['checkMail'];
        $otp = rand(111111, 999999);

        $resultOTP = $this->userModel->updateOpt($email, $otp);

        if (!$resultOTP) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật OTP. Vui lòng thử lại.'
            ]);
            return;
        }

        $sendOTP = $this->mailAuth->sendOpt($email, $otp);

        if ($sendOTP) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Gửi lại OTP thành công!!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi khi gửi lại email OTP!!'
            ]);
        }
    }

    public function logout()
    {
        AuthCore::checkAuthentication();
        $email = $_SESSION['user_email'] ?? '';
        $result = $this->userModel->updateToken($email, null);
        if ($result) {
            session_destroy();
            setcookie("token", "", time() - 10, '/');
            header("Location: ../auth/signin");
        }
    }
}
