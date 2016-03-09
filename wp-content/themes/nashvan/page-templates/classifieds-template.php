<?php 
/*
Template Name: Classifieds */
 ?>
<?php get_header(); ?>
<?php
	global $wpdb;
	$payHistorytab = $wpdb->prefix."payment_history";
	$currentDate = current_time('mysql');
?>
<div id="main-container" class="container classified-inside">
	<div class="row top-buffer2">
		<?php
			$str = "";
			if(isset($_GET['search']) && !empty($_GET['search'])) {
				$str = $_GET['search'];
			}
		?>
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
		<div class="overflow">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="title pull-left">
                        <span>Доска объявлений</span>
                    </div>
                        <ul class="links pull-right">
                          <li><?php
							if(is_user_logged_in())
							{
							echo '<a class="pull-right" href="'.get_permalink(get_page_by_title('Classified add')).'">
							Добавить новое</a>';
							}
							else
							{							 
							 echo '<a class="pull-right" data-target="#login" data-toggle="modal" href="javascript:void(0);"
							 class="pull-right">Добавить новое</a>';
							}?></li>
                        </ul>
                    </div>
                    </div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="col-centered col-xs-12 col-sm-12 col-md-12 col-lg-6">
                         <form method="get" id="search-form" style="position:relative;">
							<input type="text" name="search" id="search" class="form-control" value="<?php echo $str; ?>" placeholder="Что бы вы хотели найти?"><p id="mr-search-frm-btn"></p>
                          </form>   
						</div>                					
					</div>
                    
					 <div class="row">
                       <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">						
                         <?php
							if(is_user_logged_in())
							{
							echo '<a class="btn btn-danger" href="'.get_permalink(get_page_by_title('Classified add')).'">
							Разместить новое объявление</a>';
							}
							else
							{
							 echo '<a class="btn btn-danger" data-target="#login" data-toggle="modal" href="javascript:void(0);"
							 class="pull-right">Разместить новое объявление</a>';
							}?><br><br>
					   </div> 
                       <div class="clearfix"></div>    
					   
					   <?php if(isset($_GET['search']) && !empty($_GET['search'])) { ?>
					
							 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<?php
									 
									  query_posts(array('post_type'=>'classified','s'=>$str,'post_status'=>'publish','posts_per_page'=>-1));
									   
									   if(have_posts())
									   {
											while(have_posts())
											{
												the_post();
												
											   $classId = get_the_ID(); // Classified id 
											   $clsPrice =  get_post_meta($classId,"price",true);
											   $web_site =  get_post_meta($classId,"web_site",true);
											   $clsPhone =  get_post_meta($classId,"phone",true);
											   $clsLocation =  get_post_meta($classId,"location",true);
											   
											   $checkPayment = $wpdb->get_row("select * from $payHistorytab where postId = '$classId' and postType='classified' ORDER BY tid DESC limit 0,1");
												$numDays = 0;
												if($checkPayment) {
													
													if(!$checkPayment->activeDays) {
														$addactiveDays = 0;
													} else {
														$addactiveDays = $checkPayment->activeDays;
													}

													$activeDaysSec = $addactiveDays * 24 * 60 * 60;
													$activateTime = strtotime($checkPayment->date);
													$expirationTime = $activeDaysSec + $activateTime;
													$numDays = ($expirationTime - time())/60/60/24;
												}
											?>
										<div class="ad-block">
										<div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">
											<?php
											 if(has_post_thumbnail())
											 {
											   the_post_thumbnail(); 
											 }
											 else
											 {
												echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';
											 }
											?>
										</div>
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
											<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<h3><a href="<?php the_permalink(); ?>">
												<?php echo mb_strimwidth(get_the_title(),0,44,'...'); ?>
												</a></h3>
												<span class='review'>
												<?php 
												//function Post view Count
												if(function_exists("pvc_get_post_views"))
												{ 
												 echo pvc_get_post_views($classId);
												} 
												?></span>
												<span class='glyphicon glyphicon-eye-open'></span>
												<span class='data'><?php 
												  $clsadDate= get_the_date('d/m/y');
													echo $clsadDate;										 
												?></span>
											</div>
											<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12 m-ads-expcnt-out">
												<p><?php the_excerpt();?></p>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<span><?php echo $clsPhone; ?></span>
												<span>$<?php echo $clsPrice; ?></span>
												<span><?php if(!empty($clsLocation['address'])) { echo $clsLocation['address']; } ?></span>
											</div>
										</div>
                                        
                                        <div class="clearfix"></div>
                                        
                                        <?php $postdata = get_post( $classId, ARRAY_A ); ?>
										 <?php if( is_user_logged_in () && get_current_user_id() == $postdata['post_author'] ) { ?>
                                         <div class="bg-expire-2">Размещение вашего объявления заканчивается через <?php echo ceil($numDays); ?> дня

                                             <div class="ad-block pull-right">

                                                <div class="edit-ad">

                                                <?php edit_post_link('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', '', '</a>', $classId); ?>

                                                <a href="javascript:void(0)" class="deletepostlink" data-id="<?php echo $classId; ?>" data-toggle="modal">

                                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>

                                                </a>

                                                </div>

                                            </div>
                                        </div>		
                                        <?php } ?>
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
						
						<?php } else { ?>

						   <div class="mr-classifieds-cat-out"> 
							   <?php
								 //Category list 
								$taxonomy = 'classified_cat';
								$CatQryarg = array('parent' => 0,'hide_empty' => false); 							
								$classiTerms = get_terms($taxonomy ,$CatQryarg);
								if(is_array($classiTerms))
								{
									foreach($classiTerms as $classifiedCat)
									{
									 $catID = $classifiedCat->term_id;								
									 $catName = $classifiedCat->name;
									
									?>
									 <div class="col-lg-4 col-md-4 col-sm-4 clss-catlis-box">
									   <div class="mr-catlist-title">
										<a href="<?php echo get_term_link(intval($catID),$taxonomy);?>">
										<?php echo $catName; echo "(".$classifiedCat->count.")"; ?>
										</a>
									   </div>
									   <div class="classi-subcat-out"> 
										<?php
										   $args = array('child_of' =>$catID, 'hide_empty'=>false);									   
										   $tax_terms = get_terms($taxonomy,$args);
										  if(is_array($tax_terms)) 
										  {
										   echo '<ul>';
											foreach ($tax_terms as $tax_term) 
											{
												$tax_termID = $tax_term->term_id;	
												$tax_termName = $tax_term->name;
												?>
												 <li>
													 <a href="<?php echo get_term_link(intval($tax_termID),$taxonomy);?>">
														<?php echo $tax_termName; echo "(".$tax_term->count.")"; ?>
													 </a>
												 </li>      
												<?php
											}
										   echo '</ul>';	
										  }
										?>                                     
									   </div>
									</div>
									<?php 
								 }
								}
								?>
							 </div>						
                      
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="overflow">
							                 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							                     <div class="title pull-left">
							                         <span>Последние добавленные объявления</span>
							                     </div>
							                     </div>
							                     </div>
							 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<?php
									
									if(isset($_GET['search']) && !empty($_GET['search']))
									{
									  $str = $_GET['search'];
									  $mybusids=$wpdb->get_col("select ID from $wpdb->posts where post_title  and
									  post_type='classified' LIKE '".$str."%'");
									}
									else
									{$mybusids="";}
									
									query_posts(array('post__in'=>$mybusids,'post_type'=>'classified',
									  'post_status'=>'publish','order' =>'DESC',
									   'posts_per_page'=>'7'));
									   
									   if(have_posts())
									   {
											while(have_posts())
											{
											   the_post();
											   $classId = get_the_ID(); // Classified id 
											   $clsPrice =  get_post_meta($classId,"price",true);
											   $web_site =  get_post_meta($classId,"web_site",true);
											   $clsPhone =  get_post_meta($classId,"phone",true);
											   $clsLocation =  get_post_meta($classId,"location",true);
											   
											   $checkPayment = $wpdb->get_row("select * from $payHistorytab where postId = '$classId' and postType='classified' ORDER BY tid DESC limit 0,1");
												$numDays = 0;
												if($checkPayment) {
													
													if(!$checkPayment->activeDays) {
														$addactiveDays = 0;
													} else {
														$addactiveDays = $checkPayment->activeDays;
													}

													$activeDaysSec = $addactiveDays * 24 * 60 * 60;
													$activateTime = strtotime($checkPayment->date);
													$expirationTime = $activeDaysSec + $activateTime;
													$numDays = ($expirationTime - time())/60/60/24;
												}
										?>
										<div class="row ad-block">
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
											<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
												<span class='review'>
												<?php 
												//function Post view Count
												if(function_exists("pvc_get_post_views"))
												{ 
												 echo pvc_get_post_views($classId);
												} 
												?></span>
												<span class='glyphicon glyphicon-eye-open'></span>
												<span class='data'><?php 
												  $clsadDate= get_the_date('d/m/y');
													echo $clsadDate;										 
												?></span>
											</div>
											<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12 m-ads-expcnt-out">
												<p><?php 
												$adexpcontent = trim(get_the_excerpt());
												if(strlen($adexpcontent)>350)
												{
												 echo substr($adexpcontent,0,350)."...";
												}else{ echo $adexpcontent;}
												?></p>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<span><?php echo $clsPhone; ?></span>
												<span>$<?php echo $clsPrice; ?></span>
												<span><?php if(!empty($clsLocation['address'])) { echo $clsLocation['address']; } ?></span>
											</div>
										</div>
                                        <div class="clearfix"></div>
                                        
                                        <?php $postdata = get_post( $classId, ARRAY_A ); ?>
										 <?php if( is_user_logged_in () && get_current_user_id() == $postdata['post_author'] ) { ?>
                                         <div class="bg-expire-2">Размещение вашего объявления заканчивается через <?php echo ceil($numDays); ?> дня

                                             <div class="ad-block pull-right">

                                                <div class="edit-ad">

                                                <?php edit_post_link('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', '', '</a>', $classId); ?>

                                                <a href="javascript:void(0)" class="deletepostlink" data-id="<?php echo $classId; ?>" data-toggle="modal">

                                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>

                                                </a>

                                                </div>

                                            </div>
                                        </div>		
                                        <?php } ?>
									</div>
												<?php
											}
									   }
									?>
								  <!--Display more classifieds -->
									<div class="mr-more-classifieds-block">                                    
									</div>
								  <!--Close -->
								  <?php
							   $ttAds= query_posts(array('post_type'=>'classified','post_status'=>'publish','order' =>'DESC'));
							   if(count($ttAds)>7)
							   {
								  ?>
									<div class="clear text-center more no-padding">
									<a href="#" id="mr-more-classifieds-btn">Показать еще
									<img src="<?php echo bloginfo('template_url');?>/images/arrow_down.png"></a></div>
						  <?php } 
						   
						  ?>    <br>
								</div>
							   <div class="clearfix"></div>  
							</div>
						
					<?php } ?>	
                         <div class="clearfix"></div>
					  </div>
                        
					</div>
				</div>
			</div><!-- end .row -->
					<?php get_sidebar(); ?>
		</div><!-- end col-lg-9 -->
	</div><!-- end .row -->
</div><!-- end .container -->

<script type="text/javascript">
// Code for load more posts
 jQuery(document).ready(function()
 {
	var pageno =2;
	
	jQuery("#mr-more-classifieds-btn").click(function(event)
	{	
	     event.preventDefault();	
	  //jQuery(".post-loder").show();
	  jQuery.ajax({
		  url: "<?php echo get_template_directory_uri(); ?>/inc/custom-result.php?noofclassified="+pageno, 
		  method: "POST",
		  dataType: "html",
		  success: function(result)
	      {	
		
		   // jQuery(".post-loder").hide();
			if(jQuery(".mr-more-classifieds-block").children('.No-moreposts').length>0)
			{		
				jQuery('#mr-more-classifieds-btn').hide();			
			}
			else
			{
			 jQuery(result).appendTo(".mr-more-classifieds-block").show(2000);	
			}
	      }
	 });
	  pageno++;  
	});
	
	//Submit form 
	jQuery("#mr-search-frm-btn").click(function(){
		jQuery("#search-form").submit();
	});
 });
</script>
<?php get_footer(); ?>