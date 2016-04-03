<?php get_header();

require 'enricomap.php';

?>


	<div id="primary" class="enrico-content-area">
		
		<div class ="row" >
			
			<div  class="enrico-singlepost-info" >

			
			<h2><?php echo get_the_title($post);?></h2>

			
			


			<p><?php echo the_excerpt($post);?></p>
			
			<?php echo get_post_meta($post->ID, 'enrico-about', true);?><br>
                        <p>Adress: <br>
                        <?php echo get_post_meta($post->ID, 'enrico-streetName', true);?><br>
			
				
		                <?php if (get_post_meta($post->ID, 'enrico-postBox', true)){?>
					                Postbox:  <?php echo get_post_meta($post->ID, 'enrico-postBox', true);?><br>
				                  
				         			<?php  } ?>     
	
		                <?php echo get_post_meta($post->ID, 'enrico-postCode', true);?><br>
		                    </tr>
                 
                		<strong><?php echo get_post_meta($post->ID, 'enrico-postArea', true);?></strong></p>
                 
                    
               			<p>Telefon:  <?php echo get_post_meta($post->ID, 'enrico-phoneNumber', true);?><br>
                        
						Länkar:<br>
						<span>
			            
			           <?php 
			            if(get_post_meta($post->ID, "enrico-homepage", true) !="")
			            	echo "<a href ='" .get_post_meta($post->ID,'enrico-homepage',true)."' target='_blank'>Hemsida</a> | ";
			            	
			            if(get_post_meta($post->ID, "enrico-facebook", true) !="")
			            	echo "<a href ='" .get_post_meta($post->ID,'enrico-facebook',true)."' target='_blank'>Facebook</a> | ";
			           	
			           	if(get_post_meta($post->ID, "enrico-eniroId", true) !="")
			            	echo "<a href ='http://kartor.eniro.se/?index=yp&id=" .get_post_meta($post->ID,'enrico-eniroId',true)."' target='_blank'>Vägbeskrivning</a>";
			            
			            if(get_post_meta($post->ID, "enrico-email", true) !="")
			            	echo " | <a href= 'mailto: ".get_post_meta($post->ID, 'enrico-email', true)."' 
			            		target='_top' >email</a>";
			            ?>		
			            </span></p>
			
			
  </div><!-- col-md-8 -->
		
		
        <?php
        //Input for the map:
        
        	$InfoBox='<strong>'.get_the_title($post).'</strong><br>'.
        				get_post_meta($post->ID, 'enrico-streetName', true).'<br>'.
        				get_post_meta($post->ID, 'enrico-postArea', true).'<br>';
        	
        	
        	$locations =array();
        	
        	$locations[]=array($InfoBox,
								get_post_meta($post->ID, 'enrico-latitude', true),
								get_post_meta($post->ID,'enrico-longitude', true),
								get_the_title($post),
							);			
        				
        	
        	$map_center_latitude = get_post_meta($post->ID, 'enrico-latitude', true);
        	
        	$map_center_longitude = get_post_meta($post->ID,'enrico-longitude', true);
			
			$map_zoom=11
		
		//The Map:?>
		
		<div id="enricomapdiv" class="enricomap-single"></div>
  

  
<?php 
	
	enricomap_render($locations, $map_center_latitude, $map_center_longitude, $map_zoom );
		 
?>
  </div><!-- row --> 
</div><!-- .content-area -->


<?php get_footer(); ?>