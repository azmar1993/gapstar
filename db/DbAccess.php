<?php

include("DbConnection.php");

class DbAccess
{

    private $dbConnection; //reference for db connection
    private $tax = 21;//VAT Percentage

    public function dbConnect()
    {
        $dbCon = new DbConnection();
        return $this->dbConnection = $dbCon->openConnection();
    }

    public function brandWiseTurnover()
    {

        $from_date = date('Y-m-d', strtotime('2018-05-01'));
        $to_date = date('Y-m-d', strtotime('2018-05-07'));
        if (isset($_POST['from_date'])) {
            $from_date = date('Y-m-d', strtotime($_POST['from_date']));
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
        }

        $returnArray = array();

        $returnArray['date_range']['from_date'] = $from_date;
        $returnArray['date_range']['to_date'] = $to_date;

        if ($from_date <= $to_date) {

            $to_date = date('Y-m-d', strtotime($to_date . ' + 1 day'));

            $dateArray = array();

            $period = new DatePeriod(
                new DateTime("$from_date"),
                new DateInterval('P1D'),
                new DateTime("$to_date")
            );

            $d1 = 0;
            foreach ($period as $key => $value) {
                $dateArray[$d1] = $value->format('Y-m-d');
                $d1++;
            }

            $returnArray['dates'] = $dateArray;

            $sql = "SELECT * FROM brands";
            $result1 = $this->runQuery($sql);

            $r1 = 0;
            foreach($result1 as $row){
                $brand_id = $row['id'];

                $returnArray['data'][$r1]['brand_id'] = $brand_id;
                $returnArray['data'][$r1]['name'] = $row['name'];
                $returnArray['data'][$r1]['description'] = $row['description'];
                $returnArray['data'][$r1]['products'] = $row['products'];
                $returnArray['data'][$r1]['created'] = $row['created'];

                $d2 = 0;
                foreach ($dateArray as $date) {

                    $returnArray['data'][$r1]['gmv'][$d2]['date'] = $date;

                    $sql2 = "SELECT *, ROUND( ( IFNULL(turnover ,0) /($this->tax + 100) * 100) ,2) as turnOverWOTax 
                              FROM gmv 
                              WHERE brand_id = '$brand_id'
                              AND DATE(`date`) = '$date' ";
                    $result2 = $this->runQuery($sql2);

                    $r2 = 0; $turnover = 0; $turnOverWOTax = 0;
                    foreach($result2 as $row2){
                        $turnover += $row2['turnover'];
                        $turnOverWOTax += $row2['turnOverWOTax'];
                        $r2++;
                    }
                    $returnArray['data'][$r1]['gmv'][$d2]['turnover'] = $turnover;
                    $returnArray['data'][$r1]['gmv'][$d2]['turnoverWOTax'] = $turnOverWOTax;

                    $d2++;
                }
                $r1++;
            }
        }
        return $returnArray;
    }

    function dayWiseTurnover(){

        $from_date = date('Y-m-d', strtotime('2018-05-01'));
        $to_date = date('Y-m-d', strtotime('2018-05-07'));
        if (isset($_POST['from_date'])) {
            $from_date = date('Y-m-d', strtotime($_POST['from_date']));
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
        }

        $returnArray = array();

        $returnArray['date_range']['from_date'] = $from_date;
        $returnArray['date_range']['to_date'] = $to_date;

        if($from_date <= $to_date){
            $to_date = date('Y-m-d',strtotime($to_date.' + 1 day'));

            $period = new DatePeriod(
                new DateTime("$from_date"),
                new DateInterval('P1D'),
                new DateTime("$to_date")
            );

            $d1 = 0;
            foreach ($period as $key => $value) {
                $dateArray[$d1] = $value->format('Y-m-d');
                $d1++;
            }

            $r1 = 0;
            foreach($dateArray as $date) {

                $returnArray['data'][$r1]['date'] = $date;

                $sql1 = "SELECT 
                    ROUND( ( IFNULL(turnover ,0) /($this->tax + 100) * 100) ,2) as turnOverWOTax, turnover
                    FROM brands br
                INNER JOIN gmv ON gmv.brand_id = br.id
                WHERE DATE(`date`) = '$date'";
                $result1 = $this->runQuery($sql1);

                $turnover = 0;
                $turnOverWOTax = 0;
                if (count($result1) > 0) {
                    foreach($result1 as $row) {
                        $turnover += $row['turnover'];
                        $turnOverWOTax += $row['turnOverWOTax'];
                    }
                }

                $returnArray['data'][$r1]['turnover'] = $turnover;
                $returnArray['data'][$r1]['turnoverWOTax'] = $turnOverWOTax;

                $r1++;
            }
        }

        return $returnArray;

    }

    //run queries below
    public function runQuery($sql){

        $returnArr = array();

        $con = $this->dbConnect();
        try {
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $r1 = 0;
            foreach ($stmt->fetchAll() as $k => $row) {
                $returnArr[$r1] = $row;
                $r1++;
            }
        }catch(PDOException $exception){
            echo "<pre>Query Error : ".$exception->getMessage()."</pre>";
        }

        $con = null;

        return $returnArr;

    }
}