<?php

class EnricoQuery{
    
    private $json;
    private $QueryResults;
    
    public function __construct($url){
        
        $response = file_get_contents($url);
        $this->json = json_decode($response, true);
        
        $this->QueryResults=array();
        
        foreach($this->json['adverts'] as $item) { 
            
            if ($item['phoneNumbers'] != Null)
                $phone = $item['phoneNumbers'][0]['phoneNumber']; 
            else $phone = "";
            
            if ($item['location']['coordinates'] != Null){
                $latitude = $item['location']['coordinates'][0]['latitude'];
                $longitude = $item['location']['coordinates'][0]['longitude'];
                    }
            else
                $latitude = $longitude ="" ;
                
            if ($item['companyInfo']['companyText'] != Null)
                $companytext = $item['companyInfo']['companyText']; 
            else $companytext = "";
            
            
                
            $eniro_search_result =  array(
                                    
                                    'eniroId'  => $item['eniroId'],
                                    'companyName' => $item['companyInfo']['companyName'],
                                    'orgNumber' => $item['companyInfo']['orgNumber'],
                                    'companyText' => $companytext,
                                    'postBox' => $item['address']['postBox'],
                                    'streetName' => $item['address']['streetName'],
                                    'postCode' => $item['address']['postCode'],
                                    'postArea' => $item['address']['postArea'],
                                    'phoneNumber' => $phone,
                                    'homepage' => $item['homepage'],
                                    'facebook' => $item['facebook'],
                                    'infoPageLink' => $item['infoPageLink'],
                                    'latitude' => $latitude,
                                    'longitude' => $longitude,
                                    );
            
            $this->QueryResults[]=  $eniro_search_result  ;    
            
            }
        
        }
    
    public function hits(){
        return $this->json["totalHits"];
    }
    
    public function res_row($i){
        return $this->QueryResults[$i];
    }
}

?>