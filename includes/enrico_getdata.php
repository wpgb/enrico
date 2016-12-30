<?php
//Fetching company info through Eniro API

function get_eniroinfo_single_ID ($object){
    
    
    $eniroID =  get_post_meta($object->ID, "enrico-eniroId", true);
    
    if ($eniroID !=""){ //Check that Eniro-Id is provided- else don't execute query
        
        //Required Parameters for the query-url
        $country_code=   get_post_meta($object->ID, "enrico-countrycode", true);

        $api_profile=get_option('enrico_api_profile');
        $api_key=get_option('enrico_api_key');
        $api_version =get_option('enrico_api_version');
    
    
        $url = 'http://api.eniro.com/cs/search/basic?profile='.
                    
                    $api_profile.'&key='.$api_key.'&country='.$country_code.
                        
                        '&version='.$api_version.'&eniro_id='.$eniroID;
        
        $eniro_search_result=new Enrico_Query($url);
        
        if ($eniro_search_result->hits() != 1){  //Check that query has exactly 1 hit - else abort
            return NULL;
            }
        
        return $eniro_search_result;
            }
    
    else {
        return NULL;
    }
}
    
?>
