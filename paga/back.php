<?php
error_reporting(E_ERROR | E_PARSE);
include "../functions.php";
include_once "db.php";


/*
 * Query per te marre ditet speciale
 * */
$offDaysQuery = "SELECT date from off_days";
$resultOffDay = mysqli_query($conn, $offDaysQuery);
if (!$resultOffDay) {
    ?>
    <h1 style="text-align: center;color: Red">Po behen ndryshime provojeni pak me vone</h1>
<?php
    exit;
}

$off_days = array();
while ($row = mysqli_fetch_assoc($resultOffDay)) {
    $off_days[$row['date']]['ditePushimi'] = $row['date'];
}

/*
 * Query per te marre te dhenat e userit
 * */
$user_query = "SELECT users.id, users.full_name, users.total_paga, 
working_days.date, working_days.hours FROM users left join working_days  on users.id = working_days.user_id left join off_days on off_days.date=working_days.date";

$result_user = mysqli_query($conn, $user_query);
if (!$result_user){
    ?>
    <h1 style="text-align: center;color: Red">Po behen ndryshime provojeni pak me vone</h1>
    <?php
    exit;
}



$user_data = array();



while ($row = mysqli_fetch_assoc($result_user)){
    $user_data[$row['id']]['id'] = $row['id'];
    $user_data[$row['id']]['emri'] = $row['full_name'];
    $user_data[$row['id']]['oret'] = $row['hours'];
    $user_data[$row['id']]['pagaTotale'] = $row['total_paga'];
    $user_data[$row['id']]['data'] = $row['date'];

    $paga =  $user_data[$row['id']]['pagaTotale'];
    $dita_pushimit= $user_data[$row['id']]['data'];

    $date = strtotime($dita_pushimit);
    $date = date("l", $date);
    $date = strtolower($date);
    /*
 * Kushtet per ditet perkatese qe ka punuar useri
 * */
    if($date == "saturday" || $date == "sunday") {
        $k_in=1.5;
        $k_out = 2;
        $user_data[$row['id']]['Details'][$row['date']]['lloji']="Fundjave";

        //    llogarisim oret in te fundjaves
//    nqs ka punuar 8 ore ose me pak, jan oret in
//    Nqs ka punuar mbi 8 ore, oret out
        if ($user_data[$row['id']]['oret']>=8){
            $user_data[$row['id']]['oretInDiteP'] += 8;

            $user_data[$row['id']]['oretOutDiteP'] += ($user_data[$row['id']]['oret'] - 8);
        }
        else{
            $user_data[$row['id']]['oretInDiteP'] +=$user_data[$row['id']]['oret'];
        }
    }elseif (isset($off_days[$row['date']]['ditePushimi'])){
        $k_in=2;
        $k_out = 2.5;
        $user_data[$row['id']]['Details'][$row['date']]['lloji']="DiteSpeciale";


//    llogarisim oret in per ditet speciale
//    nqs ka punuar 8 ore ose me pak, jan oret in
//    Nqs ka punuar mbi 8 ore, oret out
        if ($user_data[$row['id']]['oret']>=8){
            $user_data[$row['id']]['oretInDiteS'] += 8;

            $user_data[$row['id']]['oretOutDiteS'] += ($user_data[$row['id']]['oret'] - 8);
        }
        else{
            $user_data[$row['id']]['oretInDiteS'] +=$user_data[$row['id']]['oret'];
            $user_data[$row['id']]['oretOutDiteS'] = 0;
        }
    }
    else{
        $k_in=1;
        $k_out = 1.25;
        $user_data[$row['id']]['Details'][$row['date']]['lloji']="Jave";
        //llogarisim oret in per diten e javes
        //nqs ka punuar 8 ore ose me pak, jan oret in
        //Nqs ka punuar mbi 8 ore, oret out
        if ($user_data[$row['id']]['oret']>8){
            $user_data[$row['id']]['oretInJave'] += 8;

            $user_data[$row['id']]['oretOutJave'] += ($user_data[$row['id']]['oret'] - 8);
        }
        else {
            $user_data[$row['id']]['oretInJave'] += $user_data[$row['id']]['oret'];
            $user_data[$row['id']]['oretOutJave']=0;
        }
    }

    $user_data[$row['id']]['pagaPerOre']= round($paga/22/8, 2   );

    $pagaIn = $user_data[$row['id']]['pagaPerOre'];

    if ($user_data[$row['id']]['oret']>8){
        $user_data[$row['id']]['oretInTot'] += 8;

        $user_data[$row['id']]['oretOutTot'] += ($user_data[$row['id']]['oret'] - 8);
    }
    else {
        $user_data[$row['id']]['oretInTot'] += $user_data[$row['id']]['oret'];

    }

    $user_data[$row['id']]['data'] = $row['date'];
    $user_data[$row['id']]['Details'][$row['date']]['oretTot'] = $row['hours'];
    if ($user_data[$row['id']]['Details'][$row['date']]['oretTot']>8){
        $user_data[$row['id']]['Details'][$row['date']]['oretIn']=8;
        $user_data[$row['id']]['Details'][$row['date']]['oretOut']=$user_data[$row['id']]['Details'][$row['date']]['oretTot']-8;
    }else{
        $user_data[$row['id']]['Details'][$row['date']]['oretIn']=$user_data[$row['id']]['Details'][$row['date']]['oretTot'];
        $user_data[$row['id']]['Details'][$row['date']]['oretOut']=0;
    }
    //Te dhenat perkatese per secilen nga ditet
    $user_data[$row['id']]['Details'][$row['date']]['single_date']=$row['date'];
    $user_data[$row['id']]['Details'][$row['date']]['PagesaIn'] = $pagaIn*$user_data[$row['id']]['Details'][$row['date']]['oretIn']*$k_in;
    $user_data[$row['id']]['Details'][$row['date']]['PagesaOut'] = round($pagaIn*$user_data[$row['id']]['Details'][$row['date']]['oretOut']*$k_out,2);
   //Te dhenat totale per te gjitha ditet
    $user_data[$row['id']]['PagesaInTotale'] +=$user_data[$row['id']]['Details'][$row['date']]['PagesaIn'];
    $user_data[$row['id']]['PagesaInDiteJ'] = $pagaIn*$user_data[$row['id']]['oretInJave'];
    $user_data[$row['id']]['PagesaOutDiteJ'] = $pagaIn*$user_data[$row['id']]['oretOutJave']*1.25;
    $user_data[$row['id']]['PagesaInDiteP'] = $pagaIn*$user_data[$row['id']]['oretInDiteP']*1.5;
    $user_data[$row['id']]['PagesaOutDiteP'] = $pagaIn*$user_data[$row['id']]['oretOutDiteP']*2;
    $user_data[$row['id']]['PagesaINDiteS'] = $pagaIn*$user_data[$row['id']]['oretInDiteS']*2;
    $user_data[$row['id']]['PagesaOutDiteS'] = $pagaIn*$user_data[$row['id']]['oretOutDiteS']*2.5;
    $user_data[$row['id']]['PagesaOutTotale'] +=$user_data[$row['id']]['Details'][$row['date']]['PagesaOut'];
}