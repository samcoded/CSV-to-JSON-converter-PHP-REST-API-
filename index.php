<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");


    if (isset($_REQUEST["csv"])) {
    $csv = $_REQUEST["csv"];

    $ext = explode('.', basename($csv));
    $ext = end($ext);
    $allowedext = array("csv","CSV");

    function urlExists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch); 
        if($httpcode>=200 && $httpcode<300){
            return true;
        } else {
            return false;
        }
    }

    if (!empty($csv) && in_array($ext, $allowedext)){

       if(urlExists($csv)){
   if ($file = fopen($csv, "r")) {
       $key = fgetcsv($file, "0",",");
       //array_push($key,"jsonid");
       $data = array();
       //$jsonid = 0;
       while ($row = fgetcsv($file, "0",",")) {
          // $jsonid++;
           //array_push($row, "$jsonid");
           $data[] = array_combine($key,$row);
       }
       fclose($file); 
        http_response_code(200);
        echo json_encode($data);
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Error Occured."));
    }
}else {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid url."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Invalid csv file."));
}
}
else {
    http_response_code(400);
    echo json_encode(array("message" => "No data input."));
}
    ?>