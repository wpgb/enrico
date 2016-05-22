<?php get_header();



if(get_option( 'enrico_map_preferredMap' )!='none'){
			
						$pagemap = new Enrico_Map; //Initialize a map object for the page
						
	
						$pagemap->add_post($post);
				}
	


?>


	<div id="primary" class="enrico-content-area" >
		<?php
				if($pagemap){		
						?>

		
			    <div id="enricomapdiv" class="enricomap-single" ></div>
		<?php		    
			    }
			?>
			<div  class="enrico-singlepost-info" >

			
				<h2><?php echo get_the_title($post);?></h2>
				<p><i>Org.nr: <?php echo get_post_meta($post->ID, 'enrico-orgNumber', true);?></i></p>
	
				
				<p><i><?php echo the_excerpt($post);?></i></p>
				
				<?php echo get_post_meta($post->ID, 'enrico-about', true);?><br>
	                        
                <h4>Adress: </h4>
                <p><?php echo get_post_meta($post->ID, 'enrico-streetName', true);?><br>
	
		
                <?php if (get_post_meta($post->ID, 'enrico-postBox', true)){?>
			                Postbox:  <?php echo get_post_meta($post->ID, 'enrico-postBox', true);?><br>
		                  
		         			<?php  } ?>     

                <?php echo get_post_meta($post->ID, 'enrico-postCode', true);?><br>
                    </tr>
         
        		<strong><?php echo get_post_meta($post->ID, 'enrico-postArea', true);?></strong></p>
         
            	<h4>Kontakt:</h4>
       			<p>Tel: <?php echo get_post_meta($post->ID, 'enrico-phoneNumber', true);?><br>
       			Mobile: <?php echo get_post_meta($post->ID, 'enrico-mobileNumber', true);?><br>
       			Fax: <?php echo get_post_meta($post->ID, 'enrico-faxNumber', true);?><br>
       			
       			Email: <a href= 'mailto: <?php echo get_post_meta($post->ID, 'enrico-email', true);?>' 
			            		target='_top' ><?php echo get_post_meta($post->ID, 'enrico-email', true);?></a>
       			
       			
                <h4>Länkar:</h4>
				

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