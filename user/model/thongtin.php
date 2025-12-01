<?php
function getThongTin() {
    $db = connectdb(); 
    $query = $db->prepare("SELECT * FROM thongtincuahang WHERE idthongtin = 1"); 
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}
?>