<?php

include "./mvc/models/CauTraLoiModel.php";

class DeThiModel extends DB
{
    public function executeQuery($sql)
    {
        return mysqli_query($this->con, $sql);
    }

    public function insertAndGetId($sql)
    {
        mysqli_query($this->con, $sql);
        return mysqli_insert_id($this->con);
    }

    // Hàm kiểm tra số lượng câu hỏi đủ điều kiện
    private function checkQuestionAvailability($monhoc, $chuong, $socaude, $socautb, $socaukho)
    {
        // Bọc $monhoc trong dấu nháy nếu nó là chuỗi
        $monhoc = mysqli_real_escape_string($this->con, $monhoc); // chống SQL injection

        $sql_caude = "SELECT COUNT(*) as count FROM cauhoi ch 
                  JOIN monhoc mh ON ch.mamonhoc = mh.mamonhoc 
                  WHERE ch.mamonhoc = '$monhoc' AND ch.dokho = 1 AND ch.trangthai != 0 AND ";
        $sql_cautb = "SELECT COUNT(*) as count FROM cauhoi ch 
                  JOIN monhoc mh ON ch.mamonhoc = mh.mamonhoc 
                  WHERE ch.mamonhoc = '$monhoc' AND ch.dokho = 2 AND ch.trangthai != 0 AND ";
        $sql_caukho = "SELECT COUNT(*) as count FROM cauhoi ch 
                   JOIN monhoc mh ON ch.mamonhoc = mh.mamonhoc 
                   WHERE ch.mamonhoc = '$monhoc' AND ch.dokho = 3 AND ch.trangthai != 0 AND ";

        // Ghép điều kiện chương
        $countChuong = count($chuong) - 1;
        $detailChuong = "(";
        for ($i = 0; $i < $countChuong; $i++) {
            $chuong_id = mysqli_real_escape_string($this->con, $chuong[$i]);
            $detailChuong .= "ch.machuong='$chuong_id' OR ";
        }
        $chuong_id = mysqli_real_escape_string($this->con, $chuong[$countChuong]);
        $detailChuong .= "ch.machuong='$chuong_id')";

        // Hoàn tất câu truy vấn
        $sql_caude .= $detailChuong;
        $sql_cautb .= $detailChuong;
        $sql_caukho .= $detailChuong;

        // Thực thi và kiểm tra lỗi
        $result_cd = mysqli_query($this->con, $sql_caude);
        $result_tb = mysqli_query($this->con, $sql_cautb);
        $result_ck = mysqli_query($this->con, $sql_caukho);

        if (!$result_cd || !$result_tb || !$result_ck) {
            die("Lỗi truy vấn SQL: " . mysqli_error($this->con));
        }

        $count_de = mysqli_fetch_assoc($result_cd)['count'];
        $count_tb = mysqli_fetch_assoc($result_tb)['count'];
        $count_kho = mysqli_fetch_assoc($result_ck)['count'];

        if ($count_de < $socaude || $count_tb < $socautb || $count_kho < $socaukho) {
            return [
                'valid' => false,
                'message' => "Không đủ câu hỏi trong cơ sở dữ liệu. Cần tối thiểu: $socaude câu dễ, $socautb câu trung bình, $socaukho câu khó. Hiện có: $count_de câu dễ, $count_tb câu trung bình, $count_kho câu khó."
            ];
        }

        return ['valid' => true];
    }


    public function create($monthi, $nguoitao, $tende, $thoigianthi, $thoigianbatdau, $thoigianketthuc, $hienthibailam, $xemdiemthi, $xemdapan, $troncauhoi, $trondapan, $nopbaichuyentab, $loaide, $socaude, $socautb, $socaukho, $chuong, $nhom)
    {
        if ($loaide == 0) {
        $troncauhoi = 0; 
    }
        if ($loaide == 1) {
            $check = $this->checkQuestionAvailability($monthi, $chuong, $socaude, $socautb, $socaukho);
            if (!$check['valid']) {
                return $check;
            }
        }

        $sql = "INSERT INTO `dethi`(`monthi`, `nguoitao`, `tende`, `thoigianthi`, `thoigianbatdau`, `thoigianketthuc`, `hienthibailam`, `xemdiemthi`, `xemdapan`, `troncauhoi`, `trondapan`, `nopbaichuyentab`, `loaide`, `socaude`, `socautb`, `socaukho`) VALUES ('$monthi','$nguoitao','$tende','$thoigianthi','$thoigianbatdau','$thoigianketthuc','$hienthibailam','$xemdiemthi','$xemdapan','$troncauhoi','$trondapan','$nopbaichuyentab','$loaide','$socaude','$socautb','$socaukho')";
        $result = mysqli_query($this->con, $sql);
        if ($result) {
            $madethi = mysqli_insert_id($this->con);
            $result = $this->create_giaodethi($madethi, $nhom);
            $result = $this->create_chuongdethi($madethi, $chuong);
            return $madethi;
        } else {
            return false;
        }
    }

public function create_dethi_auto($made, $monhoc, $chuong, $socaude, $socautb, $socaukho)
{
    $check = $this->checkQuestionAvailability($monhoc, $chuong, $socaude, $socautb, $socaukho);
    if (!$check['valid']) {
        return $check;
    }

    // Lấy giá trị troncauhoi từ bảng dethi
    $sql_dethi = "SELECT troncauhoi FROM dethi WHERE made = '$made'";
    $data_dethi = mysqli_fetch_assoc(mysqli_query($this->con, $sql_dethi));
    $troncauhoi = $data_dethi['troncauhoi'];

    // Sử dụng ORDER BY cố định nếu troncauhoi = 0
    $orderBy = $troncauhoi == 1 ? "ORDER BY RAND()" : "ORDER BY ch.macauhoi ASC";

    $sql_caude = "SELECT * FROM cauhoi ch JOIN monhoc mh ON ch.mamonhoc = mh.mamonhoc WHERE ch.mamonhoc = '$monhoc' AND ch.dokho = 1 AND ch.trangthai != 0 AND ";
    $sql_cautb = "SELECT * FROM cauhoi ch JOIN monhoc mh ON ch.mamonhoc = mh.mamonhoc WHERE ch.mamonhoc = '$monhoc' AND ch.dokho = 2 AND ch.trangthai != 0 AND ";
    $sql_caukho = "SELECT * FROM cauhoi ch JOIN monhoc mh ON ch.mamonhoc = mh.mamonhoc WHERE ch.mamonhoc = '$monhoc' AND ch.dokho = 3 AND ch.trangthai != 0 AND ";

    $countChuong = count($chuong) - 1;
    $detailChuong = "(";
    for ($i = 0; $i < $countChuong; $i++) {
        $detailChuong .= "ch.machuong='$chuong[$i]' OR ";
    }
    $detailChuong .= "ch.machuong='$chuong[$countChuong]')";

    $sql_caude .= $detailChuong . " $orderBy LIMIT $socaude";
    $sql_cautb .= $detailChuong . " $orderBy LIMIT $socautb";
    $sql_caukho .= $detailChuong . " $orderBy LIMIT $socaukho";

    $result_cd = mysqli_query($this->con, $sql_caude);
    $result_tb = mysqli_query($this->con, $sql_cautb);
    $result_ck = mysqli_query($this->con, $sql_caukho);

    $data_cd = [];
    while ($row = mysqli_fetch_assoc($result_cd)) {
        $data_cd[] = $row;
    }
    while ($row = mysqli_fetch_assoc($result_tb)) {
        $data_cd[] = $row;
    }
    while ($row = mysqli_fetch_assoc($result_ck)) {
        $data_cd[] = $row;
    }

    // Chỉ xáo trộn nếu troncauhoi = 1
    if ($troncauhoi == 1) {
        shuffle($data_cd);
    }

    return [
        'valid' => true,
        'data' => $data_cd
    ];
}

    public function create_chuongdethi($made, $chuong)
    {
        $valid = true;
        foreach ($chuong as $machuong) {
            $sql = "INSERT INTO `dethitudong`(`made`, `machuong`) VALUES ('$made','$machuong')";
            $result = mysqli_query($this->con, $sql);
            if (!$result) {
                $valid = false;
            }
        }
        return $valid;
    }

    public function update_chuongdethi($made, $chuong)
    {
        $valid = true;
        $sql = "DELETE FROM `dethitudong` WHERE `made`='$made'";
        $result_del = mysqli_query($this->con, $sql);
        if ($result_del) {
            $result_update = $this->create_chuongdethi($made, $chuong);
        } else {
            $valid = false;
        }
        return $valid;
    }

    public function create_giaodethi($made, $nhom)
    {
        $valid = true;
        $nhom = array_unique($nhom);
        foreach ($nhom as $manhom) {
            $sql_check = "SELECT manhom FROM nhom WHERE manhom = '" . mysqli_real_escape_string($this->con, $manhom) . "' AND trangthai != 0";
            $result_check = mysqli_query($this->con, $sql_check);
            if (mysqli_num_rows($result_check) == 0) {
                error_log("Nhóm không hợp lệ: $manhom");
                continue;
            }
            $sql = "INSERT INTO `giaodethi`(`made`, `manhom`) VALUES ('$made', '" . mysqli_real_escape_string($this->con, $manhom) . "')";
            $result = mysqli_query($this->con, $sql);
            if (!$result) {
                $valid = false;
            }
        }
        return $valid;
    }

    public function update_giaodethi($made, $nhom)
    {
        $valid = true;
        $sql = "DELETE FROM `giaodethi` WHERE `made`='$made'";
        $result_del = mysqli_query($this->con, $sql);
        if ($result_del) {
            $result_update = $this->create_giaodethi($made, $nhom);
        } else {
            $valid = false;
        }
        return $valid;
    }

    public function update($made, $monthi, $tende, $thoigianthi, $thoigianbatdau, $thoigianketthuc, $hienthibailam, $xemdiemthi, $xemdapan, $troncauhoi, $trondapan, $nopbaichuyentab, $loaide, $socaude, $socautb, $socaukho, $chuong, $nhom)
    {
        // Kiểm tra số lượng câu hỏi nếu là đề tự động
        if ($loaide == 1) {
            $check = $this->checkQuestionAvailability($monthi, $chuong, $socaude, $socautb, $socaukho);
            if (!$check['valid']) {
                return $check;
            }
        }

        $valid = true;
        $sql = "UPDATE `dethi` SET `monthi`='$monthi',`tende`='$tende',`thoigianthi`='$thoigianthi',`thoigianbatdau`='$thoigianbatdau',`thoigianketthuc`='$thoigianketthuc',`hienthibailam`='$hienthibailam',`xemdiemthi`='$xemdiemthi',`xemdapan`='$xemdapan',`troncauhoi`='$troncauhoi',`trondapan`='$trondapan',`nopbaichuyentab`='$nopbaichuyentab',`loaide`='$loaide',`socaude`='$socaude',`socautb`='$socautb',`socaukho`='$socaukho' WHERE `made`='$made'";
        $result = mysqli_query($this->con, $sql);
        if ($result) {
            $result = $this->update_giaodethi($made, $nhom);
            $result = $this->update_chuongdethi($made, $chuong);
        } else {
            $valid = false;
        }
        return $valid;
    }
    public function delete($made)
    {
        mysqli_begin_transaction($this->con);
        try {
            // Kiểm tra bảng ketqua trước
            $sql_check_ketqua = "SELECT COUNT(*) as count FROM `ketqua` WHERE `made` = ?";
            $stmt_check_ketqua = mysqli_prepare($this->con, $sql_check_ketqua);
            if (!$stmt_check_ketqua) {
                throw new Exception("Lỗi chuẩn bị truy vấn kiểm tra kết quả thi: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt_check_ketqua, "s", $made);
            mysqli_stmt_execute($stmt_check_ketqua);
            $result_check_ketqua = mysqli_stmt_get_result($stmt_check_ketqua);
            $row_ketqua = mysqli_fetch_assoc($result_check_ketqua);
            mysqli_stmt_close($stmt_check_ketqua);
            if ($row_ketqua['count'] > 0) {
                throw new Exception("Không thể xóa đề thi vì đã có {$row_ketqua['count']} thí sinh hoàn thành bài thi.");
            }

            // Lấy danh sách manhom từ giaodethi
            $sql_get_manhom = "SELECT DISTINCT manhom FROM `giaodethi` WHERE `made` = ?";
            $stmt_get_manhom = mysqli_prepare($this->con, $sql_get_manhom);
            if (!$stmt_get_manhom) {
                throw new Exception("Lỗi chuẩn bị truy vấn lấy mã nhóm: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt_get_manhom, "s", $made);
            mysqli_stmt_execute($stmt_get_manhom);
            $result_manhom = mysqli_stmt_get_result($stmt_get_manhom);
            $manhom_list = [];
            while ($row = mysqli_fetch_assoc($result_manhom)) {
                $manhom_list[] = $row['manhom'];
            }
            mysqli_stmt_close($stmt_get_manhom);

            // Xóa bản ghi trong trangthaithongbao dựa trên matb từ chitietthongbao
            if (!empty($manhom_list)) {
                $placeholders = implode(',', array_fill(0, count($manhom_list), '?'));
                $sql_delete_trangthaithongbao = "DELETE FROM `trangthaithongbao` WHERE `matb` IN (SELECT `matb` FROM `chitietthongbao` WHERE `manhom` IN ($placeholders))";
                $stmt_delete_trangthaithongbao = mysqli_prepare($this->con, $sql_delete_trangthaithongbao);
                if (!$stmt_delete_trangthaithongbao) {
                    throw new Exception("Lỗi chuẩn bị truy vấn xóa trạng thái thông báo: " . mysqli_error($this->con));
                }
                mysqli_stmt_bind_param($stmt_delete_trangthaithongbao, str_repeat('s', count($manhom_list)), ...$manhom_list);
                mysqli_stmt_execute($stmt_delete_trangthaithongbao);
                mysqli_stmt_close($stmt_delete_trangthaithongbao);

                // Xóa bản ghi trong chitietthongbao
                $sql_delete_chitietthongbao = "DELETE FROM `chitietthongbao` WHERE `manhom` IN ($placeholders)";
                $stmt_delete_chitietthongbao = mysqli_prepare($this->con, $sql_delete_chitietthongbao);
                if (!$stmt_delete_chitietthongbao) {
                    throw new Exception("Lỗi chuẩn bị truy vấn xóa chi tiết thông báo: " . mysqli_error($this->con));
                }
                mysqli_stmt_bind_param($stmt_delete_chitietthongbao, str_repeat('s', count($manhom_list)), ...$manhom_list);
                mysqli_stmt_execute($stmt_delete_chitietthongbao);
                mysqli_stmt_close($stmt_delete_chitietthongbao);

                // Xóa bản ghi trong thongbao (chỉ xóa những thông báo không còn liên kết với nhóm nào)
                $sql_delete_thongbao = "DELETE FROM `thongbao` WHERE `matb` NOT IN (SELECT `matb` FROM `chitietthongbao`)";
                $stmt_delete_thongbao = mysqli_query($this->con, $sql_delete_thongbao);
                if (!$stmt_delete_thongbao) {
                    throw new Exception("Lỗi xóa thông báo: " . mysqli_error($this->con));
                }
            }

            // Xóa bản ghi trong chitietdethi
            $sql_delete_chitietdethi = "DELETE FROM `chitietdethi` WHERE `made` = ?";
            $stmt_delete_chitietdethi = mysqli_prepare($this->con, $sql_delete_chitietdethi);
            if (!$stmt_delete_chitietdethi) {
                throw new Exception("Lỗi chuẩn bị truy vấn xóa chi tiết đề thi: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt_delete_chitietdethi, "s", $made);
            mysqli_stmt_execute($stmt_delete_chitietdethi);
            mysqli_stmt_close($stmt_delete_chitietdethi);

            // Xóa bản ghi trong dethitudong
            $sql_delete_dethitudong = "DELETE FROM `dethitudong` WHERE `made` = ?";
            $stmt_delete_dethitudong = mysqli_prepare($this->con, $sql_delete_dethitudong);
            if (!$stmt_delete_dethitudong) {
                throw new Exception("Lỗi chuẩn bị truy vấn xóa đề thi tự động: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt_delete_dethitudong, "s", $made);
            mysqli_stmt_execute($stmt_delete_dethitudong);
            mysqli_stmt_close($stmt_delete_dethitudong);

            // Xóa bản ghi trong giaodethi
            $sql_delete_giaodethi = "DELETE FROM `giaodethi` WHERE `made` = ?";
            $stmt_delete_giaodethi = mysqli_prepare($this->con, $sql_delete_giaodethi);
            if (!$stmt_delete_giaodethi) {
                throw new Exception("Lỗi chuẩn bị truy vấn xóa giao đề thi: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt_delete_giaodethi, "s", $made);
            mysqli_stmt_execute($stmt_delete_giaodethi);
            mysqli_stmt_close($stmt_delete_giaodethi);

            // Xóa đề thi
            $sql = "DELETE FROM `dethi` WHERE `made` = ?";
            $stmt = mysqli_prepare($this->con, $sql);
            if (!$stmt) {
                throw new Exception("Lỗi chuẩn bị truy vấn xóa đề thi: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt, "s", $made);
            $success = mysqli_stmt_execute($stmt);
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);

            if ($success && $affected_rows > 0) {
                mysqli_commit($this->con);
                return [
                    'success' => true,
                    'message' => 'Xóa đề thi thành công!'
                ];
            } else {
                throw new Exception("Không tìm thấy đề thi hoặc không thể xóa.");
            }
        } catch (Exception $e) {
            mysqli_rollback($this->con);
            error_log("Lỗi xóa đề thi: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    public function getAll($nguoitao)
    {
        $sql = "SELECT dethi.made, tende, monhoc.tenmonhoc, thoigianbatdau, thoigianketthuc, nhom.tennhom, namhoc, hocky
        FROM dethi, monhoc, giaodethi, nhom
        WHERE dethi.monthi = monhoc.mamonhoc AND dethi.made = giaodethi.made AND nhom.manhom = giaodethi.manhom AND nguoitao = $nguoitao AND dethi.trangthai = 1
        ORDER BY dethi.made DESC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $made = $row['made'];
            $index = array_search($made, array_column($rows, 'made'));
            if ($index === false) {
                $item = [
                    "made" => $made,
                    "tende" => $row['tende'],
                    "thoigianbatdau" => date_format(date_create($row['thoigianbatdau']), "H:i d/m/Y"),
                    "thoigianketthuc" => date_format(date_create($row['thoigianketthuc']), "H:i d/m/Y"),
                    "tenmonhoc" => $row['tenmonhoc'],
                    "namhoc" => $row['namhoc'],
                    "hocky" => $row['hocky'],
                    "nhom" => [$row['tennhom']]
                ];
                array_push($rows, $item);
            } else {
                array_push($rows[$index]["nhom"], $row['tennhom']);
            }
        }
        return $rows;
    }

    public function getById($made)
    {
        $sql_dethi = "SELECT dethi.*, monhoc.tenmonhoc FROM dethi, monhoc WHERE made = $made AND dethi.monthi = monhoc.mamonhoc";
        $result_dethi = mysqli_query($this->con, $sql_dethi);
        $dethi = mysqli_fetch_assoc($result_dethi);
        if ($dethi != null) {
            $sql_giaodethi = "SELECT manhom FROM giaodethi WHERE made = $made";
            $sql_dethitudong = "SELECT machuong FROM dethitudong WHERE made = $made";
            $result_giaodethi = mysqli_query($this->con, $sql_giaodethi);
            $result_dethitudong = mysqli_query($this->con, $sql_dethitudong);
            $dethi['chuong'] = array();
            while ($row = mysqli_fetch_assoc($result_dethitudong)) {
                $dethi['chuong'][] = $row['machuong'];
            }
            $dethi['nhom'] = array();
            while ($row = mysqli_fetch_assoc($result_giaodethi)) {
                $dethi['nhom'][] = $row['manhom'];
            }
        }
        return $dethi;
    }

    public function getInfoTestBasic($made)
    {
        $sql_dethi = "SELECT dethi.made, dethi.tende, dethi.thoigiantao,dethi.loaide,dethi.nguoitao,monhoc.mamonhoc, monhoc.tenmonhoc FROM dethi, monhoc WHERE made = $made AND dethi.monthi = monhoc.mamonhoc";
        $result_dethi = mysqli_query($this->con, $sql_dethi);
        $dethi = mysqli_fetch_assoc($result_dethi);
        if ($dethi != null) {
            $sql_giaodethi = "SELECT giaodethi.manhom, nhom.tennhom FROM giaodethi, nhom WHERE made = $made AND giaodethi.manhom = nhom.manhom";
            $result_giaodethi = mysqli_query($this->con, $sql_giaodethi);
            $dethi['nhom'] = array();
            while ($row = mysqli_fetch_assoc($result_giaodethi)) {
                $dethi['nhom'][] = $row;
            }
        }
        return $dethi;
    }

    public function getListTestGroup($manhom)
    {
        $sql = "SELECT dethi.made, dethi.tende, dethi.thoigianbatdau, dethi.thoigianketthuc
        FROM giaodethi, dethi
        WHERE manhom = '$manhom' AND giaodethi.made = dethi.made ORDER BY dethi.made DESC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $row['thoigianbatdau'] = date_format(date_create($row['thoigianbatdau']), "H:i d/m/Y");
            $row['thoigianketthuc'] = date_format(date_create($row['thoigianketthuc']), "H:i d/m/Y");
            $rows[] = $row;
        }
        return $rows;
    }

    public function getQuestionOfTest($made)
    {
        $sql_dethi = "select * from dethi where made = '$made'";
        $data_dethi = mysqli_fetch_assoc(mysqli_query($this->con, $sql_dethi));
        $question = array();
        if ($data_dethi['loaide'] == 0) {
            $question = $this->getQuestionOfTestManual($made);
        } else {
            $question = $this->getQuestionTestAuto($made);
        }
        $makq = $this->getMaDe($made, $_SESSION['user_id']);
        foreach ($question as $data) {
            $macauhoi = $data['macauhoi'];
            $sql = "INSERT INTO `chitietketqua`(`makq`, `macauhoi`) VALUES ('$makq','$macauhoi')";
            $addCtKq = mysqli_query($this->con, $sql);
        }

        return $question;
    }

public function getQuestionByUser($made, $user)
{
    $sql_ketqua = "SELECT * FROM ketqua where made = '$made' and manguoidung = '$user'";
    $result_ketqua = mysqli_query($this->con, $sql_ketqua);
    $data_ketqua = mysqli_fetch_assoc($result_ketqua);
    $ketqua = $data_ketqua['makq'];
    $sql_question = "SELECT * FROM chitietketqua ctkq JOIN cauhoi ch on ctkq.macauhoi = ch.macauhoi WHERE makq = '$ketqua'";
    $data_question = mysqli_query($this->con, $sql_question);
    $ctlmodel = new CauTraLoiModel();
    $sql_dethi = "SELECT * FROM dethi where made='$made'";
    $result_dethi = mysqli_query($this->con, $sql_dethi);
    $data_dethi = mysqli_fetch_assoc($result_dethi);
    $trondapan = $data_dethi['trondapan'];
    $troncauhoi = $data_dethi['troncauhoi'];
    $loaide = $data_dethi['loaide'];
    $rows = array();
    foreach ($data_question as $row) {
        if ($trondapan == 1) {
            $arrDapAn = $ctlmodel->getAllWithoutAnswer($row['macauhoi']);
            shuffle($arrDapAn);
            $row['cautraloi'] = $arrDapAn;
        } else {
            $row['cautraloi'] = $ctlmodel->getAllWithoutAnswer($row['macauhoi']);
        }
        $rows[] = $row;
    }
    // Chỉ xáo trộn nếu là đề tự động và troncauhoi = 1
    if ($loaide == 1 && $troncauhoi == 1) {
        shuffle($rows);
    }
    return $rows;
}

    public function getAllSubjects()
    {
        $sql = "SELECT mamonhoc, tenmonhoc FROM monhoc WHERE trangthai != 0 ORDER BY tenmonhoc ASC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getMaDe($made, $user)
    {
        $sql = "SELECT * FROM `ketqua` WHERE made = '$made' and manguoidung = '$user'";
        $result = mysqli_query($this->con, $sql);
        $data = mysqli_fetch_assoc($result);
        return $data['makq'];
    }

public function getQuestionTestAuto($made)
{
    $sql_dethi = "SELECT * FROM dethi WHERE made = '$made'";
    $data_dethi = mysqli_fetch_assoc(mysqli_query($this->con, $sql_dethi));

    $socaude = $data_dethi['socaude'];
    $socautb = $data_dethi['socautb'];
    $socaukho = $data_dethi['socaukho'];
    $mamonhoc = $data_dethi['monthi'];
    $troncauhoi = $data_dethi['troncauhoi'];

    // Sử dụng ORDER BY cố định nếu troncauhoi = 0
    $orderBy = $troncauhoi == 1 ? "ORDER BY RAND()" : "ORDER BY ch.macauhoi ASC";

    $sql_cd = "SELECT ch.macauhoi, ch.noidung, ch.dokho 
        FROM dethitudong dttd 
        JOIN cauhoi ch ON dttd.machuong = ch.machuong 
        WHERE ch.dokho = 1 
            AND dttd.made = '$made' 
            AND ch.mamonhoc = '$mamonhoc' 
            AND ch.trangthai != 0
        $orderBy 
        LIMIT $socaude";

    $sql_ctb = "SELECT ch.macauhoi, ch.noidung, ch.dokho 
        FROM dethitudong dttd 
        JOIN cauhoi ch ON dttd.machuong = ch.machuong 
        WHERE ch.dokho = 2 
            AND dttd.made = '$made' 
            AND ch.mamonhoc = '$mamonhoc' 
            AND ch.trangthai != 0
        $orderBy 
        LIMIT $socautb";

    $sql_ck = "SELECT ch.macauhoi, ch.noidung, ch.dokho 
        FROM dethitudong dttd 
        JOIN cauhoi ch ON dttd.machuong = ch.machuong 
        WHERE ch.dokho = 3 
            AND dttd.made = '$made' 
            AND ch.mamonhoc = '$mamonhoc' 
            AND ch.trangthai != 0
        $orderBy 
        LIMIT $socaukho";

    $result_cd = mysqli_query($this->con, $sql_cd);
    $result_tb = mysqli_query($this->con, $sql_ctb);
    $result_ck = mysqli_query($this->con, $sql_ck);

    $result = array();
    while ($row = mysqli_fetch_assoc($result_cd)) {
        $result[] = $row;
    }
    while ($row = mysqli_fetch_assoc($result_tb)) {
        $result[] = $row;
    }
    while ($row = mysqli_fetch_assoc($result_ck)) {
        $result[] = $row;
    }

    // Chỉ xáo trộn nếu troncauhoi = 1
    if ($troncauhoi == 1) {
        shuffle($result);
    }

    $rows = array();
    $ctlmodel = new CauTraLoiModel();
    foreach ($result as $row) {
        $row['cautraloi'] = $ctlmodel->getAllWithoutAnswer($row['macauhoi']);
        $rows[] = $row;
    }

    return $rows;
}
    public function getNameGroup($manhom)
    {
        $sql = "SELECT * FROM `nhom` WHERE manhom=$manhom";
        $result = mysqli_query($this->con, $sql);
        $nameGroup = mysqli_fetch_assoc($result)['tennhom'];
        return $nameGroup;
    }

    public function getQuestionOfTestManual($made)
    {
        $sql = "SELECT CTDT.macauhoi, noidung, dokho, thutu FROM chitietdethi CTDT, cauhoi CH WHERE CTDT.macauhoi = CH.macauhoi AND CTDT.made = $made ORDER BY thutu ASC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        $ctlmodel = new CauTraLoiModel();
        while ($row = mysqli_fetch_assoc($result)) {
            $row['cautraloi'] = $ctlmodel->getAllWithoutAnswer($row['macauhoi']);
            $rows[] = $row;
        }
        return $rows;
    }

    public function getResultDetail($makq)
    {
        $sql = "SELECT cauhoi.macauhoi,cauhoi.noidung,cauhoi.dokho,chitietketqua.dapanchon FROM chitietketqua, cauhoi WHERE makq= '$makq' AND chitietketqua.macauhoi = cauhoi.macauhoi";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        $ctlmodel = new CauTraLoiModel();
        while ($row = mysqli_fetch_assoc($result)) {
            $row['cautraloi'] = $ctlmodel->getAll($row['macauhoi']);
            $rows[] = $row;
        }
        return $rows;
    }

    public function getTimeTest($dethi, $nguoidung)
    {
        $sql = "Select * from ketqua where made = '$dethi' and manguoidung = '$nguoidung'";
        $sql_dethi = "select * from dethi where made = '$dethi'";
        $result_dethi = mysqli_query($this->con, $sql_dethi);
        $result = mysqli_query($this->con, $sql);
        if ($result) {
            $data = mysqli_fetch_assoc($result);
            $data_dethi = mysqli_fetch_assoc($result_dethi);
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $thoigianketthuc = date("Y-m-d H:i:s", strtotime($data['thoigianvaothi']) + ($data_dethi['thoigianthi'] * 60));
            return $thoigianketthuc;
        }
        return false;
    }

    public function getTimeEndTest($dethi)
    {
        $sql_dethi = "select * from dethi where made = '$dethi'";
        $result_dethi = mysqli_query($this->con, $sql_dethi);
        $data_dethi = mysqli_fetch_assoc($result_dethi);
        $thoigianketthuc = date("Y-m-d H:i:s", strtotime($data_dethi['thoigianketthuc']));
        return $thoigianketthuc;
    }

    public function getGroupsTakeTests($tests)
    {
        $string = implode(', ', $tests);
        $sql = "SELECT GDT.*, tennhom, namhoc, hocky FROM giaodethi GDT, nhom N WHERE GDT.manhom = N.manhom AND made IN ($string)";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function checkStudentAllowed($manguoidung, $madethi)
    {
        $valid = true;
        $sql = "SELECT *
        FROM giaodethi, chitietnhom
        WHERE giaodethi.made = '$madethi' AND giaodethi.manhom = chitietnhom.manhom AND chitietnhom.manguoidung = '$manguoidung'";
        $result = mysqli_query($this->con, $sql);
        if (!mysqli_fetch_assoc($result)) {
            $valid = false;
        }
        return $valid;
    }

    public function getAllGroups()
    {
        $sql = "SELECT manhom, tennhom FROM nhom WHERE trangthai != 0 ORDER BY tennhom ASC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getQuery($filter, $input, $args)
    {
        $query = "";
        if (isset($args["custom"]["function"])) {
            $func = $args["custom"]["function"];
            switch ($func) {
                case "getUserTestSchedule":
                    $query = "SELECT T1.*, T2.diemthi, T2.dathi, T2.xemdiemthi
              FROM (
                  SELECT DT.made, tende, thoigianbatdau, thoigianketthuc, CTN.manhom,
                         tennhom, tenmonhoc, namhoc, hocky
                  FROM chitietnhom CTN, giaodethi GDT, dethi DT, monhoc MH, nhom N
                  WHERE N.trangthai != 0
                    AND N.manhom = CTN.manhom
                    AND CTN.manhom = GDT.manhom
                    AND DT.made = GDT.made
                    AND MH.mamonhoc = DT.monthi
                    AND DT.trangthai = 1
                    AND manguoidung = '" . $args['manguoidung'] . "'
              ) T1
              LEFT JOIN (
                  SELECT DISTINCT DT.made,
                         CASE WHEN DT.xemdiemthi = 1 THEN KQ.diemthi ELSE NULL END AS diemthi,
                         CASE WHEN KQ.manguoidung IS NOT NULL THEN 1 ELSE 0 END AS dathi,
                         DT.xemdiemthi
                  FROM chitietnhom CTN, giaodethi GDT, dethi DT,
                       monhoc MH, nhom N, ketqua KQ
                  WHERE N.manhom = CTN.manhom
                    AND CTN.manhom = GDT.manhom
                    AND DT.made = GDT.made
                    AND MH.mamonhoc = DT.monthi
                    AND KQ.made = DT.made
                    AND DT.trangthai = 1
                    AND KQ.manguoidung = '" . $args['manguoidung'] . "'
              ) T2
              ON T1.made = T2.made
              WHERE 1";

                    if (isset($filter)) {
                        switch ($filter) {
                            case "0":
                                $query .= " AND CURRENT_TIMESTAMP() BETWEEN thoigianbatdau AND thoigianketthuc AND (T2.dathi IS NULL OR T2.dathi = 0)";
                                break;
                            case "1":
                                $query .= " AND CURRENT_TIMESTAMP() > thoigianketthuc AND (T2.dathi IS NULL OR T2.dathi = 0)";
                                break;
                            case "2":
                                $query .= " AND CURRENT_TIMESTAMP() < thoigianbatdau";
                                break;
                            case "3":
                                $query .= " AND T2.dathi = 1";
                                break;
                        }
                    }

                    if ($input) {
                        $query .= " AND (tende LIKE N'%$input%' OR tenmonhoc LIKE N'%$input%')";
                    }
                    $query .= " ORDER BY made DESC";
                    break;


                case "getAllCreatedTest":
                    $query = "SELECT DT.made, tende, MH.tenmonhoc, thoigianbatdau, thoigianketthuc, GROUP_CONCAT(DISTINCT N.tennhom SEPARATOR ', ') AS nhom, N.namhoc, N.hocky 
              FROM dethi DT
              JOIN giaodethi GDT ON DT.made = GDT.made
              JOIN nhom N ON N.manhom = GDT.manhom
              JOIN monhoc MH ON N.mamonhoc = MH.mamonhoc
              WHERE nguoitao = '" . $args['id'] . "' AND DT.trangthai = 1";
                    if (isset($filter)) {
                        switch ($filter) {
                            case "0":
                                $query .= " AND CURRENT_TIMESTAMP() < thoigianbatdau";
                                break;
                            case "1":
                                $query .= " AND CURRENT_TIMESTAMP() BETWEEN thoigianbatdau AND thoigianketthuc";
                                break;
                            case "2":
                                $query .= " AND CURRENT_TIMESTAMP() > thoigianketthuc";
                                break;
                            default:
                        }
                    }
                    if ($input) {
                        $query .= " AND (tende LIKE N'%$input%' OR tenmonhoc LIKE N'%$input%')";
                    }
                    if (isset($args['subject']) && $args['subject'] !== '') {
                        $subject = mysqli_real_escape_string($this->con, $args['subject']);
                        $query .= " AND DT.monthi = '$subject'";
                    }
                    if (isset($args['group']) && $args['group'] !== '') {
                        $group = mysqli_real_escape_string($this->con, $args['group']);
                        $query .= " AND GDT.manhom = '$group'";
                    }
                    $query .= " GROUP BY DT.made ORDER BY DT.made DESC";
                    break;
                case "getQuestionsForTest":
                    $id = mysqli_real_escape_string($this->con, $args['id']);
                    $mamonhoc = mysqli_real_escape_string($this->con, $args['mamonhoc']);
                    $page = $_GET['page'] ?? 1;
                    $limit = 10;
                    $offset = ($page - 1) * $limit;

                    $query = "SELECT cauhoi.*, cauhoi.noidung AS noidungplaintext FROM cauhoi JOIN phancong ON cauhoi.mamonhoc = phancong.mamonhoc WHERE cauhoi.trangthai = 1 AND phancong.manguoidung = '$id' AND cauhoi.mamonhoc = '$mamonhoc'";

                    if (!empty($filter['machuong'])) {
                        $query .= " AND cauhoi.machuong = " . intval($filter['machuong']);
                    }
                    if (!empty($filter['dokho'])) {
                        $query .= " AND cauhoi.dokho = " . intval($filter['dokho']);
                    }
                    if (!empty($input)) {
                        $input_safe = mysqli_real_escape_string($this->con, $input);
                        $query .= " AND (noidung LIKE '%$input_safe%')";
                    }

                    error_log("Query getQuestionsForTest: $query");
                    break;
            }
        }
        return $query;
    }

    public function getTestsGroupWithUserResult($manhom, $manguoidung)
    {
        $sql = "SELECT T1.*, diemthi FROM (SELECT DT.made, tende, thoigianbatdau, thoigianketthuc FROM dethi DT, giaodethi GDT WHERE DT.made = GDT.made AND DT.trangthai = 1 AND manhom = $manhom) T1 LEFT JOIN (SELECT KQ.made, diemthi FROM ketqua KQ, giaodethi GDT WHERE KQ.made = GDT.made AND manguoidung = '$manguoidung' AND GDT.manhom = $manhom) T2 ON T1.made = T2.made ORDER BY made DESC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
