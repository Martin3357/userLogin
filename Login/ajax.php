<?php
session_start();

error_reporting(E_ALL & ~E_NOTICE);

require_once "../db_conn.php";
if ($_POST['action'] == 'login') {

    $uname = trim(mysqli_real_escape_string($conn, $_POST['uname']));
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);
    //$hash = password_hash($pass,PASSWORD_BCRYPT);

    if (empty($uname)) {
        echo json_encode(array('status' => '404', 'message' => 'Username eshte bosh'));
        exit();
    } elseif (empty($pass)) {
        echo json_encode(array('status' => '404', 'message' => 'Fjalkalimi eshte bosh'));
        exit();
    }

    $query_get_data = "SELECT email,
                              password,
                              Roli,
                              ID,
                              images,
                              first_name
                              FROM adm WHERE email='$uname' OR phone='$uname'";
    $result = mysqli_query($conn, $query_get_data);
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (md5($pass) == $row['password']) {
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['email'] = $row['email'];
            //$_SESSION['password'] = $row['password'];
            $_SESSION['Roli'] = $row['Roli'];
            $_SESSION['id'] = $row['ID'];
            $_SESSION['images'] = $row['images'];

            echo json_encode(array('status' => '200', 'message' => 'User logged in!'));
            exit();
        } else {
            echo json_encode(array('status' => '404', 'message' => 'Password i pasakte!'));
            exit();
        }
    } else {
        echo json_encode(array('status' => '404', 'message' => 'Emaili ose passwordi eshte i gabuar!'));
        exit();
    }

}
elseif ($_POST['action'] == 'signup') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $atesia = mysqli_real_escape_string($conn, $_POST['atesia']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = strtolower($first_name[0]) . strtolower($last_name);
    $image = 'images/default.jpg';
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn,$_POST['cpassword']);
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
    $chash = md5($cpassword);
    if ($hash!=$chash){
        echo json_encode(array('status'=>'404','message'=>'Passwordi i vene nuk perputhet me confirm password'));
        exit();
    }

    $select = mysqli_query($conn, "SELECT ID FROM adm WHERE email = '" . $_POST['email'] . "' OR phone = '" . $_POST['phone'] . "'");
    if (mysqli_num_rows($select) > 0) {
        //$row = mysqli_fetch_assoc($select);
        {
            if ($phone == $_POST['phone']) {
                echo json_encode(array('status' => '404', 'message' => 'Ky nr teli  eshte perdorur!'));
                exit();
            } elseif ($email == $_POST['email']) {
                echo json_encode(array('status' => '404', 'message' => 'Ky email eshte perdorur!'));
                exit();
            }
        }


    }
    $roli = "user";
    $query_register_user = "INSERT INTO adm SET first_name = '" . $first_name . "',
                                                last_name = '" . $last_name . "',
                                                phone = '" . $phone . "',
                                                username = '" . $username . "',
                                                password = '" . $hash . "',
                                                dob = '" . $birthday . "',
                                                email = '" . $email . "',
                                                Roli = '" . $roli . "',
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

}