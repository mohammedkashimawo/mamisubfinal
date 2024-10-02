
<?php

class RechargePin extends ApiAccess{
    

     //Purchase Data
    public function purchaseRechargePin($body,$networkDetails,$planid,$networkk){

        $response = array();
        $details=$this->model->getApiDetails();
        
        $thenetworkId = $networkk;
        //Get Api Key Details
       $host = self::getConfigValue($details,"rechargePinProvider");
      $apiKey = self::getConfigValue($details,"rechargePinApi");
       
        //Check If API Is Is Using N3TData Or Bilalsubs
        if(strpos($host, 'n3tdata247') !== false){
            $hostuserurl="https://n3tdata247.com/api/user/";
            return $this->purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey,$thenetworkId,$actualPlanId,$planid);
        }

        if(strpos($host, 'bilalsaudasub') !== false){
            $hostuserurl="https://bilalsadasub.com/api/user/";
            return $this->purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey,$thenetworkId,$actualPlanId,$planid);
        }

        if(strpos($host, 'beensade') !== false){
            $hostuserurl="https://beensadeprint.com/api/user/";
            return $this->purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey,$thenetworkId,$actualPlanId);
        }
        
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
            "network": "'.$thenetworkId.'",
            
            "network_amount" : "'.$planid.'",
            "quantity": "'.$body->quantity.'", 
             
            "name_on_card": "'.$body->name_on_card.'"
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
        

        if($result->Status=='successful' || $result->status=='processing'){
            $response["Status"] = "successful";
            $response["status"] = "success";
            $response["load_pin"] = $result->data_pin[0]->fields->load_code;
            $response["quantity"] = $result->quantity;
            $response["serial"] = $result->data_pin[0]->fields->serial;
            $response["network"] = $result->network_name;
            $response["pin"] = $result->data_pin[0]->fields->pin;
        }
        elseif($result->status=='fail'){
        
            
            $response["status"] = "fail";
            $response["msg"] = "Network Error, Please Try Again Later";
        }
        else{
            
            
            $response["status"] = "fail";
            $response["msg"] = "Server/Network Error: ".$result->message;
            file_put_contents("data_error_log.txt",json_encode($result));
        }

        return $response;
    }

    //Purchase Data
    public function purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey,$thenetworkId,$actualPlanId,$planid){

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
                "Authorization: Token $apiKey",
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
            "network": "'.$thenetworkId.'",
            "card_name": "'.$body->business.'",
            "plan_type" : "'.$planid.'",
            "quantity": "'.$body->quantity.'"
            
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
            $response["load_pin"] = $result->load_pin ;
            $response["quantity"] = $result->quantity;
            $response["serial"] = $result->serial;
            $response["network"] = $result->network;
            $response["pin"] = $result->pin;
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