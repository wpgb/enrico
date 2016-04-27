<?php

class Enrico_Map{
    
    private $latitudes;
    private $longitudes;
    private $locations;
    
    public function __construct(){
        $this->latitudes=array();
        $this->longitudes=array();
        $this->locations=array();
            }
    
    public function add_post($post){
        
        if(!$post)
                return;
        
        if (get_post_meta($post->ID, 'enrico-latitude', true) 
					&& get_post_meta($post->ID, 'enrico-longitude', true)
						&& get_option( 'enrico_map_preferredMap' )!='none'){ 
							
						$InfoBox='<strong>'.get_the_title($post).'</strong><br>'.
			        				get_post_meta($post->ID, 'enrico-streetName', true).'<br>'.
			        				get_post_meta($post->ID, 'enrico-postArea', true).'<br>'.
			        				'Lat: '.get_post_meta($post->ID, 'enrico-latitude', true).'<br>'.
			        				'  Long: '.get_post_meta($post->ID, 'enrico-longitude', true);
			        				
			        	
						$this->locations[]=array($InfoBox,
											get_post_meta($post->ID, 'enrico-latitude', true),
											get_post_meta($post->ID,'enrico-longitude', true),
											get_the_title($post),
							);
						$this->latitudes[]= get_post_meta($post->ID,'enrico-latitude', true);
				    	$this->longitudes[]= get_post_meta($post->ID,'enrico-longitude', true);
			}
    }
        
    
    public function get_LatLong(){
        	
		$map_center_latitude=(max($this->latitudes)+min($this->latitudes))/2;
		$map_center_longitude=(max($this->longitudes)+min($this->longitudes))/2;
		
		return array($map_center_latitude,$map_center_longitude);
    }
	
	
	public function get_Zoom($MapDivHeight = 500, $MapDivWidth = 500){	
		
		if(get_option( 'enrico_map_preferredMap' )=='google'){
				$zoomfactor = 1.5 ;//zoomfactor GoogleMap approx
		}else {
				$zoomfactor = 5 ;//zoomfactor EniroMap approx
		}
		
		if(max($this->latitudes)-min($this->latitudes)>0){
		
			$map_zoom_lat = floor(log10($MapDivHeight/((max($this->latitudes)-min($this->latitudes))*$zoomfactor))/log10(2));}
	
			else {$map_zoom_lat = 11;}//If only one location
		
		if(max($this->longitudes)-min($this->longitudes)>0){
		
			$map_zoom_long = floor(log10($MapDivHeight/((max($this->longitudes)-min($this->longitudes))*$zoomfactor))/log10(2));}
	
			else {$map_zoom_long = 11;}//If only one location
		
		return min($map_zoom_lat,$map_zoom_long );
    }   
  
    public function get_Locations(){
        return $this->locations;
    }
    
    
 }        
?>