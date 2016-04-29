<?php get_header(); 


?>
<h2><?php single_term_title('Listan av: '); ?></h2>



		<?php if ( have_posts() ){ 
			
				if(get_option( 'enrico_map_preferredMap' )!='none'){
			
						$pagemap = new Enrico_Map; //Initialize a map object for the page
						}
		
			// Start the Loop.
			while ( have_posts() ) : the_post(); ?>

				<div class="enrico-multipost-info">
				
						<?php the_title( '<h4><a href="' . get_permalink() . '" title="' . 
							the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h4>' );

						echo get_post_meta($post->ID, "enrico-streetName", true)."<br>";
            			echo get_post_meta($post->ID, "enrico-postCode", true)."   ".get_post_meta($post->ID, "enrico-postArea", true)."<br>";
			            echo "Tel: ".get_post_meta($post->ID, "enrico-phoneNumber", true)."<br>";
			        
						$links= new Enrico_Links($post);
						echo $links->get_links();
							?>
			           
			           <br>
				</div><!-- / div info -->
		
							<?php 
			
				if($pagemap){
					
					$pagemap->add_post($post);
				}
				
			
			// End the loop.
			endwhile;
		
			
			the_posts_pagination( array(
				'prev_text'          => 'Föregående sida',
				'next_text'          => 'Nästa sida',
				'before_page_number' => '<span class="meta-nav screen-reader-text">'. 'Sida'.' </span>',
			) );
			
		
	if($pagemap){		
						?>

			    <div id="enricomapdiv" class="enricomap-multi" ></div>
			     
			    <script>
			    var mapDiv = document.getElementById('enricomapdiv');
			    var locations = <?php echo json_encode($pagemap->get_Locations());?>;  
			    var zoom = <?php echo $pagemap->get_Zoom();?>;
			    var LatLong = <?php echo json_encode($pagemap->get_LatLong());?>;
			    </script>
			
				<?php
				
				if(get_option( 'enrico_map_preferredMap' )=='google'){ ?>
					
					<script>
						 	initGoogleMap();
			    	</script>
			<?php				
					}elseif(get_option( 'enrico_map_preferredMap' )=='eniro'){ ?>
					
					   	<script>
						 	initEniroMap();
			    		</script>
			<?php
					}
	}	

} //if have posts?>	

<?php get_sidebar(); ?>
<?php get_footer(); ?>