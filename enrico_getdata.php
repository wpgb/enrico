<?php
//Fetching company info through Eniro API

function get_eniroinfo_single_post ($object){
    
    $eniroID =  get_post_meta($object->ID, "enrico-eniroId", true);
    $eniroGeoArea =  get_post_meta($object->ID, "enrico-postArea", true);
    $eniroSearchWord =  get_the_title ($object);
    
    $api_profile=get_option('enrico_api_profile');
    $api_key=get_option('enrico_api_key');
    $api_version =get_option('enrico_api_version');;
    
    $eniro_search_result=[];

//Primarily use the eniro ID in the query -if exists
    if ($eniroID !="")
        $url = 'http://api.eniro.com/cs/search/basic?profile='.$api_profile.'&key='.$api_key.'&country=se&version='.$api_version.'&eniro_id='.$eniroID;

//Otherwise use the Searchword(if exists)  and GeoArea (optional) in the query -if exists
    
    elseif ($eniroSearchWord !="")
        $url = 'http://api.eniro.com/cs/search/basic?profile='.$api_profile.'&key='.$api_key.'&country=se&version='.$api_version.'&geo_area='.$eniroGeoArea.'&search_word='.$eniroSearchWord;
    else return $eniro_search_result;
    
    $response = file_get_contents($url);
    $json = json_decode($response, true);
    
    $hits = $json["totalHits"];
    
//    if ($hits ==0)
//        return array(0, "");
        
    if ($hits != 1){
        echo "NO unique hit!";
        return $eniro_search_result;
    }
    
    
    //Debugging:
    //echo "<p>".$eniroID.$eniroSearchWord. $eniroGeoArea."<br>Hits: ".$json["totalHits"]."</p>";    

        foreach($json['adverts'] as $item) { 
            
            if ($item['phoneNumbers'] != Null)
                $phone = $item['phoneNumbers'][0]['phoneNumber']; 
            else $phone = "";
            
            if ($item['location']['coordinates'] != Null){
                $latitude = $item['location']['coordinates'][0]['latitude'];
                $longitude = $item['location']['coordinates'][0]['longitude'];
                    }
            else
                $latitude = $longitude ="" ;
            
            
                
            $eniro_search_result =  array(
                                    
                                    'eniroId'  => $item['eniroId'],
                                    'companyName' => $item['companyInfo']['companyName'],
                                    'orgNumber' => $item['companyInfo']['orgNumber'],
                                    'companyText' => $item['companyInfo']['companyText'],
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
            
            
        
        
        }
   
    
    return $eniro_search_result;
    }
  
  
?>
