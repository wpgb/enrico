<?php get_header(); 

require_once 'enricomap.php';
require_once 'EnricoLinks.class.php';
?>
<h2><?php single_term_title('Listan av: '); ?></h2>



		<?php if ( have_posts() ){ ?>
			<?php
			//Location Collectors Initialized
			$locations =array();
			$latitudes  =array();
			$longitudes = array();
			
		
			// Start the Loop.
			while ( have_posts() ) : the_post(); ?>

				<div class="enrico-multipost-info">
				
						<?php the_title( '<h4><a href="' . get_permalink() . '" title="' . 
							the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h4>' );

						echo get_post_meta($post->ID, "enrico-streetName", true)."<br>";
            			echo get_post_meta($post->ID, "enrico-postCode", true)."   ".get_post_meta($post->ID, "enrico-postArea", true)."<br>";
			            echo "Tel: ".get_post_meta($post->ID, "enrico-phoneNumber", true)."<br>";
			        
						$links= new EnricoLinks($post);
						echo $links->get_links();
							?>
			           
			           <br>
				</div><!-- / div info -->
		
							<?php //Creating input for the Map script-if preferred map is not 'none' and post have latitude and longitude values
			if (get_post_meta($post->ID, 'enrico-latitude', true) 
					&& get_post_meta($post->ID, 'enrico-longitude', true)
						&& get_option( 'enrico_map_preferredMap' )!='none'){
							
									$InfoBox='<strong>'.get_the_title($post).'</strong><br>'.
						        				get_post_meta($post->ID, 'enrico-streetName', true).'<br>'.
						        				get_post_meta($post->ID, 'enrico-postArea', true).'<br>'.
						        				'Lat: '.get_post_meta($post->ID, 'enrico-latitude', true).'<br>'.
						        				'  Long: '.get_post_meta($post->ID, 'enrico-longitude', true);
						        				
						        	
									$locations[]=array($InfoBox,
														get_post_meta($post->ID, 'enrico-latitude', true),
														get_post_meta($post->ID,'enrico-longitude', true),
														get_the_title($post),
										);
									$latitudes[]= get_post_meta($post->ID,'enrico-latitude', true);
							    	$longitudes[]= get_post_meta($post->ID,'enrico-longitude', true);
										}
					// End the loop.
					endwhile;
		
			

				
				
			the_posts_pagination( array(
				'prev_text'          => 'Föregående sida',
				'next_text'          => 'Nästa sida',
				'before_page_number' => '<span class="meta-nav screen-reader-text">'. 'Sida'.' </span>',
			) );
			
			?>

		
		
	<?php //Calculate Map Center and Zoom- if preferred map is not 'none'
	
	if(get_option( 'enrico_map_preferredMap' )!='none'){
		
		$map_center_latitude=(max($latitudes)+min($latitudes))/2;
		$map_center_longitude=(max($longitudes)+min($longitudes))/2;
		
		
		//var eniro_zoom_factor = 5 //used to approximate correct map zoom factor
	    //var req_zoom_lat= Math.floor((Math.log10(mapDiv.clientHeight/(delta_latitudes*eniro_zoom_factor)))/Math.log10(2));
		if(get_option( 'enrico_map_preferredMap' )=='google'){
				$zoomfactor = 1.5 ;//zoomfactor GoogleMap approx
		}else {
				$zoomfactor = 5 ;//zoomfactor EniroMap approx
		}
		
		$MapDivHeight = 500;
		$MapDivWidth = 500; //Check the css for these values. Height & Width is variable...
		
		if(max($latitudes)-min($latitudes)>0){
		
			$map_zoom_lat = floor(log10($MapDivHeight/((max($latitudes)-min($latitudes))*$zoomfactor))/log10(2));}
	
			else {$map_zoom_lat = 11;}
		
		if(max($longitudes)-min($longitudes)>0){
		
			$map_zoom_long = floor(log10($MapDivHeight/((max($longitudes)-min($longitudes))*$zoomfactor))/log10(2));}
	
			else {$map_zoom_long = 11;}
		
		$map_zoom = min ($map_zoom_lat,$map_zoom_long );

		
?>
	<div id="enricomapdiv" class="enricomap-multi"></div>

<?php
	enricomap_render($locations, $map_center_latitude, $map_center_longitude, $map_zoom );
} //endif preferred map not 'none'
} //if have posts

?>
	

<?php get_footer(); ?>