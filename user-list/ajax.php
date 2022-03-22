<?php

session_start();

error_reporting(E_ALL & ~E_NOTICE);

require_once "../db_conn.php";
//Kushti per shtimin e nje useri te ri
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
    $cpassword = mysqli_real_escape_string($conn,$_POST['caddpassword']);

    $location = str_replace("http://localhost/Projekti/","",mysqli_real_escape_string($conn, $_POST['oldPath']));
    $oldPic = mysqli_real_escape_string($conn, $_POST['oldPath']);

    //Validimi i fotos
    if (isset($_FILES['foto'])) {
        $picture = $_FILES['foto'];

        $fileName = $picture['name'];
        $fileSize = $picture['size'];
        $fileTmpLoc = $picture['tmp_name'];
        $fileError = $picture['error'];

        //allowed only jpg jpeg png files JPG JpG jpeG
        $f = explode('.', $fileName);
        $fileExt = strtolower($f[1]);
        $allowedExt = array('jpg', 'jpeg', 'png', 'jfif');
        if (in_array($fileExt, $allowedExt)) {
            if ($fileSize < 2097152) {
                $location = "images/" . time() . $picture['name'];

                move_uploaded_file($fileTmpLoc, $location);
            } else {
                echo json_encode(array('status' => '404', 'message' => 'File size exceeded'));
                exit();
            }

        } else {
            echo json_encode(array('status' => '404', 'message' => 'File Type not Supported'));
            exit();
        }
        //unlink($oldPic);

    }
    //Validimi i modalit
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
    $chash=md5($cpassword);
    //Kushti i perputhshmerise se passwordeve
    if ($hash!=$chash){
        echo json_encode(array('status'=>'404','message'=>'Password me verify password nuk perputhen'));
        exit();
    }

    //Kushti per te pare nese ekziston emaili apo nr tel i futur nga admini
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
    //query ku inserojme te dhenat e marra nga me siper
    $query_register_user = "INSERT INTO adm SET first_name = '" . $first_name . "',
                                                last_name = '" . $last_name . "',
                                                phone = '" . $phone . "',
                                                username = '" . $username . "',
                                                password = '" . $hash . "',
                                                Roli = '" . $roli . "',
                                                dob = '" . $birthday . "',
                                                email = '" . $email . "',
                                                images = '" . $location . "',
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
//Datatable backend
elseif ($_POST['action'] == 'list') {
//    printArray($_POST);
//    exit;

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


    //Searchi ne baze te dates
    if($_POST['data_flt'] != ''){
        $data = mysqli_real_escape_string($conn, $_POST['data_flt']);
        $data_arr = explode(' / ',$data);
//        printArray($data_arr);exit;
        $datafrom = str_replace(' ', '',$data_arr[0]);
        $datato = str_replace(' ', '',$data_arr[1]);

        $data_flt_query = " AND dob >= '".$datafrom."' AND dob <= '".$datato."' ";
    }else{
        $data_flt_query = '';
    }

    //Shfaqja e te dhenave te marra nga linku i produkteve
    if ($_POST['name_serch_product']!=''){
        $client_flt = mysqli_real_escape_string($conn,$_POST['name_serch_product']);
        $client_fltclear =substr($client_flt,0,-1);
        $client_fltclear = str_replace(";","','","'".$client_fltclear."'");

        $client_filter = " AND ID in (".$client_fltclear.")";

    }else{
        $client_filter='';
    }

    //Filtrimi i te dhenave ne baze te emaileve
    if ($_POST['email_search']!=''){
        $email_filter = mysqli_real_escape_string($conn,$_POST['email_search']);

        $email_flt = str_replace('\\','',$email_filter);
        //printArray($email_flt);
        $emailFilter = " AND email in (".$email_flt.")";
    }else{
        $emailFilter = '';
    }

    //Filtrimi i te dhenave ne baze te nr te telefonit
    if ($_POST['phone_search']!=''){
        $phone_filter = mysqli_real_escape_string($conn,$_POST['phone_search']);
        $phone_flt_query = " AND phone in ('".$phone_filter."')";

    }else{
        $phone_flt_query = '';
    }
    /**
     * Merr numrin total te rekordeve pa aplikuar filtrat. Psh kur shfaqim 10/30 rekorde,
     * numrin tital e marrim permes ketij query
     */
    $query_without_ftl = "SELECT COUNT(*) AS allcount 
                          FROM adm where 1 = 1  $data_flt_query $emailFilter $phone_flt_query $client_filter $searchQuery";

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
                       where  1 = 1 $data_flt_query $emailFilter $phone_flt_query $phone_flt_query $client_filter $searchQuery";

    $result_with_ftl = mysqli_query($conn, $query_with_ftl);
    if (!$result_with_ftl) {
        $error = mysqli_error($conn) . " " . __LINE__;
    }

    $records_with_ftl = mysqli_fetch_assoc($result_with_ftl);
    $totalRecordflt = $records_with_ftl['allcount'];


    $query_user = "SELECT * FROM adm WHERE 1=1 $emailFilter $data_flt_query $phone_flt_query $client_filter";

    //filtrimi i te dhenave duke perdorur searchin
    if ($_POST['search']['value']) {
        $query_user .= " AND (
             first_name LIKE '%" . $search_value . "%' OR 	
             last_name LIKE '%" . $search_value . "%' OR 	
             phone LIKE '%" . $search_value . "%' OR
             email LIKE '%" . $search_value . "%' OR 
             dob LIKE '%" . $search_value . "%' OR
             Roli LIKE '%" . $search_value . "%')";
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


    //mbushja e vektorit data me te dhenat e nevojshme
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

    //cikli i cili con te dhenat ne front end per mbushjen e datatables
    foreach ($data as $key => $row) {
        $image = '';
        if ($row['images']) {
            $image = '<img src = "http://localhost/Projekti/' . $row['images'] . '" width = "50px" height = "50px" > ';
        }

        $table_data[] = array("id" => $row['ID'],
            "firstname" => $row['firstname'],
            "lastname" => $row['lastname'],
            "phone" => $row['phone'],
            "email" => $row['email'],
            "birthday" => $row['birthday'],
            "post" => $row['post'],
            "images" => $image,
            "action" => "<div class='row mt-2'>
                            <nobr class='mx-4'>
                                <button class='usereditid btn btn-success' value='" . $row['ID'] . "' onclick='allowShowUser(" . $row['ID'] . ")'  type='button'> EDIT </button>
                                <button id='userdeleteid' type='button' class='btn btn-danger' onclick='deleteusermodal(" . $row['ID'] . ")' data-toggle='modal' data-target='#deleteModal'>DELETE</button>
                           </nobr>
                         </div> "

        );
    }

    $response = array("draw" => intval($draw), "iTotalRecords" => $totalRecords, "iTotalDisplayRecords" => $totalRecordflt, "data" => $table_data);
    echo json_encode($response);


}
//Kushti per plotesimin automatik te modalit te  userave
elseif ($_POST['action'] == 'edit') {
    $id = mysqli_real_escape_string($conn, $_POST['usereditid']);
    //printArray($id);
    $query_exist_id="
                    SELECT ID FROM adm WHERE ID=".$id."
                     ";
    $result_exist_id = mysqli_query($conn,$query_exist_id);

    $row = mysqli_fetch_assoc($result_exist_id);
    if (empty($row['ID'])){
        echo json_encode(array('status' => '404', 'message' => 'User doesnt exist' . __LINE__));
        exit();
    }
    $sql = "SELECT first_name,
                              last_name,
                              atesia,
                              phone,
                              dob,
                              email,
                              username,
                              images
                              FROM adm WHERE ID='$id'";
    $query_get_user = mysqli_query($conn, $sql);
    if (!$query_get_user) {
        echo json_encode(array('status' => '404', 'message' => 'Error ne databaze' . __LINE__));
    }
    $row = mysqli_fetch_assoc($query_get_user);
    echo json_encode(array('status' => '200', 'message' => $row));
    exit();
}elseif ($_GET['action'] == 'get_all_numbers'){

    $search_text = mysqli_real_escape_string($conn, $_GET['search']);
    $query_numbers = "SELECT distinct phone
                          FROM adm
                          WHERE phone like '%" . $search_text . "%'";

    $result_numbers = mysqli_query($conn, $query_numbers);
    if (!$result_numbers) {
        echo json_encode(array('status' => '404', 'message' => "Internal server error "));
        exit;
    }

    while ($row = mysqli_fetch_assoc($result_numbers)) {
        if (!empty($row['phone'])) {
            $json[] = array('id' => $row['phone'], 'text' => $row['phone']);
        } else {
            $json[] = array('id' => '0', 'text' => 'No Results Found');
        }

    }

    echo json_encode($json);
    exit;
}elseif ($_GET['action']=='get_all_emails'){
//    printArray($_GET);

//printArray($ids);
    $search_text = mysqli_real_escape_string($conn, $_GET['search']);
    $query_email = "SELECT distinct email
                          FROM adm
                          WHERE  email like '%" . $search_text . "%'";

    $result_email = mysqli_query($conn, $query_email);
    if (!$result_email) {
        echo json_encode(array('status' => '404', 'message' => "Internal server error "));
        exit;
    }

    while ($row = mysqli_fetch_assoc($result_email)) {
        if (!empty($row['email'])) {
            $json[] = array('id' => $row['email'], 'text' => $row['email']);
        } else {
            $json[] = array('id' => '0', 'text' => 'No Results Found');
        }

    }

    echo json_encode($json);
    exit;
}
//Kushti per perditesimin e te dhenave te userit
elseif  ($_POST['action'] == 'update') {
    $id = mysqli_real_escape_string($conn, $_POST['userupdateid']);
//    echo $id;
//    exit;
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $location = str_replace("http://localhost/Projekti/","",mysqli_real_escape_string($conn, $_POST['oldPath']));
    $oldPic = mysqli_real_escape_string($conn, $_POST['oldPath']);


    if (isset($_FILES['foto'])) {
        $picture = $_FILES['foto'];
        $fileName = $picture['name'];
        $fileSize = $picture['size'];
        $fileTmpLoc = $picture['tmp_name'];
        $fileError = $picture['error'];

        //allowed only jpg jpeg png files JPG JpG jpeG
        $f = explode('.', $fileName);
        $fileExt = strtolower($f[1]);
        $allowedExt = array('jpg', 'jpeg', 'png', 'jfif');
        if (in_array($fileExt, $allowedExt)) {
            if ($fileSize < 2097152) {
                $location = "images/" . time() . $picture['name'];

                move_uploaded_file($fileTmpLoc, "../".$location);
            } else {
                echo json_encode(array('status' => '404', 'message' => 'File size exceeded'));
                exit();
            }

        } else {
            echo json_encode(array('status' => '404', 'message' => 'File Type not Supported'));
            exit();
        }
        //unlink($oldPic);

    }

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
    $query_exist_id="
                    SELECT ID FROM adm WHERE ID=".$id."
                     ";
    $result_exist_id = mysqli_query($conn,$query_exist_id);

    $row = mysqli_fetch_assoc($result_exist_id);
    if (empty($row['ID'])){
        echo json_encode(array('status' => '404', 'message' => 'User doesnt exist' . __LINE__));
        exit();
    }

    $query_update_user = "UPDATE adm SET first_name = '" . $first_name . "',
                                                last_name = '" . $last_name . "',
                                                phone = '" . $phone . "',
                                                username = '" . $username . "',
                                                dob = '" . $birthday . "',
                                                email = '" . $email . "',
                                                images = '" . $location . "'
                                                WHERE ID='$id'";

    $result_update_user = mysqli_query($conn, $query_update_user);

    if (!$result_update_user) {
        echo json_encode(array('status' => '404', 'message' => 'Error ne databaze' . __LINE__));
        exit();
    }
    echo json_encode(array('status' => '200', 'message' => "U editua me sukses"));
    exit();
}
//Kushti per fshirjen e userit
elseif ($_POST['action'] == 'delete'){
    $id = mysqli_real_escape_string($conn, $_POST['userdeleteid']);
    $query_exist_id="
                    SELECT ID FROM adm WHERE ID=".$id."
                     ";
    $result_exist_id = mysqli_query($conn,$query_exist_id);

    $row = mysqli_fetch_assoc($result_exist_id);
    if (empty($row['ID'])){
        echo json_encode(array('status' => '404', 'message' => 'User doesnt exist! ' . __LINE__));
        exit();
    }
    $sql = "DELETE FROM adm WHERE ID='$id'";
    $query_delete_user = mysqli_query($conn, $sql);
    if (!$query_delete_user) {
        echo json_encode(array('status' => '404', 'message' => 'Ky user nuk ekziston' . __LINE__));
        exit();
    }

    echo json_encode(array('status' => '200', 'message' => "U fshi me sukses"));
    exit();
}