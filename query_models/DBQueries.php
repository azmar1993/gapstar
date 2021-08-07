<?php

class DBQueries
{

    function brandWiseTurnover($from_date,$to_date){

        $returnArray = array();

        if($from_date <= $to_date){

            $to_date = date('Y-m-d',strtotime($to_date.' + 1 day'));

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

            require '../db/db_connection.php';
            $sql = "SELECT * FROM brands";
            $result = $conn->query($sql);

            $r1 = 0;
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {

                    $brand_id = $row['id'];

                    $returnArray['data'][$r1]['brand_id'] = $brand_id;
                    $returnArray['data'][$r1]['name'] = $row['name'];
                    $returnArray['data'][$r1]['description'] = $row['description'];
                    $returnArray['data'][$r1]['products'] = $row['products'];
                    $returnArray['data'][$r1]['created'] = $row['created'];

                    $d2 = 0;
                    foreach($dateArray as $date){

                        $sql2 = "SELECT * FROM gmv 
                              WHERE brand_id = '$brand_id'
                              AND DATE(`date`) = '$date' ";
                        $result2 = $conn->query($sql2);

                        $returnArray['data'][$r1]['gmv'][$d2]['date'] = $date;

                        $r2 = 0; $turnover = 0;
                        if ($result2->num_rows > 0) {
                            while($row2 = $result2->fetch_assoc()) {

                                $turnover += $row2['turnover'];

                                $r2++;
                            }
                        }
                        $turnoverWOTax = 0;
                        if($turnover != 0){
                            $turnoverWOTax = ($turnover/121) * 100;
                        }
                        $returnArray['data'][$r1]['gmv'][$d2]['turnover'] = $turnover;
                        $returnArray['data'][$r1]['gmv'][$d2]['turnoverWOTax'] = $turnoverWOTax;

                        $d2++;
                    }

                    $r1++;
                }
            } else {
                echo "0 results";
            }
            $conn->close();

        }

        return $returnArray;

    }

    function dayWiseTurnover($from_date,$to_date){

        $returnArray = array();

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

            require '../db/db_connection.php';

            $r1 = 0;
            foreach($dateArray as $date) {

                $returnArray['data'][$r1]['date'] = $date;

                $sql = "SELECT SUM(turnover) as totalTurnover FROM brands br
                INNER JOIN gmv ON gmv.brand_id = br.id
                WHERE DATE(`date`) = '$date'";
                $result = $conn->query($sql);

                $turnover = 0;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $turnover += $row['totalTurnover'];

                    }
                }

                $turnoverWOTax = 0;
                if ($turnover != 0) {
                    $turnoverWOTax = ($turnover / 121) * 100;
                }

                $returnArray['data'][$r1]['turnover'] = $turnover;
                $returnArray['data'][$r1]['turnoverWOTax'] = $turnoverWOTax;

                $r1++;
            }
            $conn->close();
        }

        return $returnArray;

    }

    function checkLogin($user_name,$login_password){

        $retArr = array();

        $b64_org_pw = base64_encode($login_password);

        require '../db/db_connection.php';
        $sql = "SELECT * FROM users WHERE user_name = '$user_name'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            $sql2 = "SELECT * FROM users WHERE user_name = '$user_name' AND user_password_enc = '$b64_org_pw' ";
            $result2 = $conn->query($sql2);

            if ($result2->num_rows > 0) {

                $retArr['res'] = '0';
                $retArr['desc'] = 'Successfully Logged In';

                $_SESSION["loggedIn"] = '1';
                $_SESSION["user"] = "$user_name";

            }else{
                $retArr['res'] = '2';
                $retArr['desc'] = 'Wrong Password';
            }

        }else{
            $retArr['res'] = '1';
            $retArr['desc'] = 'User Name is Not Available';
        }

        $conn->close();

        return $retArr;
    }

}