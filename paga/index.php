<?php
session_start();
if ($_SESSION['Roli'] != 'admin') {
    header('Location:'.'http://localhost/Projekti/'.'index2.php');
}

require_once "db.php";
require_once "back.php";
//require_once "functions.php";
?>
<br><br><br>
<div class="table-responsive">
    <table class="table" border="1" id="customers">
        <thead>
        <tr>
            <th scope="col" colspan="2" style="text-align: center">Emer Mbiemer</th>
            <th scope="col" colspan="3" style="text-align: center">OretIN</th>
            <th scope="col" colspan="3" style="text-align: center">OretOUT</th>
            <th scope="col" colspan="3" style="text-align: center">Oret Totale</th>
            <th scope="col" colspan="4" style="text-align: center">PagesaIN</th>
            <th scope="col" colspan="4" style="text-align: center">PagesaOUT</th>
            <th scope="col" style="text-align: center">Total Pagesa</th>
        </tr>
        </thead>
        <tbody>

        <tr style="background-color: orange">
            <td style="background: #04AA6D" colspan="2"></td>
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
            <td style="background-color: #04AA6D;"></td>

        </tr>

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
                <td ><?= $row['emri'] ?></td>
                <td><?= $row['oretInJave'] ?> orë</td>
                <td><?= $row['oretInDiteP'] ?> orë</td>
                <td><?= $row['oretInDiteS'] ?> orë</td>
                <td><?= $row['oretOutJave'] ?> orë</td>
                <td><?= $row['oretOutDiteP'] ?> orë</td>
                <td><?= $row['oretOutDiteS'] ?> orë</td>
                <td><?= $row['oretInTot'] ?> orë</td>
                <td><?= $row['oretOutTot'] ?> orë</td>
                <td><?= $row['oretInDiteP'] + $row['oretInDiteS'] + $row['oretInJave'] ?> ore</td>
                <td><?= $row['PagesaInDiteJ'] ?> lekë</td>
                <td><?= $row['PagesaInDiteP'] ?> lekë</td>
                <td><?= $row['PagesaINDiteS'] ?> lekë</td>
                <td><?= $row['PagesaInTotale'] ?> lekë</td>
                <td><?= $row['PagesaOutDiteJ'] ?> lekë</td>
                <td><?= $row['PagesaOutDiteP'] ?> lekë</td>
                <td><?= $row['PagesaOutDiteS'] ?> lekë</td>
                <td><?= $row['PagesaOutDiteJ'] + $row['PagesaOutDiteP'] + $row['PagesaOutDiteS'] ?> lekë</td>

                <td><?= $row['PagesaInTotale'] + $row['PagesaOutTotale'] ?> lekë</td>

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
                            <td> <?= $row2['oretIn'] ?> orë</td>
                            <td> <?= $row2['oretOut'] ?> orë</td>
                            <td><?= $row2['oretIn'] + $row2['oretOut'] ?> orë</td>
                            <td> <?= $row2['PagesaIn'] ?> lekë</td>
                            <td> <?= $row2['PagesaOut'] ?> lekë</td>
                            <td> <?= $row2['PagesaIn'] + $row2['PagesaOut'] ?> lekë</td>
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
        <tfoot >
        <tr style="background-color: lightsalmon">
            <!--                Mbushim nen tableen me te dhenat perkates-->
            <td>Totali</td>
            <td>Nr(<?= $n ?>)</td>
            <td><?= $totali_user_in_j ?> orë</td>
            <td><?= $totali_user_in_p ?> orë</td>
            <td><?= $totali_user_in_s ?> orë</td>
            <td><?= $totali_user_out_j ?> orë</td>
            <td><?= $totali_user_out_p ?> orë</td>
            <td><?= $totali_user_out_s ?> orë</td>
            <td><?= $totali_user_in ?> orë</td>
            <td><?= $totali_user_out ?> orë</td>
            <td><?= $totali_user_out + $totali_user_in ?> orë</td>
            <td><?= $totali_user_pagaIn_j ?> lekë</td>
            <td><?= $totali_user_pagaIn_p ?> lekë</td>
            <td><?= $totali_user_pagaIn_s ?> lekë</td>
            <td><?= $totali_user_pagaIn ?> lekë</td>
            <td><?= $totali_user_pagaOut_j ?> lekë</td>
            <td><?= $totali_user_pagaOut_p ?> lekë</td>
            <td><?= $totali_user_pagaOut_s ?> lekë</td>
            <td><?= $totali_user_pagaOut_j + $totali_user_pagaOut_p ?> lekë</td>
            <td><?= $totali_user_pagaTotale ?> lekë</td>

        </tr>
        </tfoot>
    </table>
</div>

<?php include_once "script.php"?>


