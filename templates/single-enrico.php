<?php get_header();

require_once 'enricomap.php';
require_once 'EnricoLinks.class.php';
require_once 'EnricoMap.class.php';

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

						<?php
						$links= new EnricoLinks($post);
						echo $links->get_links();
							?>
			
  </div><!-- col-md-8 -->
		
		
        <?php //Creating input for the Map script-if preferred map is not 'none' and post have latitude and longitude values
			if (get_post_meta($post->ID, 'enrico-latitude', true) 
					&& get_post_meta($post->ID, 'enrico-longitude', true)
						&& get_option( 'enrico_map_preferredMap' )!='none'){
        
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
		}//endif map	 
				?>
  </div><!-- row --> 
</div><!-- .content-area -->


<?php get_footer(); ?>