<?php
class BookModel {
    private $conn;

    public function __construct() {
        // Lấy kết nối từ connectDB.php
        $this->conn = connectdb(); // Sử dụng hàm connectdb()
    }

    // Lấy thông tin sách theo ID
    public function getBookById($bookId) {
        $stmt = $this->conn->prepare("
            SELECT s.idsach, s.tensach, s.gia, s.sltonkho, s.mota, s.anhbia,
                   t.tentacgia, nxb.tennxb, ctdm.tenchitietdanhmuc, ctdm.idchitietdanhmuc,
                   dm.tendanhmuc, dm.iddanhmuc
            FROM sach s
            JOIN tacgia t ON s.idtacgia = t.idtacgia
            JOIN nhaxuatban nxb ON s.idnhaxuatban = nxb.idnhaxuatban
            JOIN chitietdanhmuc ctdm ON s.idctdanhmuc = ctdm.idchitietdanhmuc
            JOIN danhmuc dm ON ctdm.iddanhmuc = dm.iddanhmuc
            WHERE s.idsach = ? AND s.trangthai = 1
        ");
        $stmt->execute([(int)$bookId]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Lấy danh sách hình ảnh của sách
    public function getBookImages($bookId) {
        $stmt = $this->conn->prepare("
            SELECT duongdananh
            FROM hinhanhsach
            WHERE idsach = ? AND trangthai = 1
        ");
        $stmt->execute([(int)$bookId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy sách liên quan (cùng chi tiết danh mục, loại trừ sách hiện tại)
    public function getRelatedBooks($categoryId, $bookId) {
        $stmt = $this->conn->prepare("
            SELECT s.idsach, s.tensach, s.gia, s.anhbia, t.tentacgia
            FROM sach s
            JOIN tacgia t ON s.idtacgia = t.idtacgia
            WHERE s.idctdanhmuc = ? AND s.idsach != ? AND s.trangthai = 1
            LIMIT 4
        ");
        $stmt->execute([(int)$categoryId, (int)$bookId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy sản phẩm nổi bật (dựa trên số lượng tồn kho cao hoặc số lượng bán)
    public function getFeaturedBooks() {
        $stmt = $this->conn->prepare("
            SELECT s.idsach, s.tensach, s.gia, s.anhbia, t.tentacgia
            FROM sach s
            JOIN tacgia t ON s.idtacgia = t.idtacgia
            WHERE s.trangthai = 1
            ORDER BY s.sltonkho DESC
            LIMIT 4
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy sách theo danh mục (iddanhmuc)
    public function getBooksByCategory($categoryId) {
        $stmt = $this->conn->prepare("
            SELECT s.idsach, s.tensach, s.gia, s.anhbia, t.tentacgia
            FROM sach s
            JOIN tacgia t ON s.idtacgia = t.idtacgia
            JOIN chitietdanhmuc ctdm ON s.idctdanhmuc = ctdm.idchitietdanhmuc
            WHERE ctdm.iddanhmuc = ? AND s.trangthai = 1
            LIMIT 4
        ");
        $stmt->execute([(int)$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy sách hay (dựa trên số lượng tồn kho thấp - giả định sách bán chạy)
    public function getRecommendedBooks() {
        $stmt = $this->conn->prepare("
            SELECT s.idsach, s.tensach, s.gia, s.anhbia, t.tentacgia
            FROM sach s
            JOIN tacgia t ON s.idtacgia = t.idtacgia
            WHERE s.trangthai = 1
            ORDER BY s.sltonkho ASC
            LIMIT 4
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>