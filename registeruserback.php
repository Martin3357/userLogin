<?php
//session_start();

require_once "functions.php";
require_once "db_conn.php";
if ($_POST['action'] == 'register') {
    $first_name = mysqli_real_escape_string($conn, $_POST['addfirst_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['addlast_name']);
    $atesia = mysqli_real_escape_string($conn, $_POST['addatesia']);
    $roli = mysqli_real_escape_string($conn, $_POST['addroli']);
    $phone = mysqli_real_escape_string($conn, $_POST['addphone']);
    $birthday = mysqli_real_escape_string($conn, $_POST['addbirthday']);
    $email = mysqli_real_escape_string($conn, $_POST['addemail']);
    $username = strtolower($first_name[0]) . strtolower($last_name);
    $password = mysqli_real_escape_string($conn, $_POST['addpassword']);
    $image = 'images/default.jpg';
    if (!preg_match("/^[a-zA-z]*$/", $first_name)) {
        echo json_encode(array('status' => '404', 'message' => 'Emri jo i sakte'));
        exit();
    } elseif ($first_name == "") {
        echo json_encode(array('status' => '404', 'message' => 'Vendosni emrin'));
        exit();
    } elseif (!preg_match("/^[a-zA-z]*$/", $last_name)) {
        echo json_encode(array('status' => '404', 'message' => 'Mbiemri jo i sakte'));
        exit();
    } elseif ($last_name == "") {
        echo json_encode(array('status' => '404', 'message' => 'Vendosni mbiemrin'));
        exit();
    } elseif (!preg_match("/^[a-zA-z]*$/", $atesia)) {
        echo json_encode(array('status' => '404', 'message' => 'Atesia jo e sakte'));
        exit();
    } elseif ($atesia == "") {
        echo json_encode(array('status' => '404', 'message' => 'Vendosni atesine'));
        exit();
    } elseif ($roli == "") {
        echo json_encode(array('status' => '404', 'message' => 'zgjidhni rolin'));
        exit();
    } elseif (!preg_match("/^[0-9]*$/", $phone)) {
        echo json_encode(array('status' => '404', 'message' => 'Lejohen vetem vlera numerike'));
        exit();
    } elseif (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/", $email)) {
        echo json_encode(array('status' => '404', 'message' => 'Emaili ne forme jo te sakte'));
        exit();
    } elseif (!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/", $password)) {

        echo json_encode(array('status' => '404', 'message' => 'Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character .'));
        exit();
    }
    $hash = md5($password);

    $select = mysqli_query($conn, "SELECT ID FROM adm WHERE email = '" . $_POST['addemail'] . "' OR phone = '" . $_POST['addphone'] . "'");
    if (mysqli_num_rows($select) > 0) {
        //$row = mysqli_fetch_assoc($select);
        {
            if ($phone == $_POST['addphone']) {
                echo json_encode(array('status' => '404', 'message' => 'Ky nr teli  eshte perdorur!'));
                exit();
            } elseif ($email == $_POST['addemail']) {
                echo json_encode(array('status' => '404', 'message' => 'Ky email eshte perdorur!'));
                exit();
            }
        }


    }
    $query_register_user = "INSERT INTO adm SET first_name = '" . $first_name . "',
                                                last_name = '" . $last_name . "',
                                                phone = '" . $phone . "',
                                                username = '" . $username . "',
                                                password = '" . $hash . "',
                                                Roli = '" . $roli . "',
                                                dob = '" . $birthday . "',
                                                email = '" . $email . "',
                                                images = '" . $image . "',
                                                atesia = '" . $atesia . "'
                                                ";
    $result_register_user = mysqli_query($conn, $query_register_user);

    if (!$result_register_user) {
        echo json_encode(array('status' => '404', 'message' => mysqli_error($conn)));
        exit();
    } else {
        echo json_encode(array('status' => '200', 'message' => 'U regjistrua me sukses'));
        exit();
    }


//echo json_encode(array('status' => '200', 'message' => 'User logged in!'));
    /*$query_register_user = "INSERT INTO adm SET first_name = '" . $first_name . "',
                                                last_name = '" . $last_name . "',
                                                phone = '" . $phone . "',
                                                username = '" . $username . "',
                                                password = '" . $password . "',
                                                dob = '" . $birthday . "',
                                                email = '" . $email . "',
                                                atesia = '" . $atesia . "',
                                                ";
    $result_register_user = mysqli_query($conn, $query_register_user);
    if (!$result_register_user) {
        //echo json_encode(array('status' => '404', 'message' => 'Emaili ne forme jo te sakte'));
    }
    //Header("Location:ajax.php");*/
}
