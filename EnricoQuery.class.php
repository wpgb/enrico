<?php

class EnricoQuery{
    
    private $json;
    private $QueryResults;
    private $CountryCode;
    
    public function __construct($url){
        
        $string=parse_str(parse_url($url, PHP_URL_QUERY), $array);
        
        $this->CountryCode = $string['country'];
        
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
    
    
    public function row_in_db($i){
               $qargs = array(
							'post_type' => array( 'enrico'),
							'meta_query' => array(  //(array) - Custom field parameters (available with Version 3.1).
       												array(
												       		'key' => 'enrico-eniroId',                  
												         	'value' => $this->res_row($i)['eniroId'],                 
												         	'type' => 'CHAR',                  
													         'compare' => '=',                
												    						 ),											
       												
													 ),
							'posts_per_page' => -1,
							
							);
							
				$query = new WP_Query( $qargs );

			
        	    if(!$query->have_posts()){
        	        return true;
        	    }
        	    
        	    else return false;
    }
    
    public function insert_post_row($i){
        
        if( !$this->row_in_db($i)){
                
				
	            $postarr = array(
	             			'ID' => "",
	             			
	             			'post_type' => 'enrico',
	                        
	                        'post_title' => $this->res_row($i)['companyName'],
	                        
	                        'post_name' => wp_unique_post_slug( $this->res_row($i)['companyName'],'None', 'None', 'enrico', 'None'),
	              
	                        
	                        'post_excerpt' =>  $this->res_row($i)['companyText'],
	                        
	                         'meta_input' => array(
	                        		    'enrico-eniroId'  => $this->res_row($i)['eniroId'],	
	                        		    
	                        			'enrico-companyName' => $this->res_row($i)['companyName'],

                                        'enrico-orgNumber' => $this->res_row($i)['orgNumber'],

                                        'enrico-companyText' => $this->res_row($i)['companyText'],
	                                    
	                                    'enrico-postBox' => $this->res_row($i)['postBox'],
	                                    
	                                    'enrico-streetName' => $this->res_row($i)['streetName'],
	                                    
	                                    'enrico-postCode' => $this->res_row($i)['postCode'],
	                                    
	                                    'enrico-postArea' => $this->res_row($i)['postArea'],
	                                    
	                                    'enrico-phoneNumber' => $this->res_row($i)['phoneNumber'],
	                                    
	                                    'enrico-homepage' => $this->res_row($i)['homepage'],
	                                    
	                                    'enrico-facebook' => $this->res_row($i)['facebook'],
	                                    
	                                    'enrico-infoPageLink' => $this->res_row($i)['infoPageLink'],
	                                   
	                                   	'enrico-latitude' => $this->res_row($i)['latitude'],
	                                    
	                                    'enrico-longitude' => $this->res_row($i)['longitude'],
	                                    
	                                    'enrico-countrycode' => $this->CountryCode,)
	                        
	             					) ;
	             
	             wp_insert_post( $postarr, 'false' );
	             
        
                }
            return;
        }

    
    
    public function insert_post_all(){
        
        foreach($this->QueryResults as $hit) {
            
            $this->insert_post_row($hit);
        }
        return;
        
    }
    }

?>