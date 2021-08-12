<!DOCTYPE html>
<html lang="en">
<head>
    <title>ASSESMENT</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="lib/bootstrap/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body style="margin:25px;">
<div class="pricing-header p-3 pb-md-4 mx-auto text-center">
    <h1 class="display-4 fw-normal">Otrium</h1>
</div>
<div class="row">
    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
        <div class="col-md-2"></div>
        <div class="col-md-4">
            <div class="card rounded-3 shadow-sm">
                <form action="reports/turnover_per_brand.php" method="post" >
                    <div class="card-header py-3">
                        <h4 class="my-0 fw-normal">Daily Turnover Report</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><small class="text-muted fw-light">Brand Wise</small></h1>
                        <ul class="list-unstyled mt-3 mb-4" align="left">
                            <li>Select From Date</li>
                            <li>
                                <input type="date" class="w-50 form-control" value="2018-05-01" id="from_date" name="from_date" required>
                            </li>
                            <li>Select to Date</li>
                            <li>
                                <input type="date" class="w-50 form-control" value="2018-05-07" id="to_date" name="to_date" required>
                            </li>
                        </ul>
                        <button type="submit" class="btn btn-lg btn-outline-primary">SEARCH</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card rounded-3 shadow-sm">
                <form action="reports/turnover_per_day.php" method="post" >
                    <div class="card-header py-3">
                        <h4 class="my-0 fw-normal">Daily Turnover Report</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><small class="text-muted fw-light">Overall</small></h1>
                        <ul class="list-unstyled mt-3 mb-4" align="left">
                            <li>Select From Date</li>
                            <li>
                                <input type="date" class="w-50 form-control" value="2018-05-01" id="from_date" name="from_date" required>
                            </li>
                            <li>Select to Date</li>
                            <li>
                                <input type="date" class="w-50 form-control" value="2018-05-07" id="to_date" name="to_date" required>
                            </li>
                        </ul>
                        <button type="submit" class="btn btn-lg btn-outline-primary">SEARCH</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>