<?php

class Enrico_Query{
    
    private $json;
    private $QueryResults;
    private $CountryCode;
    
    public function __construct($url){
        
        parse_str(parse_url($url, PHP_URL_QUERY),$args);
        
        $this->CountryCode = $args['country'];
        
        $response = file_get_contents($url);
        
        $this->json = json_decode($response, true);
        
        $this->QueryResults=array();
        
        foreach($this->json['adverts'] as $item) { 
            
            $phone = ""; $phone_mob = ""; $phone_fax = "";
            
            if ($item['phoneNumbers'] != Null)
                
                foreach($item['phoneNumbers'] as $number){
                    
                    switch($number['type']){
                        case 'std':
                            $phone = $number['phoneNumber'];
                            break;
                        
                        case 'mob':
                            $phone_mob = $number['phoneNumber'];
                            break;
                            
                        case 'fax':
                            $phone_fax = $number['phoneNumber'];
                            break;
                    }
                }

            
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
                                    'mobileNumber' => $phone_mob,
                                    'faxNumber' => $phone_fax,
                                    'homepage' => $item['homepage'],
                                    'facebook' => $item['facebook'],
                                    'infoPageLink' => $item['infoPageLink'],
                                    'latitude' => $latitude,
                                    'longitude' => $longitude,
                                    'countrycode' => $this->CountryCode,
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
    
    
    public function row_in_db($hit_row){
               $qargs = array(
							'post_type' => array( 'enrico'),
							'meta_query' => array(  //(array) - Custom field parameters (available with Version 3.1).
       												array(
												       		'key' => 'enrico-eniroId',                  
												         	'value' => $hit_row['eniroId'],                 
												         	'type' => 'CHAR',                  
													         'compare' => '=',                
												    						 ),											
       												
													 ),
							'posts_per_page' => -1,
							
							);
							
				$query = new WP_Query( $qargs );

			
        	    if($query->have_posts()){
        	        return true;
        	    }
        	    
        	    else return false;
    }
    
    public function insert_post_row($hit_row,$status,$partner_cat){
        
        
        if( !$this->row_in_db($hit_row)){       
				
	            $postarr = array(
	             			'ID' => "",
	             			
	             			'post_type' => 'enrico',
	                        
	                        'post_title' => $hit_row['companyName'],
	                        
	                        'post_name' => wp_unique_post_slug( $hit_row['companyName'],'None', 'None', 'enrico', 'None'),
	                        
	                        'post_status' => $status,
	              
	                        'post_excerpt' =>  $hit_row['companyText'],
	                        
	                        'tax_input' => array(
	                                    'partner_type' => $partner_cat,
	                                        ),
	                                    
	                        
	                         'meta_input' => array(
	                        		    'enrico-eniroId'  => $hit_row['eniroId'],	
	                        		    
	                        			'enrico-companyName' => $hit_row['companyName'],

                                        'enrico-orgNumber' => $hit_row['orgNumber'],

                                        'enrico-companyText' => $hit_row['companyText'],
	                                    
	                                    'enrico-postBox' => $hit_row['postBox'],
	                                    
	                                    'enrico-streetName' => $hit_row['streetName'],
	                                    
	                                    'enrico-postCode' => $hit_row['postCode'],
	                                    
	                                    'enrico-postArea' => $hit_row['postArea'],
	                                    
	                                    'enrico-phoneNumber' => $hit_row['phoneNumber'],
	                                    
	                                    'enrico-homepage' => $hit_row['homepage'],
	                                    
	                                    'enrico-facebook' => $hit_row['facebook'],
	                                    
	                                    'enrico-infoPageLink' => $hit_row['infoPageLink'],
	                                   
	                                   	'enrico-latitude' => $hit_row['latitude'],
	                                    
	                                    'enrico-longitude' => $hit_row['longitude'],
	                                    
	                                    'enrico-countrycode' => $this->CountryCode,)
	                        
	             					) ;
	
	 
	 
	             wp_insert_post( $postarr, 'false' );
	             
        
}
            return;
        }

    
    
    public function insert_post_all($status,$partner_cat){
        
        foreach($this->QueryResults as $hit_row) {
            
           $this->insert_post_row($hit_row,$status,$partner_cat);

        }
        return;
        
    }
    }

?>