<?php get_header();



if(get_option( 'enrico_map_preferredMap' )!='none'){
			
						$pagemap = new Enrico_Map; //Initialize a map object for the page
						
	
						$pagemap->add_post($post);
				}
	


?>


	<div id="primary" class="content-area" >
		<?php
				if($pagemap){		
						?>

		
			    <div id="enricomapdiv" class="enricomap-single" ></div>
		<?php		    
			    }
			?>
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
				$links= new Enrico_Links($post);
				echo $links->get_links();
					?>
				
	  		
			
		
       
        
			     
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
			
		?>
		

 
</div>
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>