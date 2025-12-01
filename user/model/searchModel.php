<?php
require_once 'connectDB.php';

class SearchModel {
    private $conn;

    public function __construct() {
        $this->conn = connectdb();
        if ($this->conn === null) {
            throw new Exception("Không thể kết nối cơ sở dữ liệu");
        }
    }

    public function countBooks($keyword = '', $category = null, $min_price = null, $max_price = null) {
        if ($this->conn === null) {
            return 0;
        }

        $category_ids = [];
        if ($category !== null && $category !== 'all') {
            $stmt = $this->conn->prepare("SELECT idchitietdanhmuc FROM chitietdanhmuc WHERE iddanhmuc = :iddanhmuc");
            $stmt->execute([':iddanhmuc' => $category]);
            $category_ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }

        $sql = "SELECT COUNT(*) as total 
                FROM sach s 
                LEFT JOIN chitietdanhmuc c ON s.idctdanhmuc = c.idchitietdanhmuc 
                LEFT JOIN danhmuc d ON c.iddanhmuc = d.iddanhmuc 
                WHERE s.trangthai = 1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (s.tensach LIKE :keyword)";
            $params[':keyword'] = "%$keyword%";
        }
        if (!empty($category_ids)) {
            $placeholders = [];
            foreach ($category_ids as $index => $id) {
                $param_name = ":category_id_$index";
                $placeholders[] = $param_name;
                $params[$param_name] = $id;
            }
            $sql .= " AND s.idctdanhmuc IN (" . implode(',', $placeholders) . ")";
        }
        if ($min_price !== null) {
            $sql .= " AND s.gia >= :min_price";
            $params[':min_price'] = $min_price;
        }
        if ($max_price !== null) {
            $sql .= " AND s.gia <= :max_price";
            $params[':max_price'] = $max_price;
        }

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function searchBooks($keyword = '', $category = null, $min_price = null, $max_price = null, $limit = null, $offset = null) {
        if ($this->conn === null) {
            return [
                'status' => 'error',
                'message' => 'Không thể kết nối cơ sở dữ liệu',
                'data' => [],
                'total_results' => 0
            ];
        }

        $category_ids = [];
        if ($category !== null && $category !== 'all') {
            $stmt = $this->conn->prepare("SELECT idchitietdanhmuc FROM chitietdanhmuc WHERE iddanhmuc = :iddanhmuc");
            $stmt->execute([':iddanhmuc' => $category]);
            $category_ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }

        $sql = "SELECT s.idsach, s.tensach, s.gia, s.anhbia 
                FROM sach s 
                LEFT JOIN chitietdanhmuc c ON s.idctdanhmuc = c.idchitietdanhmuc 
                LEFT JOIN danhmuc d ON c.iddanhmuc = d.iddanhmuc 
                WHERE s.trangthai = 1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (s.tensach LIKE :keyword)";
            $params[':keyword'] = "%$keyword%";
        }
        if (!empty($category_ids)) {
            $placeholders = [];
            foreach ($category_ids as $index => $id) {
                $param_name = ":category_id_$index";
                $placeholders[] = $param_name;
                $params[$param_name] = $id;
            }
            $sql .= " AND s.idctdanhmuc IN (" . implode(',', $placeholders) . ")";
        }
        if ($min_price !== null) {
            $sql .= " AND s.gia >= :min_price";
            $params[':min_price'] = $min_price;
        }
        if ($max_price !== null) {
            $sql .= " AND s.gia <= :max_price";
            $params[':max_price'] = $max_price;
        }

        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = (int)$limit;
            $params[':offset'] = (int)$offset;
        }

        try {
            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => &$value) {
                $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindParam($key, $value, $type);
            }
            $stmt->execute();
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return [
                'status' => 'success',
                'data' => $books,
                'total_results' => $this->countBooks($keyword, $category, $min_price, $max_price)
            ];
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi truy vấn: ' . $e->getMessage(),
                'data' => [],
                'total_results' => 0
            ];
        }
    }

    public function __destruct() {
        $this->conn = null;
    }
}
?>