<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

if (isset($_REQUEST["csv"])) {
    $csv = $_REQUEST["csv"]; //receive data request
    $ext = explode(".", basename($csv));
    $ext = end($ext); //url file extension
    $allowedext = ["csv", "CSV"];

    //function to check url is valid
    function urlExists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode >= 200 && $httpcode < 300) {
            return true;
        } else {
            return false;
        }
    }

    if (!empty($csv) && in_array($ext, $allowedext)) {
        if (urlExists($csv)) {
            if ($file = fopen($csv, "r")) {
                $key = fgetcsv($file, "0", ",");
                //array_push($key,"jsonid");
                $data = [];
                //$jsonid = 0;
                while ($row = fgetcsv($file, "0", ",")) {
                    // $jsonid++;
                    //array_push($row, "$jsonid");
                    $data[] = array_combine($key, $row);
                }
                fclose($file);

                //send json data if successful
                http_response_code(200);
                echo json_encode($data);
            } else {
                //send error
                http_response_code(503);
                echo json_encode(["message" => "Error Occured."]);
            }
        } else {
            //send error if url is invalid
            http_response_code(400);
            echo json_encode(["message" => "Invalid url."]);

        }
    } else {
        //send error if file extension is not csv format
        http_response_code(400);
        echo json_encode(["message" => "Invalid csv file."]);
    }
} else {
    //send error if no data was inputted
    http_response_code(400);
    echo json_encode(["message" => "No data input."]);
}