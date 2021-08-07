<?php
require '../query_models/DBQueries.php';
require '../phptocsv/simple_html_dom.php';

$queries = new DBQueries();

$from_date = date('Y-m-d',strtotime('2018-05-01'));
$to_date = date('Y-m-d',strtotime('2018-05-07'));
if(isset($_GET['from_date'])){
    $from_date = date('Y-m-d',strtotime($_GET['from_date']));
    $to_date = date('Y-m-d',strtotime($_GET['to_date']));
}

$result = $queries->brandWiseTurnover($from_date,$to_date);

$table = 'abc';

if(isset($result['data'])){
    if(count($result['data']) > 0){

        $table = '<table border="1" style=\'font-size:13px;\'>
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
            </tr>';

            foreach($result['data'] as $brand){
                $brand_name = $brand['name'];

                $table .= '<tr>
                <th>'.$brand_name.'</th>';

                $a = 0;
                $total_brand_turnover = 0;
                foreach($brand['gmv'] as $gmv) {
                    $dayWiseTotTurnAround[$a] += $gmv['turnoverWOTax'];
                    $total_brand_turnover += $gmv['turnoverWOTax'];

                    $table .= '<th>'.number_format($gmv['turnoverWOTax'],2).'</th>';
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
        </table>';

        $html = str_get_html($table);
        header('Content-type: application/ms-excel');
        header('Content-Disposition: attachment; filename=brandwise-turnover-report.csv');

        $fp = fopen("php://output", "w");

        foreach($html->find('tr') as $element)
        {
            $th = array();
            foreach( $element->find('th') as $row)
            {
                $th [] = $row->plaintext;
            }

            $td = array();
            foreach( $element->find('td') as $row)
            {
                $td [] = $row->plaintext;
            }
            !empty($th) ? fputcsv($fp, $th) : fputcsv($fp, $td);
        }

        fclose($fp);


    }else{
        $table = '<div class="alert alert-warning" role="alert">Not Available</div>';
    }
}else{
    $table = '<div class="alert alert-warning" role="alert">Not Available</div>';
}
?>



