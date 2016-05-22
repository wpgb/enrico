<?php

class Enrico_Links{
    
    private $links_html;
    
    public function __construct($post){
         
						
						$this->links_html ="<span>";
			            
			          
			            if(get_post_meta($post->ID, "enrico-homepage", true) !="")
			            	    $this->links_html .= "<a href ='" .get_post_meta($post->ID,'enrico-homepage',true)."' target='_blank'>Hemsida</a> | ";
			            	
			            if(get_post_meta($post->ID, "enrico-facebook", true) !="")
			            		$this->links_html .=  "<a href ='" .get_post_meta($post->ID,'enrico-facebook',true)."' target='_blank'>Facebook</a> | ";
			           	
			           	if(get_post_meta($post->ID, "enrico-email", true) !="")
			            		$this->links_html .=  " | <a href= 'mailto: ".get_post_meta($post->ID, 'enrico-email', true)."' 
			            		target='_top' >email</a> | ";
			            
			            if(get_post_meta($post->ID, "enrico-infoPageLink", true) !="")
			            		$this->links_html .=  "<a href ='" .get_post_meta($post->ID,'enrico-infoPageLink',true)."' target='_blank'>InfoPage</a> | ";
			            
			            if (get_post_meta($post->ID, 'enrico-latitude', true) && get_post_meta($post->ID, 'enrico-longitude', true)){
			                
			                    if(get_option( 'enrico_map_preferredMap' )=='google'){ //If Google map
			                                $this->links_html .=  "<a href ='https://maps.google.com?daddr=" 
			                                    .get_post_meta($post->ID,'enrico-latitude',true).','.get_post_meta($post->ID,'enrico-longitude',true)."' target='_blank'>Vägbeskrivning</a>";
			                            }
			                     else {
			            
            			            if(get_post_meta($post->ID, "enrico-countrycode", true) =="se"){
            			            		$this->links_html .=  "<a href ='http://kartor.eniro.se/?index=yp&id=" .get_post_meta($post->ID,'enrico-eniroId',true)."' target='_blank'>Vägbeskrivning</a>";}
            			            
            			            elseif (get_post_meta($post->ID, "enrico-countrycode", true) =="dk"){
            			            		$this->links_html .=  "<a href ='http://map.krak.dk/?index=yp&id=" .get_post_meta($post->ID,'enrico-eniroId',true)."' target='_blank'>Ruteplan</a>";}
            			            
            			            elseif (get_post_meta($post->ID, "enrico-countrycode", true) =="no"){
            			            		$this->links_html .=  "<a href ='http://kart.gulesider.no/?index=yp&id=" .get_post_meta($post->ID,'enrico-eniroId',true)."' target='_blank'>Veibeskrivelse</a>";}
			                     }
			           		
			            
			            	
                            }
                           
                         
			            		
			            		
                        $this->links_html .= "</span>";
            }
            
    public function get_links(){
        return $this->links_html;
    
                    }
			
}

?>