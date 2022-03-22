<?php
error_reporting(0);

session_start();

require_once "../db_conn.php";
//Datatable per chekins
if ($_POST['action'] == 'check') {

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
             check_in_date LIKE '%" . $search_value . "%')";
    }
    //Filtrimi ne baze te dates
    if($_POST['data_flt'] != ''){
        $data = mysqli_real_escape_string($conn, $_POST['data_flt']);
        $data_arr = explode(' / ',$data);
//        printArray($data_arr);exit;
        $datafrom = str_replace(' ', '',$data_arr[0]);
        $datato = str_replace(' ', '',$data_arr[1]);

        $data_flt_query = " AND check_in_date >= '".$datafrom."' AND check_in_date <= '".$datato."' ";
    }else{
        $data_flt_query = '';
    }

    //filtrimi ne baze te emrit
    if ($_POST['name_search']!=''){
        $name_filter = mysqli_real_escape_string($conn,$_POST['name_search']);
        $name_flt_query = " AND first_name in ('".$name_filter."')";

    }else{
        $name_flt_query = '';
    }



    /**
     * Numrin total te rekordeve duke aplikuar filtrin search
     */
    $query_without_ftl = "SELECT  COUNT(DISTINCT user_id,check_in_date) AS allcount 
                       FROM adm inner join checkins on checkins.user_id = adm.ID 
                   WHERE 1=1 $data_flt_query $name_flt_query";

    $reuslt_without_ftl = mysqli_query($conn, $query_without_ftl);

    if (!$reuslt_without_ftl) {
        $error = mysqli_error($conn) . " " . __LINE__;
    }

    $records = mysqli_fetch_assoc($reuslt_without_ftl);
    $totalRecords = $records['allcount'];


    /**
     * Numrin total te rekordeve duke aplikuar filtrin search
     */
    $query_with_ftl = "SELECT COUNT(DISTINCT user_id,check_in_date) AS allcount 
                       FROM adm  
                       inner join checkins on checkins.user_id = adm.ID
                   WHERE 1=1 $searchQuery $data_flt_query $name_flt_query";

    $result_with_ftl = mysqli_query($conn, $query_with_ftl);
    if (!$result_with_ftl) {
        $error = mysqli_error($conn) . " " . __LINE__;
    }

    $records_with_ftl = mysqli_fetch_assoc($result_with_ftl);
    $totalRecordfilt = $records_with_ftl['allcount'];
//    printArray($totalRecordfilt);
//    exit;

    //Vendosim nje limit per te marre 10 rekorde per cdo faqe te tabeles
    $helper = array();

    $query_user_lim = "select DISTINCT user_id, check_in_date FROM adm  
                       inner join checkins on checkins.user_id = adm.ID where 1 = 1 $data_flt_query $name_flt_query";


    //Filtrimi duke perdorur searchin

    if ($_POST['search']['value']) {
        $query_user_lim .= " AND (
             first_name LIKE '%" . $search_value . "%' OR 	
             last_name LIKE '%" . $search_value . "%' OR 	
             check_in_date LIKE '%" . $search_value . "%')";
    }

    //Renditja e tabeles asc/desc
    if ($_POST['order']) {
        $column = $_POST['order'][0]['column'];

        $column_name = $_POST['columns'][$column]['name'];

        $order = strtoupper($_POST['order'][0]['dir']);
        $query_user_lim .= "ORDER BY " . $column_name . " " . $order . " ";
    } else {
        $query_user_lim .= "ORDER BY ID ASC";
    }

    if ($_POST['length'] != -1) {
        $start = $_POST['start'];
        $length = $_POST['length'];

        $query_user_lim .= "LIMIT " . $start . "," . $length;
    }
    $user_result_lim = mysqli_query($conn, $query_user_lim);
    while ($row = mysqli_fetch_assoc($user_result_lim)) {
        $helper['id'][$row['user_id']] = $row['user_id'];
        $helper['check_in_date'][$row['check_in_date']] = $row['check_in_date'];
    }

    $query_date_limiter = "('" . implode("','", $helper['check_in_date']) . "')";
    $query_id_limiter = "('" . implode("','", $helper['id']) . "')";


    //query kryesor per marrjen e te dhenave qe na duhen per mbushjen e tabeles
    $query_user = "
                    SELECT adm.ID ,
                          adm.first_name,
                          adm.last_name,
                          checkins.check_in_date,
                          checkins.check_in_hour,
                          checkins.check_out_date,
                          checkins.check_out_hour
                    FROM checkins
                    inner join adm on checkins.user_id = adm.ID where adm.ID in $query_id_limiter and checkins.check_in_date in $query_date_limiter 
                     $data_flt_query";

    $table_data = [];
    $data = array();
    //$query_user="SELECT adm.ID, checkins.id FROM checkins INNER JOIN adm ON adm.ID = checkins.id";
    $user_result = mysqli_query($conn, $query_user);


    //cikli per mbushjen e vektorit table data
    while ($row = mysqli_fetch_assoc($user_result)) {

        if ($row['check_out_hour']=="00:00:00"){
            $row['check_out_hour']="18:00:00";
        }
        $out = strtotime($row['check_in_hour']);


        $data[$row['ID']][$row['check_in_date']]['ID'] = $row['ID'];
        $data[$row['ID']][$row['check_in_date']]['firstname'] = $row['first_name'];
        $data[$row['ID']][$row['check_in_date']]['lastname'] = $row['last_name'];
        $data[$row['ID']][$row['check_in_date']]['dita'] = $row['check_in_date'];
        $data[$row['ID']][$row['check_in_date']]['hoursIn'] += (strtotime($row['check_out_hour'])-strtotime($row['check_in_hour']))/3600;
        //$data[$row['ID']][$row['check_in_date']]['hoursOut'] = 8-$data[$row['ID']][$row['check_in_date']]['hoursIn'] ;
        $data[$row['ID']][$row['check_in_date']]['details'][] = $row;


        if ($data[$row['ID']][$row['check_in_date']]['hoursIn']>9) {
            $data[$row['ID']][$row['check_in_date']]['hoursOut'] = 0;

        }else{
            $data[$row['ID']][$row['check_in_date']]['hoursOut'] =9-$data[$row['ID']][$row['check_in_date']]['hoursIn'];
        }

    }

    //cikli per te cuar te dhenat ne front end
    foreach ($data as $key => $row) {
        foreach ($row as $key2 => $row2) {
//
            $table_data[] = array(
                "firstName" => $row2['firstname'],
                "lastName" => $row2['lastname'],
                "hoursin" => round($row2['hoursIn'], 2),
                "hoursout" => round($row2['hoursOut'], 2),
                "date" => $row2['dita'],
                "details" => $row2['details']
            );
        }
    }

    $response = array("draw" => intval($draw), "iTotalRecords" => $totalRecords, "iTotalDisplayRecords" => $totalRecordfilt, "data" => $table_data);
    echo json_encode($response);


}
//kushti ku marrim emrin te cilin do kerkojme

elseif ($_GET['action'] == 'get_all_names'){

    $search_name = mysqli_real_escape_string($conn, $_GET['search']);

    $query_name = "SELECT distinct adm.ID,adm.first_name,adm.last_name
                          FROM adm inner join checkins on checkins.user_id = adm.ID
                          WHERE first_name like '%" . $search_name . "%'";

    $result_name = mysqli_query($conn, $query_name);
    if (!$result_name) {
        echo json_encode(array('status' => '404', 'message' => "Internal server error "));
        exit;
    }

    while ($row = mysqli_fetch_assoc($result_name)) {
        if (!empty($row['first_name'])) {
            $json[] = array('id' => $row['first_name'], 'text' => $row['first_name']);
        } else {
            $json[] = array('id' => '0', 'text' => 'No Results Found');
        }

    }

    echo json_encode($json);
    exit;
}