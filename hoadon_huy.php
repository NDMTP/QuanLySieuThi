<?php
include('connect.php'); // Đảm bảo rằng kết nối cơ sở dữ liệu đã sẵn sàng

if (isset($_GET['hoadon'])) {
    $mahd = $conn->real_escape_string($_GET['hoadon']);
    $trangthai = "UPDATE HOADON SET TRANGTHAIHOADON = -1 WHERE MAHOADON='".$mahd."'";
    $result_tt = $conn->query($trangthai);

    if ($result_tt) {
        echo '<script language="javascript">alert("Đã hủy đơn hàng!"); window.location.href="bill.php";</script>';
    } else {
        echo '<script language="javascript">alert("Lỗi khi cập nhật hóa đơn."); window.location.href="bill.php";</script>';
    }
} else {
    echo '<script language="javascript">alert("Mã hóa đơn không tồn tại!"); window.location.href="bill.php";</script>';
}
?>
