
<?php

class bulksms extends ApiAccess{
    

     //Purchase Data
    public function purchasebulksms($body){

        $response = array();
        $details=$this->model->getApiDetails();
        
        //Get Api Key Details
        $hostr = self::getConfigValue($details,"rechargePinProvider");
        $apiKeyr = self::getConfigValue($details,"rechargePinApi");
        $hostre = "https://danvtu.com.ng/mobile/cardapi.php" ;
        $host = "https://husmodataapi.com/api/bulk/";
        $apiKey = "fd07fbe08c491665d00486a31ad07137f73bd750";

        //Check If API Is Is Using N3TData Or Bilalsubs
        if(strpos($host, 'n3tdata247') !== false){
            $hostuserurl="https://n3tdata247.com/api/user/";
            return $this->purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey);
        }

        if(strpos($host, 'bilalsadasub') !== false){
            $hostuserurl="https://bilalsadasub.com/api/user/";
            return $this->purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey);
        }

        if(strpos($host, 'beensade') !== false){
            $hostuserurl="https://beensadeprint.com/api/user/";
            return $this->purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey);
        }
        
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $host,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "sender":"'.$body->sender. '",
            "message": "'.$body->message.'", 
            "number" :  "'.$body->phone.'"
            
        }',
        
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Token $apiKey"
        ),
        ));

        $exereq = curl_exec($curl);
        $err = curl_error($curl);
        
        if($err){
            $response["status"] = "fail";
            $response["msg"] = "Server Connection Error"; //.$err;
            file_put_contents("data_error_log2.txt",json_encode($response)." ".$err." ".$host);
            curl_close($curl);
            return $response;
        }

        $result=json_decode($exereq);
        curl_close($curl);
        

        if($result->status=='success' || $result->status=='processing'){
            
            

$response["status"] = "success";
$response["amount"] = $result->amount;
$response["total_number"] = $result->total_number;
$response["total_correct_number"] = $result->total_correct_number;
$response["total_wrong_number"] = $result->total_wrong_number;
$response["message"] = $result->message;
$response["sender_name"] = $result->sender_name;
$response["numbers"] = $result->numbers;


        }
        elseif($result->Status=='failed'){
        
            
            $response["status"] = "fail";
            $response["msg"] = "Network Error, Please Try Again Later";
        }
        else{
            
            
            $response["status"] = "fail";
            $response["msg"] = "Server/Network Error: ".$result->msg;
            file_put_contents("data_error_log.txt",json_encode($result));
        }

        return $response;
    }

    //Purchase Data
    public function purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey){

        $response = array();
        
        // ------------------------------------------
        //  Get User Access Token
        // ------------------------------------------
        
        
        $curlA = curl_init();
        curl_setopt_array($curlA, array(
            CURLOPT_URL => $hostuserurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => 1,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic $apiKey",
            ),
        ));
    
        $exereqA = curl_exec($curlA);
        $err = curl_error($curlA);
        
        if($err){
            $response["status"] = "fail";
            $response["msg"] = "Server Connection Error"; //.$err;
            curl_close($curlA);
            return $response;
        }
        $resultA=json_decode($exereqA);
        $apiKey=$resultA->AccessToken;
        curl_close($curlA);
    
        
        // ------------------------------------------
        //  Purchase Data
        // ------------------------------------------
    
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $host,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "sender":"'.$body->sender. '",
            "message": "'.$body->message.'", 
            "phonenumber" :  "'.$body->phone.'"
            
        }',
        
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Token $apiKey"
        ),
        ));

        $exereq = curl_exec($curl);
        $err = curl_error($curl);
        
        if($err){
            $response["status"] = "fail";
            $response["msg"] = "Server Connection Error"; //.$err;
            file_put_contents("basic_error_log2.txt",json_encode($response));
            curl_close($curl);
            return $response;
        }

        $result=json_decode($exereq);
        curl_close($curl);
        
        if($result->status=='successful' || $result->status=='success'){
            $response["status"] = "success";
$response["amount"] = $result->amount;
$response["total_number"] = $result->total_number;
$response["total_correct_number"] = $result->total_correct_number;
$response["total_wrong_number"] = $result->total_wrong_number;
$response["message"] = $result->message;
$response["sender_name"] = $result->sender_name;
$response["numbers"] = $result->numbers;

        }
        elseif($result->status=='fail'){
            $response["status"] = "fail";
            $response["msg"] = "Network Error, Please Try Again Later";
             file_put_contents("rechargepin_error_log.txt",json_encode($result));
        }
        else{
            $response["status"] = "fail";
            $response["msg"] = "Servcer Error: ".$result->message;
            file_put_contents("basic_data_error_log.txt",json_encode($result));
        }

        return $response;
    }
    

}

?>