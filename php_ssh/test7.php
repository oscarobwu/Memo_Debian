<?php

$url = 'https://172.19.4.166/mgmt/tm/ltm/virtual';

if ($argc > 1){
    $url = $argv[1];
}

$ch=curl_init();
// user credencial
curl_setopt($ch, CURLOPT_USERPWD, "admin:admin");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
curl_setopt($ch, CURLOPT_VERBOSE, true);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
curl_close($ch);
$resultArray = json_decode($result, true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Exclude App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <h1>Campaigns</h1><br>
    <input type="text" name="" id="">
    <input type="submit">
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Campaign Name</th>
                <th scope="col">CPA Goal</th>
                <th scope="col">Click Amount</th>
            </tr>
            <?php
            $today = date('Y-m-d h:i:s');
            $lastmonth = date('Y-m-d h:i:s', strtotime('-1 months', strtotime($today)));
            foreach ($resultArray['kind'] as $campaign) {
                if ($campaign['created'] > $lastmonth) {

            ?>
                    <tr>
                        <td scope="col"><?php echo $campaign['fullPath']; ?> </td>
                        <td scope="col"><?php echo $campaign['name']; ?></td>
                        <td scope="col"><?php echo $campaign['destination']; ?></td>
                        <td scope="col"><?php echo $campaign['mask']; ?></td>
                    </tr>
            <?php }
            } ?>
        </thead>
    </table>


</body>

</html>
