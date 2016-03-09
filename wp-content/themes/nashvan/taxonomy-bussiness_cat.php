<?php get_header(); 
  $curCat_id = $wp_query->get_queried_object_id();
?>
<div id="main-container" class="container business-directory inside">
	<div class="row top-buffer2">
		<?php
			$str = "";
			if(isset($_GET['search']) && !empty($_GET['search'])) {
				$str = $_GET['search'];
				//$mybusids = $wpdb->get_col("select ID from $wpdb->posts where post_title and post_type='business' LIKE '".$str."%'");
			}
		?>
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
				<div class="overflow">
		                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                    <div class="title pull-left">
		                        <span><?php single_cat_title( '', true ); ?></span>
		                    </div>
		                </div>
		        </div>	
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="col-centered col-xs-12 col-sm-12 col-md-12 col-lg-6">
                               <form method="get" id="search-form" style="position:relative;">
                                    <input type="text" name="search" id="search" class="form-control" value="<?php echo $str; ?>" placeholder="Кого бы вы хотели найти?"><p id="mr-search-frm-btn"></p>
                                </form>
							</div>
					</div>
					<div class="row">
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
                         <?php
						  //Get the list of all business categrory
						   $args = array('orderby'=>'name','order'=> 'ASC'); 
						   $termsArr = get_terms('bussiness_cat', $args);							
						 ?>
							<select name="mrbusiness_category" id="mr-selectbus-category" data-style="btn-info" class="form-control selectpicker" required>
								<option value="">Выберите категорию</option>
                                <?php
								  
								  if(count($termsArr)>0)
								  {
								   foreach($termsArr as $termData)
								    {?>								                           
								      <option <?php if($curCat_id ==$termData->term_id){echo 'selected';} ?> 
                                      value="<?php echo $termData->term_id; ?>">
                                      <?php
									  if($termData->parent>0)
									  {
										echo "<strong>- </strong>";
									  }
									  echo $termData->name.'</option>';
                                    }
								  }
							  ?>
								
							</select>
						</div>
						<div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-4">							
                            <?php
							if(is_user_logged_in())
							{
							  echo '<a class="btn btn-danger" href="'.get_permalink(get_page_by_title("Business Directory Add")).'">
							  Разместить свой бизнес</a>';
							}
							else
							{							 
							 echo '<a class="btn btn-danger" data-target="#login" data-toggle="modal" 
							 href="javascript:void(0);" class="pull-right">Разместить свой бизнес</a>';
							}?>
						</div>
						<div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-3">
							<a href="<?php echo get_permalink(get_page_by_title('Business Directory'));?>" class="btn btn-primary">Все категории</a>
						</div>
                        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
                          <div class="pull-right">
                            <select name="showNoOfbusiness" id="show-noof-business" class="form-control selectpicker" data-style="btn-info">
                                <option <?php if(isset($_GET['nfbp']) && $_GET['nfbp']==1){echo 'selected';}?> 
                                value="10">10</option>
                                <option <?php if(isset($_GET['nfbp']) && $_GET['nfbp']==15){echo 'selected';}?> 
                                value="15">15</option>
                                <option <?php if(isset($_GET['nfbp']) && $_GET['nfbp']==20){echo 'selected';}?> 
                                value="20">20</option>
                                <option <?php if(isset($_GET['nfbp']) && $_GET['nfbp']==30){echo 'selected';}?>
                                 value="30">30</option>
                            </select>
					     </div>
                        </div>
					</div>
					
					<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            	 <?php
								 if(!empty($_GET['nfbp']) && is_numeric($_GET['nfbp']))
								 {
								  $postPerPage = $_GET['nfbp'];
								 }
								 else
								 {
								  $postPerPage = 10;
								 }
								 
								$my_post_array = array("-99");
								$my_post_args = array( 'post_type' => 'business', 's' => $str, 'post_status' => 'publish', 'posts_per_page' => $postPerPage, 'tax_query' => array( array( 'taxonomy' => 'bussiness_cat', 'field' => 'id', 'terms' => $curCat_id ) ) );
		
								$my_querys = null;
								$my_querys = new WP_Query($my_post_args);
		
								if( $my_querys->have_posts() ) {
									while ($my_querys->have_posts()) : $my_querys->the_post();
									
										if( check_post_expiration( get_the_ID(), "business" ) > 0 ) {
											array_push($my_post_array, get_the_ID());
										}
		
									endwhile;
								}
								wp_reset_query();
																 								
								 query_posts(array('post_type'=>'business','s'=>$str,'post_status'=>'publish', 'post__in' => $my_post_array, 'posts_per_page'=>$postPerPage,
									'tax_query' => array(
									array(
										'taxonomy' => 'bussiness_cat',
										'field'    => 'id',
										'terms'    => $curCat_id,
									))));
								   
								   if(have_posts())
								   {
									    while(have_posts())
										{
										   the_post();
										   $businessId = get_the_ID(); // Business id 	
										   $busweb_site =  get_post_meta($businessId,"website",true);
										   $busPhone =  get_post_meta($businessId,"phone",true);
										   $busclsAddress =  get_post_meta($businessId,"address",true);		
										   $busCatList = get_the_terms($businessId,'bussiness_cat'); // Categories
											?>
								
                                	  <div class="row business-block ad-block">
									    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">
										<?php
										 if(has_post_thumbnail())
										 {
										   the_post_thumbnail('thumbnail'); 
										 }
										 else
										 {
											echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';
										 }
										?>
									</div>
                                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                    <div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height: 40px;">
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
<p class="pull-right" style="margin-right:20px; font-size: 12px;"><span class='glyphicon glyphicon-eye-open' style="margin: 4px 0 0 5px;"></span>
											<?php 
											//function Post view Count
											if(function_exists("pvc_get_post_views"))
											{ 
											 echo pvc_get_post_views($businessId);
											} 
											?>
                                            </p>
                                        </h3>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <p>Рейтинг: 
                                        <?php			
										 if(function_exists("csr_get_overall_rating_stars"))
										 {										
										  echo csr_get_overall_rating_stars($businessId);		
										  echo "<span class='tt-ratc'>";
										  echo csr_get_rating_count($businessId)." отзыва</span>";											
										 }
										?>                                       
                                        </p>
                                        <p>Категория:  <span>
                                        <?php
                                            
                                               if(is_array($busCatList))
                                                {
                                                 $i=1;													 
                                                 foreach ($busCatList as $cat)
                                                 {
                                                    // array_push($classifiedCat,$cat->term_id); 	
													 $termlink=get_term_link(intval($cat->term_id),'bussiness_cat');
													  echo '<a href="'.$termlink.'">'.$cat->name.'</a>';	
                                                     //echo $cat->name;													
                                                     if($i<count($busCatList))
                                                     {echo ", ";}
                                                     $i++;
                                                  }														 
                                                }
                                            ?></span></p>
                                        <p>Вебсайт: <span>
                                        <?php
										if(!empty($busweb_site))
										{
										   $parsed = parse_url($busweb_site);
											if (empty($parsed['scheme'])){
												$urlStr = 'http://' . ltrim($busweb_site, '/');
											}
											else
											{
											  $urlStr= $busweb_site;
											}
										echo '<a target="_blank" href="'.$urlStr.'">'.$busweb_site.'</a>';	
										}
										?>
                                        
                                        </span></p>
                                        <p>Телефон: <span><?php echo $busPhone; ?></span></p>
                                        <p>Адрес:  <span>
                                        <?php 
                                        if(!empty($busclsAddress['address']))
                                        {
										 echo '<a class="mr-gmap-location-pop" 
										   data-add-lat="'.$busclsAddress["lat"].'" 
										   data-add-long="'.$busclsAddress["lng"].'">'.$busclsAddress['address'].'</a>';
										}
                                        ?></span></p>
                                    </div>
                                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-12">
                                        <p><?php the_excerpt(); ?></p>
                                    </div>
                                </div>
								      </div>
                                  <?php
										}
								   } else {
										echo '<div class="row search-error">';
											echo '<div class="row bg-warning col-xs-12 col-sm-12 col-md-12 col-lg-12">';
												echo '<p>Не удалось ничего найти по результатам вашего запроса</p>';
												echo '<p>Попробуйте изменить фразу поиска</p>';
											echo '</div>';
										echo '</div>';
								   }
								?>
								
							</div>
							</div>
						</div>
						
						</div>
					</div>
				</div>
						<?php get_sidebar(); ?>

			</div><!-- end .row -->
		</div><!-- end col-lg-9 -->
	</div><!-- end .row -->
</div><!-- end .container -->
<script type="text/javascript">
jQuery(document).ready(function(e) {

// Change Category	
   jQuery('#mr-selectbus-category').on("change",function(){
	  var catid = jQuery(this).val(); 
	  if(catid)	  
	  {
		jQuery.ajax({url:'<?php echo get_template_directory_uri(); ?>/inc/custom-result.php?getCatIdurl='+catid,
		method: "POST",		
		dataType:"text",
		success: function(res)
		 {
		   window.location=res;			
		 }
		});		 
	  }
   });
 // End 
 
  //Show Noof bussiness  
   jQuery('#show-noof-business').on("change",function()
   {
	  var noofbusiness = jQuery(this).val(); 
	 
	  if(noofbusiness)	  
	  {
		var currentPageUrl =encodeURIComponent('<?php echo current_page_url(); ?>');
		jQuery.ajax({
		url:"<?php echo get_template_directory_uri(); ?>/inc/custom-result.php?shownofbpage="+noofbusiness+"&cpurl="+currentPageUrl,
		method:"POST",		
		dataType:"text",
		success: function(result)
		 { 
		   window.location=result;			
		 }
		});		 
	  }
   });
   
  //Submit form 
   jQuery("#mr-search-frm-btn").click(function(){
	 jQuery("#search-form").submit();
   });
});
</script>
<?php get_footer(); ?>