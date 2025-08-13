<?php

class CauHoiModel extends DB
{
    public function create($noidung, $dokho, $mamonhoc, $machuong, $nguoitao)
    {
        $sql = "INSERT INTO `cauhoi` (`noidung`, `dokho`, `mamonhoc`, `machuong`, `nguoitao`) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->con, $sql);
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . mysqli_error($this->con));
        }

        mysqli_stmt_bind_param($stmt, "sisss", $noidung, $dokho, $mamonhoc, $machuong, $nguoitao);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $insert_id = mysqli_insert_id($this->con); // ✅ lấy ID câu hỏi vừa thêm
            mysqli_stmt_close($stmt);
            return $insert_id; // ✅ trả về ID thay vì true/false
        } else {
            mysqli_stmt_close($stmt);
            return false; // thất bại
        }
    }


    public function update($macauhoi, $noidung, $dokho, $mamonhoc, $machuong, $nguoitao)
    {
        $sql = "UPDATE `cauhoi` SET `noidung`=?, `dokho`=?, `mamonhoc`=?, `machuong`=?, `nguoitao`=? WHERE `macauhoi`=?";
        $stmt = mysqli_prepare($this->con, $sql);
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . mysqli_error($this->con));
        }
        mysqli_stmt_bind_param($stmt, "sisssi", $noidung, $dokho, $mamonhoc, $machuong, $nguoitao, $macauhoi);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function delete($macauhoi)
    {
        $sql = "UPDATE `cauhoi` SET `trangthai`='0' WHERE `macauhoi`=?";
        $stmt = mysqli_prepare($this->con, $sql);
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . mysqli_error($this->con));
        }
        mysqli_stmt_bind_param($stmt, "i", $macauhoi);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM cauhoi JOIN monhoc ON cauhoi.mamonhoc = monhoc.mamonhoc ORDER BY cauhoi.macauhoi ASC LIMIT 5";
        $result = mysqli_query($this->con, $sql);
        if ($result === false) {
            die("Lỗi truy vấn SQL: " . mysqli_error($this->con));
        }
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getById($macauhoi)
    {
        $sql = "SELECT * FROM `cauhoi` WHERE `macauhoi`=?";
        $stmt = mysqli_prepare($this->con, $sql);
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . mysqli_error($this->con));
        }
        mysqli_stmt_bind_param($stmt, "i", $macauhoi);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $row;
    }

    public function getAllBySubject($mamonhoc)
    {
        $sql = "SELECT * FROM `cauhoi` WHERE `mamonhoc`=? ORDER BY id ASC";
        $stmt = mysqli_prepare($this->con, $sql);
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . mysqli_error($this->con));
        }
        mysqli_stmt_bind_param($stmt, "s", $mamonhoc);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $rows;
    }

    public function getTotalPage($content, $selected)
    {
        $sql = "SELECT COUNT(*) as total FROM cauhoi WHERE noidung LIKE ?";
        $stmt = mysqli_prepare($this->con, $sql);
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . mysqli_error($this->con));
        }
        $content_param = "%$content%";
        mysqli_stmt_bind_param($stmt, "s", $content_param);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $count = mysqli_fetch_assoc($result)['total'];
        mysqli_stmt_close($stmt);
        $data = $count % 5 == 0 ? $count / 5 : floor($count / 5) + 1;
        return $data;
    }

    public function getQuestionBySubject($mamonhoc, $machuong, $dokho, $content, $page)
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql = "SELECT macauhoi, noidung, dokho, machuong FROM cauhoi WHERE mamonhoc = ? AND trangthai = '1'";
        $params = array('s', $mamonhoc);
        if ($machuong != 0) {
            $sql .= " AND machuong = ?";
            $params[0] .= 's';
            $params[] = $machuong;
        }
        if ($dokho != 0) {
            $sql .= " AND dokho = ?";
            $params[0] .= 'i';
            $params[] = $dokho;
        }
        if ($content != '') {
            $sql .= " AND noidung LIKE ?";
            $params[0] .= 's';
            $params[] = "%$content%";
        }
        $sql .= " ORDER BY id ASC LIMIT ?, ?";
        $params[0] .= 'ii';
        $params[] = $offset;
        $params[] = $limit;

        $stmt = mysqli_prepare($this->con, $sql);
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . mysqli_error($this->con) . " | Truy vấn: $sql");
        }
        mysqli_stmt_bind_param($stmt, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $rows;
    }

    public function getTotalPageQuestionBySubject($mamonhoc, $machuong, $dokho, $content)
    {
        $sql = "SELECT COUNT(*) as total FROM cauhoi WHERE mamonhoc = ? AND trangthai = '1'";
        $params = array('s', $mamonhoc);
        if ($machuong != 0) {
            $sql .= " AND machuong = ?";
            $params[0] .= 's';
            $params[] = $machuong;
        }
        if ($dokho != 0) {
            $sql .= " AND dokho = ?";
            $params[0] .= 'i';
            $params[] = $dokho;
        }
        if ($content != '') {
            $sql .= " AND noidung LIKE ?";
            $params[0] .= 's';
            $params[] = "%$content%";
        }

        $stmt = mysqli_prepare($this->con, $sql);
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . mysqli_error($this->con) . " | Truy vấn: $sql");
        }
        mysqli_stmt_bind_param($stmt, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $count = mysqli_fetch_assoc($result)['total'];
        mysqli_stmt_close($stmt);
        $limit = 10;
        return ceil($count / $limit);
    }

    public function getQuery($filter, $input, $args)
    {
        if ($input) {
            return $this->getQueryWithInput($filter, $input, $args);
        }
        $query = "SELECT * FROM cauhoi, monhoc, phancong 
                  WHERE cauhoi.mamonhoc = monhoc.mamonhoc 
                  AND cauhoi.trangthai = 1 
                  AND phancong.manguoidung = ? 
                  AND phancong.mamonhoc = cauhoi.mamonhoc";
        $params = array('s', $args['id']);

        if (isset($filter)) {
            if (isset($filter['mamonhoc'])) {
                $query .= " AND monhoc.mamonhoc = ?";
                $params[0] .= 's';
                $params[] = $filter['mamonhoc'];
            }
            if (isset($filter['machuong'])) {
                $query .= " AND machuong = ?";
                $params[0] .= 's';
                $params[] = $filter['machuong'];
            }
            if (isset($filter['dokho']) && $filter['dokho'] != 0) {
                $query .= " AND dokho = ?";
                $params[0] .= 'i';
                $params[] = $filter['dokho'];
            }
        }

        $query .= " ORDER BY cauhoi.macauhoi ASC"; // Sắp xếp theo id thay vì macauhoi

        return ['query' => $query, 'params' => $params];
    }

    public function getQueryWithInput($filter, $input, $args)
    {
        $query = "SELECT cauhoi.*, monhoc.tenmonhoc 
              FROM cauhoi JOIN monhoc ON cauhoi.mamonhoc = monhoc.mamonhoc 
              WHERE cauhoi.noidung LIKE ? AND cauhoi.trangthai = '1'";
        $params = array('s', "%$input%");

        if (isset($filter)) {
            if (isset($filter['mamonhoc'])) {
                $query .= " AND monhoc.mamonhoc = ?";
                $params[0] .= 's';
                $params[] = $filter['mamonhoc'];
            }
            if (isset($filter['machuong'])) {
                $query .= " AND cauhoi.machuong = ?";
                $params[0] .= 's';
                $params[] = $filter['machuong'];
            }
            if (isset($filter['dokho']) && $filter['dokho'] != 0) {
                $query .= " AND cauhoi.dokho = ?";
                $params[0] .= 'i';
                $params[] = $filter['dokho'];
            }
        }

        $query .= " ORDER BY CAST(cauhoi.macauhoi AS UNSIGNED) ASC";

        return ['query' => $query, 'params' => $params];
    }
    public function getsoluongcauhoi($chuong, $monhoc, $dokho)
    {
        // Log tham số đầu vào
        error_log("getsoluongcauhoi: chuong=" . json_encode($chuong) . ", monhoc=$monhoc, dokho=$dokho");

        // Chuyển chuong thành mảng nếu nó là chuỗi
        if (!is_array($chuong)) {
            $chuong = !empty($chuong) ? [$chuong] : [];
        }

        $sql = "SELECT COUNT(*) as soluong FROM cauhoi WHERE dokho = ? AND mamonhoc = ?";
        $params = ['is', (int)$dokho, $monhoc];

        if (!empty($chuong)) {
            $placeholders = str_repeat('?,', count($chuong) - 1) . '?';
            $sql .= " AND machuong IN ($placeholders)";
            $params[0] .= str_repeat('s', count($chuong));
            $params = array_merge($params, $chuong);
        }

        // Log câu lệnh SQL
        error_log("SQL Query: $sql");
        error_log("Params: " . json_encode($params));

        $stmt = mysqli_prepare($this->con, $sql);
        if ($stmt === false) {
            error_log("Lỗi chuẩn bị truy vấn: " . mysqli_error($this->con));
            return 0;
        }

        if (!mysqli_stmt_bind_param($stmt, ...$params)) {
            error_log("Lỗi bind param: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return 0;
        }

        if (!mysqli_stmt_execute($stmt)) {
            error_log("Lỗi thực thi truy vấn: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return 0;
        }

        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            error_log("Lỗi lấy kết quả: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return 0;
        }

        $row = mysqli_fetch_assoc($result);
        $soluong = $row['soluong'] ?? 0;
        error_log("Số lượng câu hỏi: $soluong");

        mysqli_stmt_close($stmt);
        return (int)$soluong;
    }

}
