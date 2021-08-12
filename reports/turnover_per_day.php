<?php

include("../db/DbAccess.php");
$db = new DbAccess();

$result = $db->dayWiseTurnover();

$table = '';
$export_csv_btn = '';

$from_date = $result['date_range']['from_date'];
$to_date = $result['date_range']['to_date'];

if(isset($result['data'])){
    if(count($result['data']) > 0){
        //creating the html table in PHP separately to append below
        $table .= '<table class="table table-striped" style=\'font-size:13px;white-space:nowrap;\'>
                    <tr>
                        <th>DATE</th>
                        <th style="text-align:right;">TOTAL TURNOVER</th>
                    </tr>';

                    $total_turnover = 0;
                    foreach($result['data'] as $day){
                        $total_turnover += $day['turnoverWOTax'];
                        $table .= '<tr>
                            <td>'.date('d-M, Y',strtotime($day['date'])).'</td>
                            <td align="right">'.number_format($day['turnoverWOTax'],2).'</td>
                        </tr>';
                    }

        $table .= '<tr>
                        <th>TOTAL</th>
                        <th  style="text-align:right;">'.number_format($total_turnover,2).'</th>
                    </tr>
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
    <div class="jumbotron text-left">
        <h5>Overall Turnover Report - Per Day</h5>
        <p><?= date('d-M, Y',strtotime($from_date)) ?> to <?= date('d-M, Y',strtotime($to_date)) ?> </p>
        <?= $export_csv_btn ?>
    </div>
    <div class="row">
        <div class="col-md-5">
            <?= $table ?>
        </div>
    </div>
</body>
</html>
