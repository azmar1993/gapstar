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
        $html = str_get_html($table);
        header('Content-type: application/ms-excel');
        header('Content-Disposition: attachment; filename=turnover-report-per-day.csv');

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