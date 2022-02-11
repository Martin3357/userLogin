<?php
require_once "functions.php";
require_once "db_conn.php";
if ($_POST['action'] == 'delete'){
    $id = mysqli_real_escape_string($conn, $_POST['userdeleteid']);
    $sql = "DELETE FROM adm WHERE ID='$id'";
    $query_delete_user = mysqli_query($conn, $sql);
    if (!$query_delete_user) {
        echo json_encode(array('status' => '404', 'message' => 'Error ne databaze' . __LINE__));
        exit();
    }

    echo json_encode(array('status' => '200', 'message' => "U fshi me sukses"));
    exit();
}