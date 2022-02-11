<?php
require_once "functions.php";
require_once "db_conn.php";
session_start();

if ($_POST['action'] == 'update') {
    $id = mysqli_real_escape_string($conn, $_POST['userupdateid']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);


    if (!preg_match("/^[a-zA-z]*$/", $first_name)) {
        echo json_encode(array('status' => '404', 'message' => 'Emri jo i sakte'));
        exit();
    } elseif (empty($first_name)) {
        echo json_encode(array('status' => '404', 'message' => 'Vendosni emrin'));
        exit();
    } elseif (!preg_match("/^[a-zA-z]*$/", $last_name)) {
        echo json_encode(array('status' => '404', 'message' => 'Mbiemri jo i sakte'));
        exit();
    } elseif ($last_name == "") {
        echo json_encode(array('status' => '404', 'message' => 'Mbiemri smund te jete bosh '));
        exit();
    } elseif (!preg_match("/^[0-9]*$/", $phone)) {
        echo json_encode(array('status' => '404', 'message' => 'Lejohen vetem vlera numerike'));
        exit();
    } elseif (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/", $email)) {
        echo json_encode(array('status' => '404', 'message' => 'Emaili ne forme jo te sakte'));
        exit();
    }
    $username = strtolower($first_name[0]) . strtolower($last_name);

    $query_update_user = "UPDATE adm SET first_name = '" . $first_name . "',
                                                last_name = '" . $last_name . "',
                                                phone = '" . $phone . "',
                                                username = '" . $username . "',
                                                dob = '" . $birthday . "',
                                                email = '" . $email . "'
                                                WHERE ID='$id'";
    $result_update_user = mysqli_query($conn, $query_update_user);

    if (!$result_update_user) {
        echo json_encode(array('status' => '404', 'message' => 'Error ne databaze' . __LINE__));
        exit();
    }
    echo json_encode(array('status' => '200', 'message' => "U editua me sukses"));
    exit();
} elseif ($_POST['action'] == 'updateprofile') {
    $id = mysqli_real_escape_string($conn, $_POST['userupdateid']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);


    if (!preg_match("/^[a-zA-z]*$/", $first_name)) {
        echo json_encode(array('status' => '404', 'message' => 'Emri jo i sakte'));
        exit();
    } elseif (empty($first_name)) {
        echo json_encode(array('status' => '404', 'message' => 'Vendosni emrin'));
        exit();
    } elseif (!preg_match("/^[a-zA-z]*$/", $last_name)) {
        echo json_encode(array('status' => '404', 'message' => 'Mbiemri jo i sakte'));
        exit();
    } elseif ($last_name == "") {
        echo json_encode(array('status' => '404', 'message' => 'Mbiemri smund te jete bosh '));
        exit();
    } elseif (!preg_match("/^[0-9]*$/", $phone)) {
        echo json_encode(array('status' => '404', 'message' => 'Lejohen vetem vlera numerike'));
        exit();
    } elseif (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/", $email)) {
        echo json_encode(array('status' => '404', 'message' => 'Emaili ne forme jo te sakte'));
        exit();
    }
    $username = strtolower($first_name[0]) . strtolower($last_name);

    $query_update_user = "UPDATE adm SET first_name = '" . $first_name . "',
                                                last_name = '" . $last_name . "',
                                                phone = '" . $phone . "',
                                                email = '" . $email . "'
                                                WHERE ID='$id'";
    $result_update_user = mysqli_query($conn, $query_update_user);

    if (!$result_update_user) {
        echo json_encode(array('status' => '404', 'message' => 'Error ne databaze' . __LINE__));
        exit();
    }
    echo json_encode(array('status' => '200', 'message' => "U editua me sukses"));
    exit();
} elseif ($_POST['action'] == 'updateprofilephoto') {
    $id = $_SESSION['id'];

    $picture = $_FILES['foto'];
    $location = "images/" . time() . $picture['name'];
    $temp_path = $picture['tmp_name'];
    move_uploaded_file($temp_path, $location);

    unlink($_POST['oldPath']);

    $query_update_user = "UPDATE adm SET images = '" . $location . "' WHERE ID='$id'";

    $result_update_user = mysqli_query($conn, $query_update_user);

    if (!$result_update_user) {
        echo json_encode(array('status' => '404', 'message' => 'Error ne databaze' . __LINE__));
        exit();
    }
    echo json_encode(array('status' => '200', 'message' => "U editua me sukses"));
    exit();
} elseif ($_POST['action'] == 'updatepassword') {
    $id = $_SESSION['id'];
    $old = mysqli_real_escape_string($conn, $_POST['oldpass']);
    $new = mysqli_real_escape_string($conn, $_POST['newpass']);
    $verify = mysqli_real_escape_string($conn, $_POST['verifypass']);


    $hash = md5($new);

    $query_get_data = "SELECT password
                              FROM adm WHERE ID='$id'";
    $result = mysqli_query($conn, $query_get_data);
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (md5($old) == $row['password']) {

            if (!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/", $new)) {
                echo json_encode(array('status' => '404', 'message' => 'Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character .'));
                exit();
            } elseif (!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/", $verify)) {
                echo json_encode(array('status' => '404', 'message' => 'Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character .'));
                exit();
            }
            if (md5($new) == md5($verify) && md5($old) != md5($new)) {
                $query_update_password = "UPDATE adm SET password = '" . $hash . "'
                                                WHERE ID='$id'";
                $result_update_password = mysqli_query($conn, $query_update_password);

                if (!$result_update_password) {
                    echo json_encode(array('status' => '404', 'message' => 'Error ne databaze' . __LINE__));
                    exit();
                }
                echo json_encode(array('status' => '200', 'message' => "Passwordi u  ndryshua me sukses"));
                exit();
            } elseif (md5($new) == md5($old)) {
                echo json_encode(array('status' => '404', 'message' => 'Passwordi i ri eshte i njejte me te vjetrin'));
                exit();
            } elseif (md5($new) != md5($verify)) {
                echo json_encode(array('status' => '404', 'message' => 'New password nuk perputhet me fushen verify password'));
                exit();
            }
        } else {
            echo json_encode(array('status' => '404', 'message' => 'Passwordi eshte i gabuar'));
            exit();
        }
    }
}