<?php

include("../db/DbAccess.php");
$db = new DbAccess();

$result = $db->brandWiseTurnover();

$table = '';
$export_csv_btn = '';

$from_date = $result['date_range']['from_date'];
$to_date = $result['date_range']['to_date'];

if(isset($result['data'])){
    if(count($result['data']) > 0){
        //creating the html table in PHP separately to append below
        $table .= '<table class="table table-striped" style=\'font-size:13px;white-space:nowrap;\' name="result_data">
            <thead>
            <tr>
                <th>BRAND NAME</th>';

        $x = 0;
        $dayWiseTotTurnAround = array();
        foreach($result['dates'] as $date){
            $dayWiseTotTurnAround[$x] = 0;
            $table .= '<th>'.date('d-M, Y',strtotime($date)).'</th>';
            $x++;
        }
        $table .= '<th>TOTAL</th>
            </tr>
            </thead>
            <tbody>';

        foreach($result['data'] as $brand){
            $brand_name = $brand['name'];

            $table .= '<tr>
                <td>'.$brand_name.'</td>';

            $a = 0;
            $total_brand_turnover = 0;
            foreach($brand['gmv'] as $gmv) {
                $dayWiseTotTurnAround[$a] += $gmv['turnoverWOTax'];
                $total_brand_turnover += $gmv['turnoverWOTax'];

                $table .= '<td align="right">'.number_format($gmv['turnoverWOTax'],2).'</td>';
                $a++;
            }

            $table .= '<th style="text-align:right;">'.number_format($total_brand_turnover,2).'</th>
            </tr>';

        }
        $table .= '<tr>
                <th>TOTAL</th>';

        $overall_total = 0;

        foreach($dayWiseTotTurnAround as $dwtot) {
            $overall_total += $dwtot;

            $table .= '<th style="text-align:right;">' . number_format($dwtot, 2) . '</th>';
        }
        $table .= '<th style="text-align:right;">'.number_format($overall_total,2).'</th>
            </tr>
            </tbody>
        </table>';
        $export_csv_btn = '<form action="../lib/phptocsv/export_csv.php" method="post">
                                <button class="btn btn-info btn-sm" type="submit" >Export CSV</button>
                                <textarea style="display: none;" name="table_data" >' .$table.'</textarea>
                           </form>';
    }else{
        $table = '<div class="alert alert-warning" role="alert">Not Available</div>';
    }
}else{
    $table = '<div class="alert alert-warning" role="alert">Not Available</div>';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>ASSESMENT</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="../lib/bootstrap/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body style="margin:25px;">

<br>

    <div class="jumbotron text-left">
        <h5>Brandwise Turnover Report</h5>
        <p><?= date('d-M, Y',strtotime($from_date)) ?> to <?= date('d-M, Y',strtotime($to_date)) ?> </p>
        <?= $export_csv_btn ?>
    </div>

    <?= $table ?>


</body></html>
