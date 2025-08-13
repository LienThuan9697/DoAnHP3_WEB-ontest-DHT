<?php

class PaginationModel extends DB
{
    public function getTotalPages($queryData, $limit, $args = [])
    {
        if (is_string($queryData)) {
            $query = $queryData;
            $params = [];
        } else {
            if (!isset($queryData['query']) || empty($queryData['query'])) {
                error_log("PaginationModel::getTotalPages: Missing or empty query");
                return 0;
            }
            $query = $queryData['query'];
            $params = $queryData['params'] ?? [];

            if (isset($queryData['count_query'])) {
                $query = $queryData['count_query'];
            } else {
                // Tạo subquery để xử lý GROUP BY
                $query = "SELECT COUNT(*) AS total FROM ($query) AS subquery";
            }
        }

        $stmt = mysqli_prepare($this->con, $query);
        if ($stmt === false) {
            error_log("PaginationModel::getTotalPages: Prepare failed: " . mysqli_error($this->con) . " | Query: $query");
            return 0;
        }

        if (!empty($params)) {
            $types = $params[0];
            $param_values = array_slice($params, 1);
            mysqli_stmt_bind_param($stmt, $types, ...$param_values);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        $total = isset($row['total']) ? (int)$row['total'] : 0;
        return $limit > 0 ? ceil($total / $limit) : 1;
    }

    public function pagination($queryData, $limit, $offset)
    {
        if (is_string($queryData)) {
            $query = $queryData;
            $params = [];
        } else {
            if (!isset($queryData['query']) || empty($queryData['query'])) {
                error_log("PaginationModel::pagination: Missing or empty query");
                return [];
            }
            $query = $queryData['query'];
            $params = $queryData['params'] ?? [];
        }

        $query .= " LIMIT ?, ?";
        $stmt = mysqli_prepare($this->con, $query);
        if ($stmt === false) {
            error_log("PaginationModel::pagination: Prepare failed: " . mysqli_error($this->con) . " | Query: $query");
            return [];
        }

        if (!empty($params)) {
            $types = $params[0] . 'ii';
            $param_values = array_slice($params, 1);
            $param_values[] = $offset;
            $param_values[] = $limit;
            mysqli_stmt_bind_param($stmt, $types, ...$param_values);
        } else {
            mysqli_stmt_bind_param($stmt, 'ii', $offset, $limit);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);

        return $rows;
    }
}
