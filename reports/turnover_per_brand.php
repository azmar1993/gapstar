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

$result = $queries->brandWiseTurnover($from_date,$to_date);

$table = '';
$export_csv_btn = '';
if(isset($result['data'])){
    if(count($result['data']) > 0){
        //creating the html table in PHP separately to append below
        $table .= '<table class="table table-striped" style=\'font-size:13px;white-space:nowrap;\'>
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

        $export_csv_btn = '<a class="btn btn-info btn-sm" href="../reports/turnover_per_brand_csv.php?from_date='.$from_date.'&to_date='.$to_date.'" >Export CSV</a>';
    }else{
        $table = '<div class="alert alert-warning" role="alert">Not Available</div>';
    }
}else{
    $table = '<div class="alert alert-warning" role="alert">Not Available</div>';
}
?>
<a href="<?= $signoutUrl ?>" class="btn btn-warning btn-sm" style="float: right;">Logout</a>
<a href="../page/home.php" class="btn btn-info btn-sm" style="float: right;">BACK</a>
<br>
<div class="jumbotron text-left">
    <h5>Brandwise Turnover Report</h5>
    <p><?= date('d-M, Y',strtotime($from_date)) ?> to <?= date('d-M, Y',strtotime($to_date)) ?> </p>
    <?= $export_csv_btn ?>
</div>

<?= $table ?>

</body></html>
