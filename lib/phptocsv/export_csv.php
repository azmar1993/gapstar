<?php

include("simple_html_dom.php");

$table = $_POST['table_data'];
$table = str_replace("<td","<th",$table);
$table = str_replace("</td","</th",$table);

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
}
fclose($fp);