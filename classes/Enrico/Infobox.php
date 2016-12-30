<?php

class Enrico_Infobox{
        
    private $infobox_html;
    
    public function __construct($post){
                
                $links= new Enrico_Links($post);
        
        		$this->infobox_html ='<div>';
        		
        		if(get_option( 'enrico_map_marker_logo' )!=''){
        		    
        		    $this->infobox_html .= '<img src="'.get_option( 'enrico_map_marker_logo' ).
        		        '" style= "width:'.get_option( 'enrico_map_marker_logo_width' ).'px"><br>';
        		}
        		
        		$this->infobox_html .= 	'<a href="' . get_permalink($post) . '">' .get_the_title($post).'</a><br>'.
			        						
			        						get_post_meta($post->ID, 'enrico-streetName', true).'<br>'.
			        						
			        						get_post_meta($post->ID, 'enrico-postArea', true).'<br>'.
			        				        
			        				        'Tel: '.get_post_meta($post->ID, 'enrico-phoneNumber', true).'<br>'.
			        				
			        				        $links->get_links().'</div>';
			        				        
			   
        }
        
    public function get_infobox(){
        return $this->infobox_html;
    
                    }
    }


?>