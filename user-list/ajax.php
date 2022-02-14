<?php

require_once "../functions.php";
require_once "../db_conn.php";

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

} elseif ($_POST['action'] == 'list') {

    $draw = $_POST['draw'];
    $limit_start = $_POST['start'];
    $limit_end = $_POST['length'];
    $columnIndex = $_POST['order'][0]['column'];
    $columnName = $_POST['columns'][$columnIndex]['data'];
    $columnSortOrder = $_POST['order'][0]['dir'];

    $search_value = $_POST['search']['value'];
    $searchQuery = '';
    if (!empty($search_value)) {
        $searchQuery = " AND (
             first_name LIKE '%" . $search_value . "%' OR 	
            last_name LIKE '%" . $search_value . "%' OR 	
            phone LIKE '%" . $search_value . "%' OR
            email LIKE '%" . $search_value . "%' OR 
            dob LIKE '%" . $search_value . "%' OR
            Roli LIKE '%" . $search_value . "%')";
    }
    /**
     * Merr numrin total te rekordeve pa aplikuar filtrat. Psh kur shfaqim 10/30 rekorde,
     * numrin tital e marrim permes ketij query
     */
    $query_without_ftl = "SELECT COUNT(*) AS allcount 
                          FROM adm where 1 = 1 $searchQuery";

    $reuslt_without_ftl = mysqli_query($conn, $query_without_ftl);

    if (!$reuslt_without_ftl) {
        $error = mysqli_error($conn) . " " . __LINE__;
    }

    $records = mysqli_fetch_assoc($reuslt_without_ftl);
    $totalRecords = $records['allcount'];


    /**
     * Numrin total te rekordeve duke aplikuar filtrin search
     */
    $query_with_ftl = "SELECT COUNT(*) AS allcount 
                       FROM  adm 
                       where  1 = 1 $searchQuery";

    $result_with_ftl = mysqli_query($conn, $query_with_ftl);
    if (!$result_with_ftl) {
        $error = mysqli_error($conn) . " " . __LINE__;
    }

    $records_with_ftl = mysqli_fetch_assoc($result_with_ftl);
    $totalRecordflt = $records_with_ftl['allcount'];


    $query_user = "SELECT * FROM adm WHERE 1=1 ";


    if ($_POST['search']['value']) {
        $query_user .= "AND first_name like'%" . $search_value . "%'";
        $query_user .= "OR last_name like '%" . $search_value . "%'";
        $query_user .= "OR phone like '%" . $search_value . "%'";
        $query_user .= "OR email like '%" . $search_value . "%'";
        $query_user .= "OR dob like '%" . $search_value . "%'";
        $query_user .= "OR Roli like '%" . $search_value . "%'";
    }


    if ($_POST['order']) {
        $column = $_POST['order'][0]['column'];

        $column_name = $_POST['columns'][$column]['name'];
        $order = strtoupper($_POST['order'][0]['dir']);
        $query_user .= "ORDER BY " . $column_name . " " . $order . " ";
    } else {
        $query_user .= "ORDER BY ID ASC";
    }


    if ($_POST['length'] != -1) {
        $start = $_POST['start'];
        $length = $_POST['length'];
        $query_user .= "LIMIT " . $start . "," . $length;
    }


    $user_result = mysqli_query($conn, $query_user);

    $table_data = [];
    $data = array();

    while ($row = mysqli_fetch_assoc($user_result)) {
        $data[$row['ID']]['ID'] = $row['ID'];
        $data[$row['ID']]['firstname'] = $row['first_name'];
        $data[$row['ID']]['lastname'] = $row['last_name'];
        $data[$row['ID']]['phone'] = $row['phone'];
        $data[$row['ID']]['email'] = $row['email'];
        $data[$row['ID']]['birthday'] = $row['dob'];
        $data[$row['ID']]['post'] = $row['Roli'];
        $data[$row['ID']]['images'] = $row['images'];
    }

    foreach ($data as $key => $row) {
        $image = '';
        if ($row['images']) {
            $image = '<img src = "../' . $row['images'] . '" width = "50px" height = "50px" > ';
        }

        $table_data[] = array("id" => $row['ID'],
            "firstname" => $row['firstname'],
            "lastname" => $row['lastname'],
            "phone" => $row['phone'],
            "email" => $row['email'],
            "birthday" => $row['birthday'],
            "post" => $row['post'],
            "images" => $image,
            "action" => "<div class='row'>
                            <nobr>
                                <button id='usereditid' onclick='userEdit(" . $row['ID'] . ")' type='button' class='btn btn-success' data-toggle='modal' data-target='#userModal'>EDIT</button>
                                <button id='userdeleteid' type='button' class='btn btn-danger' onclick='deleteusermodal(" . $row['ID'] . ")' data-toggle='modal' data-target='#deleteModal'>DELETE</button>
                           </nobr>
                         </div> "

        );
    }

    $response = array("draw" => intval($draw), "iTotalRecords" => $totalRecords, "iTotalDisplayRecords" => $totalRecordflt, "data" => $table_data);
    echo json_encode($response);


} elseif ($_POST['action'] == 'edit') {
    $id = mysqli_real_escape_string($conn, $_POST['usereditid']);
    //printArray($id);
    $sql = "SELECT first_name,
                              last_name,
                              atesia,
                              phone,
                              dob,
                              email,
                              username
                              FROM adm WHERE ID='$id'";
    $query_get_user = mysqli_query($conn, $sql);
    if (!$query_get_user) {
        echo json_encode(array('status' => '404', 'message' => 'Error ne databaze' . __LINE__));
    }
    $row = mysqli_fetch_assoc($query_get_user);
    echo json_encode(array('status' => '200', 'message' => $row));
    exit();
}

