<?php

require '../headers/header.php';

if(isset($_SESSION['loggedIn'])){
    if($_SESSION['loggedIn'] != '1'){
        header("Location: ../page/login.php");
    }
}else{
    header("Location: ../page/login.php");
}
$signoutUrl = "../page/signout.php";

require '../query_models/DBQueries.php';

$queries = new DBQueries();

$from_date = date('Y-m-d',strtotime('2018-05-01'));
$to_date = date('Y-m-d',strtotime('2018-05-07'));
if(isset($_POST['from_date'])){
    $from_date = date('Y-m-d',strtotime($_POST['from_date']));
    $to_date = date('Y-m-d',strtotime($_POST['to_date']));
}

$result = $queries->dayWiseTurnover($from_date,$to_date);

$table = '';
$export_csv_btn = '';
if(isset($result['data'])){
    if(count($result['data']) > 0){

        $table .= '<table class="table table-striped" style=\'font-size:13px;\'>
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
        $export_csv_btn = '<a class="btn btn-info btn-sm" href="../reports/turnover_per_day_csv.php?from_date='.$from_date.'&to_date='.$to_date.'" >Export CSV</a>';
    }else{
        $table = '<div class="alert alert-warning" role="alert">Not Available</div>';
    }
}else{
    $table = '<div class="alert alert-warning" role="alert">Not Available</div>';
}
?>
<a href="<?= $signoutUrl ?>" class="btn btn-warning btn-sm" style="float: right;">Logout</a>
<a href="../page/home.php" class="btn btn-info btn-sm" style="float: right;">BACK</a>
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
</body></html>
