<?php
session_start();
if ($_SESSION['Roli'] != 'admin') {
    header('Location:' . 'http://localhost/Projekti/' . 'index.php');
}

require_once "db.php";
require_once "back.php";
//require_once "functions.php";
?>
<br><br><br>
<link rel="stylesheet" href="paga/style.css">
<div class="table-responsive mx-3">
    <table class="table " id="customers">
        <thead class="bottomBorder">
        <tr>
            <th scope="col" colspan="2" style="text-align: center; padding-bottom: 50px;" rowspan="2">Emer Mbiemer</th>
            <th scope="col" colspan="3" style="text-align: center">OretIN</th>
            <th scope="col" colspan="3" style="text-align: center">OretOUT</th>
            <th scope="col" colspan="3" style="text-align: center">Oret Totale</th>
            <th scope="col" colspan="4" style="text-align: center">PagesaIN</th>
            <th scope="col" colspan="4" style="text-align: center">PagesaOUT</th>
            <th scope="col" style="text-align: center; padding-bottom: 50px;" rowspan="2">Total Pagesa</th>
        </tr>

        <tr style="background-color: orange">
            <td>Oret Dite Jave</td>
            <td>Oret Fundjave</td>
            <td>Oret Ditepushimi</td>
            <td>Oret Dite Jave</td>
            <td>Oret Fundjave</td>
            <td>Oret Ditepushimi</td>
            <td>Totali In</td>
            <td>Totali Out</td>
            <td>Totali</td>
            <td>Paga Dite Jave</td>
            <td>Paga Fundjave</td>
            <td>Paga Ditepushimi</td>
            <td>Totali</td>
            <td>Paga Dite Jave</td>
            <td>Paga Fundjave</td>
            <td>Paga Ditepushimi</td>
            <td>Totali</td>

        </tr>
        </thead>
        <tbody>


        <?php
        $n = 0;
        $totali_user_in = 0;
        $totali_user_out = 0;
        $totali_user_pagaIn = 0;
        $totali_user_pagaOut = 0;
        $totali_in_hours = 0;


        foreach ($user_data as $key => $row) {
            //Totali i seciles kolone
            $totali_user_in_j += $row['oretInJave'];
            $totali_user_in_p += $row['oretInDiteP'];
            $totali_user_out_j += $row['oretOutJave'];
            $totali_user_out_p += $row['oretOutDiteP'];
            $totali_user_in_s += $row['oretInDiteS'];
            $totali_user_out_s += $row['oretOutDiteS'];
            $totali_user_in += $row['oretInTot'];
            $totali_user_out += $row['oretOutTot'];
            $totali_user = $totali_user_in + $totali_user_out;
            $totali_user_pagaIn_j += $row['PagesaInDiteJ'];
            $totali_user_pagaIn_p += $row['PagesaInDiteP'];
            $totali_user_pagaIn_s += $row['PagesaINDiteS'];
            $totali_user_pagaOut_s += $row['PagesaOutDiteS'];
            $totali_user_pagaOut_j += $row['PagesaOutDiteJ'];
            $totali_user_pagaOut_p += $row['PagesaOutDiteP'];
            $totali_user_pagaIn += $row['PagesaInTotale'];
            $totali_user_pagaOut += $row['PagesaOutTotale'];
            $totali_user_pagaTotale = $totali_user_pagaOut + $totali_user_pagaIn;
            $totali_in_hours += $row['oretInJave'];
            $n++; ?>
            <tr>
                <td>
                    <button type="button" id="plusi_<?= $row['id'] ?>" onclick="toggleTable('<?= $row['id'] ?>')"
                            class="btn btn-plus"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </td>
                <!--                    Mbushim tabelen me te dhenat perkatese -->
                <td><?= $row['emri'] ?></td>
                <td><?= $row['oretInJave'] ?> or??</td>
                <td><?= $row['oretInDiteP'] ?> or??</td>
                <td><?= $row['oretInDiteS'] ?> or??</td>
                <td><?= $row['oretOutJave'] ?> or??</td>
                <td><?= $row['oretOutDiteP'] ?> or??</td>
                <td><?= $row['oretOutDiteS'] ?> or??</td>
                <td><?= $row['oretInTot'] ?> or??</td>
                <td><?= $row['oretOutTot'] ?> or??</td>
                <td><?= $row['oretInDiteP'] + $row['oretInDiteS'] + $row['oretInJave'] ?> ore</td>
                <td><?= $row['PagesaInDiteJ'] ?> lek??</td>
                <td><?= $row['PagesaInDiteP'] ?> lek??</td>
                <td><?= $row['PagesaINDiteS'] ?> lek??</td>
                <td><?= $row['PagesaInTotale'] ?> lek??</td>
                <td><?= $row['PagesaOutDiteJ'] ?> lek??</td>
                <td><?= $row['PagesaOutDiteP'] ?> lek??</td>
                <td><?= $row['PagesaOutDiteS'] ?> lek??</td>
                <td><?= $row['PagesaOutDiteJ'] + $row['PagesaOutDiteP'] + $row['PagesaOutDiteS'] ?> lek??</td>

                <td><?= $row['PagesaInTotale'] + $row['PagesaOutTotale'] ?> lek??</td>

            </tr>
            <td colspan="20" class="hide_row" id='tabela_<?= $row['id'] ?>'>

                <div class="col-18">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th>Nr</th>
                            <th>Dite</th>
                            <th>Data</th>
                            <th>OretIN</th>
                            <th>OretOUT</th>
                            <th>OretTotali</th>
                            <th>PagesaIN</th>
                            <th>PagesaOUT</th>
                            <th>Total Pagesa</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $k = 0;
                        foreach ($row['Details'] as $key2 => $row2){
                        $k++; ?>
                        <tr>
                            <td> <?= $k; ?></td>
                            <td><?= $row2['lloji'] ?></td>
                            <td> <?= $row2['single_date'] ?>   </td>
                            <td> <?= $row2['oretIn'] ?> or??</td>
                            <td> <?= $row2['oretOut'] ?> or??</td>
                            <td><?= $row2['oretIn'] + $row2['oretOut'] ?> or??</td>
                            <td> <?= $row2['PagesaIn'] ?> lek??</td>
                            <td> <?= $row2['PagesaOut'] ?> lek??</td>
                            <td> <?= $row2['PagesaIn'] + $row2['PagesaOut'] ?> lek??</td>
                        </tr>
                        </tbody>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </td>
            <?php
        } ?>
        </tbody>
        <tfoot>
        <tr style="background-color: lightsalmon">
            <!--                Mbushim nen tableen me te dhenat perkates-->
            <td>Totali</td>
            <td>Nr(<?= $n ?>)</td>
            <td><?= $totali_user_in_j ?> or??</td>
            <td><?= $totali_user_in_p ?> or??</td>
            <td><?= $totali_user_in_s ?> or??</td>
            <td><?= $totali_user_out_j ?> or??</td>
            <td><?= $totali_user_out_p ?> or??</td>
            <td><?= $totali_user_out_s ?> or??</td>
            <td><?= $totali_user_in ?> or??</td>
            <td><?= $totali_user_out ?> or??</td>
            <td><?= $totali_user_out + $totali_user_in ?> or??</td>
            <td><?= $totali_user_pagaIn_j ?> lek??</td>
            <td><?= $totali_user_pagaIn_p ?> lek??</td>
            <td><?= $totali_user_pagaIn_s ?> lek??</td>
            <td><?= $totali_user_pagaIn ?> lek??</td>
            <td><?= $totali_user_pagaOut_j ?> lek??</td>
            <td><?= $totali_user_pagaOut_p ?> lek??</td>
            <td><?= $totali_user_pagaOut_s ?> lek??</td>
            <td><?= $totali_user_pagaOut_j + $totali_user_pagaOut_p ?> lek??</td>
            <td><?= $totali_user_pagaTotale ?> lek??</td>

        </tr>
        </tfoot>
    </table>
</div>



