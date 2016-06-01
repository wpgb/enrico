<?php get_header(); ?>
<div id="primary" class="enrico-content-area" >

<form role="search" method="get" class="search-form" 
			
			action="<?php echo esc_url( home_url('/enrico/')); ?>">
	

			
			<input type="search" class="enrico-search-field"
						placeholder="<?php echo single_term_title('Sök bland '); ?>" 
							value="<?php echo get_search_query(); ?>" name="s" 
								 />
	
			<button type="submit" class="enrico-search-submit"></button>
</form>
<br>


<?php
 	if ( have_posts() ){ 
			
			if(get_option( 'enrico_map_preferredMap' )!='none'){
			
				$pagemap = new Enrico_Map; //Initialize a map object for the page
			}
			
			// Initiate the Custom Loop. Construct arguments for WP Query
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			
			$args = array(
							'post_type' => 'enrico',
							'post_status' => 'publish',
							'posts_per_page' => get_option("enrico_archive_posts_per_page"),
							'paged'=> $paged,
			

							);
			switch (get_option("enrico_archive_posts_sort_term")) {
    				case "default":
        					
        							break;
        			case "post_title":
        					$args['orderby']='title';
        							break;
        							
        			case "enrico-postCode":
        					$args['orderby']= 'meta_value';
        						$args['meta_key'] = 'enrico-postCode';
        							break;
        			
        			case "enrico-latitude":
        						$args['orderby']= 'meta_value_num' ;
        						$args['meta_key'] = 'enrico-latitude';
        						$args['meta_type'] = 'DECIMAL';
        							break;
        							
        			case "enrico-longitude":
        						$args['orderby']= 'meta_value_num' ;
        						$args['meta_key'] = 'enrico-longitude';
        						$args['meta_type'] = 'DECIMAL';
        							break;
						}
        
        	if(get_option("enrico_archive_posts_sort_order") =='DESC'){
        			$args['order']= 'DESC';
        			}
        		else{
        			
        			$args['order']='ASC';
        			
        		}
			
	
			// Start the Custom Loop.
			
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();?>

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
			
			
			endwhile; wp_reset_postdata();  // End the loop.

			
				
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

</div>
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>