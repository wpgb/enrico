<?php
require('EnricoQuery.class.php');

function enrico_admin_import_page(){
    	add_submenu_page('edit.php?post_type=enrico', 'EnricoImport', 'Enrico Import', 
    	'manage_options', 'enrico_company_import',
    	'enrico_import_define_search');
  
}



function enrico_import_define_search(){
	
	?>
	

	<div class="wrap">
		<h2>Enrico Company Import</h2>
		
	</div>
	

	<form  action="admin-post.php" method="post" >
  
    
	    Search Terms (use comma to delimit):<br>
		    <input type="text" name="search_terms" size="75" placeholder="Example values: advokat, hoteller, vvs">
		    
		    <input type="submit" value="Submit Search"><br>
		   	
		   	<input name='action' type="hidden" value='import_search_form_submit'>
   
	    Search Location :<br>
	    	<input type="text" name="geo_area" size="75" placeholder="Example values: Stockholm, københavn, Oslo">
	    <br>
   
	    Country :<br>
	    		<input type="radio" name="country" value="se" checked> Sweden (eniro.se)
	  			<input type="radio" name="country" value="no"> Norway (gulesider.no)
	  			<input type="radio" name="country" value="dk"> Denmark (krak.dk)
		 <br><br>
	  	Max Results:<br>
	    		<input type="radio" name="max_results" value="25" checked> 25
	  			<input type="radio" name="max_results" value="50"> 50
	  			<input type="radio" name="max_results" value="75"> 75
	  			<input type="radio" name="max_results" value="100"> 100
	  	
	  	

   
  
	</form>
	
		
	<?php
	
//	$url= 'http://api.eniro.com/cs/search/basic?profile=gbellak&key=1070681897893082491&country=se&version=1.1.3&search_word=eniro&geo_area=stockholm';

}

function enrico_import_run_search(){
	        
        //Required Parameters for the query-url
        $country_code= $_POST['country'];

        $api_profile=get_option('enrico_api_profile');
        $api_key=get_option('enrico_api_key');
        $api_version =get_option('enrico_api_version');
    
    
        $url = 'http://api.eniro.com/cs/search/basic?profile='.get_option('enrico_api_profile')
        											
        												.'&key='.get_option('enrico_api_key')
        												
                        								.'&version='.get_option('enrico_api_version')
        												
        												.'&country='.$_POST['country']
        												
        												.'&search_word='.$_POST['search_terms']
        												
        												.'&geo_area='.$_POST['geo_area']
        												
        												.'&from_list=1'
        												
        												.'&to_list='.$_POST['max_results']
                        
														;
                        
                        
        
        $eniro_search_result=new EnricoQuery($url);
	
//	wp_safe_redirect(admin_url('edit.php?post_type=enrico&page=enrico_company_import'));exit;
	
	?>
		<div class="wrap">
		<h2>Enrico Company Import- Search Result</h2>
		<p>No. of hits: <?php echo $eniro_search_result->hits() ?>;</p>
		<p><?php echo $url ?></p>
		
		<form  action="admin-post.php" method="post" >
			
			<?php 
			$i=0;
			
			while ($eniro_search_result->res_row($i)){
				?>
				
				<?php 
				
				echo '<input type="checkbox" name="import_'.$i.'" value="on" checked>
				
				<input type="hidden" name="import_eniroId_'.$i.'" value="'.$eniro_search_result->res_row($i)['eniroId'].'">  '.
				'<input type="hidden" name="import_country_'.$i.'" value="'.$country_code.'">  '
				
					.$eniro_search_result->res_row($i)['eniroId'].' |  '
					
					.$eniro_search_result->res_row($i)['companyName'].' |  '
					
					.$eniro_search_result->res_row($i)['streetName'].' | '
					
					.$eniro_search_result->res_row($i)['postArea'].'<br>';
				
				$i++;
			}
			
  	?>	
     <br><input type="submit" value="Import Companies"><br>
     <input name='action' type="hidden" value='import_selected_perform'>
  
	</form>
	
		
	</div>
<?php
}


function enrico_import_companies(){
	echo  '<h2>Enrico Company Import- Search Result</h2>';
	
	
	$i=0;
			
	while ($_POST["import_eniroId_".$i]){//Iterate the POST array
		
		if($_POST["import_".$i]=='on'){ //Check if Import box is checked
			
			//Required Parameters for the query-url
        	
    
        		$url = 'http://api.eniro.com/cs/search/basic?profile='
                    
                    .get_option('enrico_api_profile')
                    
                    .'&key='.get_option('enrico_api_key')
                    
                    .'&country='.$_POST["import_country_".$i]
                    	
                    .'&version='.get_option('enrico_api_version')
                    
                    .'&eniro_id='.$_POST["import_eniroId_".$i];
                        
                      
        
        	$eniro_search_result=new EnricoQuery($url);
        
	        if ($eniro_search_result->hits() != 1){  //Check that query has exactly 1 hit - else skip
	           		continue;
	            		}
	        $qargs = array(
							'post_type' => array( 'enrico'),
							'meta_query' => array(  //(array) - Custom field parameters (available with Version 3.1).
       												array(
												       		'key' => 'enrico-eniroId',                  
												         	'value' => $eniro_search_result->res_row(0)['eniroId'],                 
												         	'type' => 'CHAR',                  
													         'compare' => '=',                
												    						 ),											
       												
													 ),
							'posts_per_page' => -1,
							
							);


			$query = new WP_Query( $qargs );

			
        	if(!$query->have_posts()){//Only if NOT duplicate-possibly data update could be added in ELSE statement for existing post....
			
				
	            $postarr = array(
	             			'ID' => "",
	             			
	             			'post_type' => 'enrico',
	                        
	                        'post_title' => $eniro_search_result->res_row(0)['companyName'],
	                        
	                        'post_name' => wp_unique_post_slug( $eniro_search_result->res_row(0)['companyName'],'None', 'None', 'enrico', 'None'),
	              
	                        
	                        'post_excerpt' =>  $eniro_search_result->res_row(0)['companyText'],
	                        
	                         'meta_input' => array(
	                        		    'enrico-eniroId'  => $eniro_search_result->res_row(0)['eniroId'],	
	                        		    
	                        			'enrico-companyName' => $eniro_search_result->res_row(0)['companyName'],

                                        'enrico-orgNumber' => $eniro_search_result->res_row(0)['orgNumber'],

                                        'enrico-companyText' => $eniro_search_result->res_row(0)['companyText'],
	                                    
	                                    'enrico-postBox' => $eniro_search_result->res_row(0)['postBox'],
	                                    
	                                    'enrico-streetName' => $eniro_search_result->res_row(0)['streetName'],
	                                    
	                                    'enrico-postCode' => $eniro_search_result->res_row(0)['postCode'],
	                                    
	                                    'enrico-postArea' => $eniro_search_result->res_row(0)['postArea'],
	                                    
	                                    'enrico-phoneNumber' => $eniro_search_result->res_row(0)['phoneNumber'],
	                                    
	                                    'enrico-homepage' => $eniro_search_result->res_row(0)['homepage'],
	                                    
	                                    'enrico-facebook' => $eniro_search_result->res_row(0)['facebook'],
	                                    
	                                    'enrico-infoPageLink' => $eniro_search_result->res_row(0)['infoPageLink'],
	                                   
	                                   	'enrico-latitude' => $eniro_search_result->res_row(0)['latitude'],
	                                    
	                                    'enrico-longitude' => $eniro_search_result->res_row(0)['longitude'],
	                                    
	                                    'enrico-countrycode' => $_POST["import_country_".$i],)
	                        
	                       
	             					) ;
	             wp_insert_post( $postarr, 'false' );
	             
             	}	

		
		}

		$i++;
		
	}
	wp_safe_redirect(admin_url('edit.php?post_type=enrico'));exit;
				
}

?>