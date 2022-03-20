<?php
error_reporting(E_ERROR | E_PARSE);
include "../functions.php";
include_once "db_conn.php";


/*
 * Query per te marre te dhenat e produkteve dhe te userave
 * */
$query_products = "SELECT adm.ID,
                          adm.first_name,
                          products_table.product_name,
                          products_table.id,
                          products_table.sasia_blere_tot,
                          markat.id_m,
                          markat.marka,
                          markat.cmimi_blere,
                          markat.cmimi_shitur,
                          markat.product_id,
                          markat.sasia_blere,
                          markat.sasia_shitur,
                          blerje.blere,
                          blerje.data_blerjes,
                          blerje.id_b
                   FROM markat
                        left join blerje on blerje.mark_id = markat.id_m
                        left join adm on adm.ID = blerje.client_id 
                       
                        left join products_table on markat.product_id= products_table.id
                        ";

$result_products = mysqli_query($conn, $query_products);
if (!$result_products){
    ?>
    <h1 style="text-align: center;color: Red">Po behen ndryshime provojeni pak me vone</h1>
    <?php
    exit;
}

$products_data = array();


while ($row = mysqli_fetch_assoc($result_products)){

    //te dhenat e produktit
    $products_data['product_details'][$row['id']]['id'] = $row['id'];
    $products_data['product_details'][$row['id']]['emri_produktit'] = $row['product_name'];
    $products_data['product_details'][$row['id']]['sasia_totale_produkteve'] = $row['sasia_blere_tot'];



    //te dhenat specifike te produkteve
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['marka'] = $row['marka'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['id_markes'] = $row['id_m'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['data'] = $row['data_blerjes'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['cmimi_shitjes'] = $row['cmimi_shitur'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['cmimi_bler'] = $row['cmimi_blere'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['sasia_mallit_blere'] = $row['sasia_blere'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['sasia_shitur_mall'] = $row['sasia_shitur'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['sasia_mbetur_mall'] = $row['sasia_blere']-$row['sasia_shitur'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['cmimi_blerjeve'] = $row['cmimi_blere']*$row['sasia_blere'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['xhiro'] = $row['sasia_shitur']*$row['cmimi_shitur'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['balanca_baze_produkti'] =$products_data['product_details'][$row['id']]['details'][$row['marka']]['xhiro']-$products_data['product_details'][$row['id']]['details'][$row['marka']]['cmimi_blerjeve'];


    //te dhenat se kush i ka blere keto produkte
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['user_data'][$row['ID']]['data_blerjes_klienti'] = $row['data_blerjes'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['user_data'][$row['ID']]['emri'] = $row['first_name'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['user_data'][$row['ID']]['sasia_blere_klienti'] = $row['blere'];
    $products_data['product_details'][$row['id']]['details'][$row['marka']]['user_data'][$row['ID']]['sasia_lekeve_shpenzuar'] = $row['blere']*$row['cmimi_shitur'];

    //te dhenat e pergjithshme te produkteve
    $products_data['product_details'][$row['id']]['sasia_total_shitur'] += $products_data['product_details'][$row['id']]['details'][$row['marka']]['user_data'][$row['ID']]['sasia_blere_klienti'];
    $products_data['product_details'][$row['id']]['sasia_totale_mbetur']=$products_data['product_details'][$row['id']]['sasia_totale_produkteve']-$products_data['product_details'][$row['id']]['sasia_total_shitur'];
    $products_data['product_details'][$row['id']]['sasia_totale_lekeve_xhiro'] += $products_data['product_details'][$row['id']]['details'][$row['marka']]['user_data'][$row['ID']]['sasia_lekeve_shpenzuar'];
    $products_data['product_details'][$row['id']]['shpenzime'][$row['ID']]+=$products_data['product_details'][$row['id']]['details'][$row['marka']]['cmimi_blerjeve'];
    $products_data['product_details'][$row['id']]['shpenzimet_totale'] = $products_data['product_details'][$row['id']]['shpenzime'][$row['ID']];
    $products_data['product_details'][$row['id']]['balanca'] = $products_data['product_details'][$row['id']]['sasia_totale_lekeve_xhiro']-$products_data['product_details'][$row['id']]['shpenzimet_totale'];




    //Te dhenat e userave per produktet qe ka blere

    $products_data['user_details'][$row['ID']]['id_bleresit'] = $row['ID'];
    $products_data['user_details'][$row['ID']]['emri_bleresit'] = $row['first_name'];

    //te dhenat per produktet qe ka blere useri perkates
    $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['emri_produktit'] = $row['product_name'];
    $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['totali_blere'] += $row['blere'];
    $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['totali_kosto_blere'] += $row['blere'] * $row['cmimi_shitur'];
    $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['id_tabel'] = $row['id_b'];
    $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['mesatarja/produkt'] = $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['totali_kosto_blere']/$products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['totali_blere'];
    $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['marka'][$row['marka']]['marka'] = $row['marka'];
    $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['marka'][$row['marka']]['id_efundit'] = $row['id_b'];
    $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['marka'][$row['marka']]['sasia_blere'] += $row['blere'];
    $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['marka'][$row['marka']]['cmimi/cope'] = $row['cmimi_shitur'];
    $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['marka'][$row['marka']]['kosto'] = $row['cmimi_shitur'] * $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['marka'][$row['marka']]['sasia_blere'];
    $products_data['user_details'][$row['ID']]['produkti'][$row['product_name']]['marka'][$row['marka']]['data'][$row['data_blerjes']]['data'] = $row['data_blerjes'];

    //Te dhenat e userave per produktet qe ka blere
    $products_data['user_details'][$row['ID']]['totali_sasi_blere'] += $row['blere'];
    $products_data['user_details'][$row['ID']]['kosto_totale'] += $row['blere']*$row['cmimi_shitur'];
    $products_data['user_details'][$row['ID']]['mesatarja'] = $products_data['user_details'][$row['ID']]['kosto_totale']/$products_data['user_details'][$row['ID']]['totali_sasi_blere'];





}

//printArray($products_data);