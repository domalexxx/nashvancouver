<?php 
/*
Template Name: Bussiness Directory */
  get_header(); ?>
<div class="container business-directory">
	<div class="row">
		<?php get_sidebar(); ?>
		<?php
			$str = "";
			if(isset($_GET['search']) && !empty($_GET['search'])) {
				$str = $_GET['search'];
			}
		?>
		<div class="top-buffer2 col-xs-9 col-sm-9 col-md-9 col-lg-9">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<span>Бизнес справочник</span>
						</div>
						<div class="col-centered col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <form style="position:relative;" id="search-form" method="get">
							<input type="text" name="search" id="search" class="form-control" value="<?php echo $str; ?>" placeholder="Кого бы вы хотели найти?"><p id="mr-search-frm-btn"></p>
                        </form>
						</div>
					</div>
					<div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                          <?php
							if(is_user_logged_in())
							{
							  echo '<a class="btn btn-danger" href="'.get_permalink(get_page_by_title("Business Directory Add")).'">Разместить свой бизнес</a>';
							}
							else
							{							 
							 echo '<a class="btn btn-danger" data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Разместить свой бизнес</a>';
							}?>
					</div>
					
					<?php if(isset($_GET['search']) && !empty($_GET['search'])) { ?>
		  	
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:10px;">
							 <?php
								query_posts( array('post_type'=>'business','s'=>$str,'post_status'=>'publish','posts_per_page'=>-1) );
							   
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
							
								  <div class="row business-block">
									<div class="row col-xs-2 col-sm-2 col-md-2 col-lg-3">
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
									<p><span class='glyphicon glyphicon-eye-open'></span>
										<?php 
										//function Post view Count
										if(function_exists("pvc_get_post_views"))
										{ 
										 echo pvc_get_post_views($businessId);
										} 
										?>
										</p>
								</div>
								<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
								<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
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
					  <?php } else { ?>
					
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-centered mr-buss-dOut">
									<img class="banner" src="http://dummyimage.com/500x85/4d494d/686a82.gif&text=" alt="">
								
								  <?php
									 //Category list 
									$taxonomy ='bussiness_cat';
									$CatQryarg = array('parent'=>0); 							
									$businessTerms = get_terms($taxonomy ,$CatQryarg);
									if(is_array($businessTerms))
									{
									echo '<ul class="catalog">';
									 foreach($businessTerms as $businessCat)
									 {
										 
										 $catID = $businessCat->term_id;								
										 $catName = $businessCat->name;
										 $catImgurl = "";
										 if(function_exists('z_taxonomy_image_url'))
										 {
											  $catImgurl=z_taxonomy_image_url($catID);  
											  
										 }
									   ?>
										<li>
										 <a class="auto" style="background:url(<?php echo $catImgurl; ?>)" 
										   href="<?php echo get_term_link(intval($catID),$taxonomy);?>">
										   <?php echo $catName;?>
										 </a>
										</li>
									   <?php
									 }	
									echo '</ul>';	
									}
								  ?>
								  
								</div>
								</div>
								
								
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<span>Недавно добавленные бизнесы</span>
									</div>
									<?php
									  query_posts(array('post_type'=>'business','post_status'=>'publish','order' => 'DESC',
									   'posts_per_page'=>'3'));
									   
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
									   <div class="row business-block">
										<div class="row col-xs-2 col-sm-2 col-md-2 col-lg-3">
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
											<p><span class='glyphicon glyphicon-eye-open'></span>
											 <?php 
												//function Post view Count
												if(function_exists("pvc_get_post_views"))
												{ 
												 echo pvc_get_post_views($businessId);
												} 
												?>
											</p>
										</div>
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
											<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												Рейтинг:
												<?php
												if(function_exists("csr_get_overall_rating_stars"))
												 {										
												  echo csr_get_overall_rating_stars($businessId);		
												  echo "<span class='tt-ratc'>";
												  echo csr_get_rating_count($businessId)." отзыва</span>";											
												 }	 ?>                                           
												
												<p>Категория:  <span>
												<?php   
												if(is_array($busCatList))
												{
												 $i=1;													 
												 foreach ($busCatList as $cat)
												 {
													// array_push($businessCat,$cat->term_id); 	
													 
													  $termlink=get_term_link(intval($cat->term_id),'bussiness_cat');
														  echo '<a href="'.$termlink.'">'.$cat->name.'</a>';												
													 if($i<count($busCatList))
													 {echo ", ";}
													 $i++;
												  }														 
												} 
												?>
												</span></p>
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
											 ?>
												</span></p>
											</div>
											<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12">
												<p><?php the_excerpt(); ?></p>
											</div>
										</div>
									</div>
									  <?php
											}
									   }
									?>
									<!--Display more business -->
									<div class="mr-more-business-block">                                    
									</div>
								   <!-- Close --> 
									<div class="clear text-center more no-padding">
									 <a class="mr-more-business-btn" id="mr-more-business-btn" href="#">Показать еще<img src="http://nashvancouver.clickwebstudio.com/wp-content/themes/nashvan/images/arrow_down.png"></a></div>
								</div>
							</div>
						<?php } ?>					
						</div>
					</div>
				</div>
			</div><!-- end .row -->
		</div><!-- end col-lg-9 -->
	</div><!-- end .row -->
</div><!-- end .container -->
<script type="text/javascript">
// Code for load more posts
 jQuery(document).ready(function()
 {
 	//Submit form 
   jQuery("#mr-search-frm-btn").click(function(){
	 jQuery("#search-form").submit();
   });
	   
	var pageno =2;
	
	jQuery("#mr-more-business-btn").click(function(event)
	{	
	     event.preventDefault();	
	  //jQuery(".post-loder").show();
	  jQuery.ajax({
		  url: "<?php echo get_template_directory_uri(); ?>/inc/custom-result.php?noofbusiness="+pageno, 
		  method: "POST",
		  dataType: "html",
		  success: function(result)
	      {	
		
		   // jQuery(".post-loder").hide();
			if(jQuery(".mr-more-business-block").children('.No-moreposts').length>0)
			{		
				jQuery('#mr-more-business-btn').hide();			
			}
			else
			{
			 jQuery(result).appendTo(".mr-more-business-block").show(2000);	
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