<?php get_header(); 
$cpostid= $post->ID;
?>
<div class="container classified-inside">
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
							<span>Доска объявлений</span>
                             <?php
							if(is_user_logged_in())
							{
							  echo '<a class="pull-right" href="'.get_permalink(get_page_by_title("Classified add")).'">Добавить новое</a>';
							}
							else
							{							 
							 echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Добавить новое</a>';
							}?>
						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">
						</div>
						
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
							<form method="get" id="search-form" style="position:relative;">
								<input type="text" name="search" id="search" class="form-control" value="<?php echo $str; ?>" placeholder="Что бы вы хотели найти?"> <p id="mr-search-frm-btn"></p>
							</form>
						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">
						</div>
					</div>
					
					<div class="row">
						<div class="row col-xs-4 col-sm-4 col-md-4 col-lg-4">
							<select required class="form-control" id="select_category" name="select_category">
								<option value="0">Выберите категорию</option>
								 <?php
									$classified_catarr = get_terms('classified_cat', array('hide_empty' => true ));
									foreach($classified_catarr as $classcat) {
										echo '<option value="'.get_term_link( $classcat ).'">'.$classcat->name.'</option>';
									}
								?>
							</select>
						</div>
						<div class="row center text-center col-xs-12 col-sm-4 col-md-5 col-lg-5">
							 <?php
							if(is_user_logged_in()) {
								echo '<a class="btn btn-danger" href="'.get_permalink(get_page_by_title('Classified add')).'">Разместить новое объявление</a>';
							}
							else {							 
							 	echo '<a class="btn btn-danger" data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Разместить новое объявление</a>';
							}
							?><br><br>
						</div>
						<div class="row col-xs-12 col-sm-4 col-md-3 col-lg-3">
							<a class="btn btn-primary" href="<?php echo get_permalink(get_page_by_title('Classifieds'));?>">Все категории</a>
						</div>
					</div>
                    
					<?php if(isset($_GET['search']) && !empty($_GET['search'])) { ?>
					
						<div class="row">
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
											?>
                                    <div class="row ad-block">
									<div class="row col-xs-2 col-sm-2 col-md-2 col-lg-3">
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
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12">
											<p><?php the_excerpt();?></p>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<span><?php echo $clsPhone; ?></span>
                                            <span>$<?php echo $clsPrice; ?></span>
                                            <span><?php echo $clsLocation; ?></span>
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
					
					<?php } else { ?>
					
					 <div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                          <!-- Classified content-->
                           <?php
						     $classifiedCat = array();
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
								   $clsCatList = get_the_terms($classId,'classified_cat'); // Categories
						   ?>
							    <div id="classified">
								<div class="form-group">
									<h1><?php the_title(); ?></h1>
									<span class='review'><?php 
									   if(function_exists("pvc_get_post_views"))
									   { 
									    echo pvc_get_post_views($classId);
									   } 
									 ?></span><span class='glyphicon glyphicon-eye-open'></span>
									<span class='data'><?php echo get_the_date('d/m/y');?></span>
								</div>
							<div class="row upload">
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-5">
									<div class="image mr-classified-ad-thumb">
							    	  <?php 
									  if(has_post_thumbnail())
									  {
										the_post_thumbnail('full');
									  }
									  else
									  {
										 echo '<img alt="" src="'.get_template_directory_uri().'/images/empty-pic.png">'; 
									  }
									  
									  ?>
								    </div>
								</div>
								
								<div class="no-padding col-xs-1 col-sm-1 col-md-1 col-lg-1">
									<ul class="images mrclGlists">
									<li>
										<?php $classified_attachment = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "thumbnail", true ); ?>
										<a data-thumb-src="<?php echo wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ); ?>" href="#">
											<img width="56px" height="56px" src="<?php echo $classified_attachment[0]; ?>">
										</a>
									</li>
                                	<?php
										//Classified gallery attachment id's "fg_temp_metadata"
										$galleryAttids= get_post_meta($classId,"fg_temp_metadata",true);
										if($galleryAttids)
										{
										  $galleryidsArr = explode(",",$galleryAttids);
										  if(count($galleryidsArr)>0)
										  {
											foreach($galleryidsArr as $gAttid)
											{	
											  $attmentSrc =  wp_get_attachment_image_src($gAttid,array(56,56),true);
											  
											  $attmentFullUrl=wp_get_attachment_url($gAttid);
											  ?>
                                               <li>
                                                <a href="#"  data-thumb-src="<?php echo $attmentFullUrl;?>">
											       <img src="<?php echo $attmentSrc[0];?>" height="56px" width="56px">
                                                 </a>
                                               </li>
                                              <?php
											}
										  }
										}
									  ?>
									  </ul>								
								</div>
								<div class="form-horizontal info col-xs-7 col-sm-7 col-md-7 col-lg-6">
										<div class="form-group">
											<div class="price text-left col-xs-10 col-sm-10 col-md-10 
                                            col-lg-4 control-label" for="">Стоимость:</div>
											<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
												<span>$<?php echo $clsPrice; ?></span>
											</div>
										</div>
										<div class="form-group">
											<div class="category text-left col-xs-10 col-sm-10 col-md-10 
                                            col-lg-4 control-label" for="">Категория:</div>
											<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
												<span>
												<?php
												   if(is_array($clsCatList))
												    {
													 $i=1;													 
												     foreach ($clsCatList as $cat)
												     {
														 array_push($classifiedCat,$cat->term_id); 	
													  $termlink=get_term_link(intval($cat->term_id),'classified_cat');
													  echo '<a href="'.$termlink.'">'.$cat->name.'</a>';													
														 if($i<count($clsCatList))
														 {echo ", ";}
														 $i++;
												      }														 
								                     }
												?></span>
											</div>
										</div>
										<div class="form-group">
											<div class="website text-left col-xs-10 col-sm-10 col-md-10 col-lg-4
                                             control-label" for="">Вебсайт:</div>
												<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
													<span><a target="_blank" href="<?php echo $web_site; ?>">
										<?php echo $web_site; ?></a></span>
												</div>
										</div>
										<div class="form-group">
											<div class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-4
                                             control-label" for="">Телефон:</div>
												<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
													<span><?php echo $clsPhone; ?></span>
												</div>
										</div>
										<div class="form-group">
											<div class="location text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
                                            control-label" for="">Расположение:</div>
												<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
													<span><?php echo $clsLocation; ?></span>
												</div>
										</div>
										<div class="text-center form-group">
												<button type="submit" class="btn btn-primary">Отправить сообщение</button>
										</div>

								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="text col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<p><?php the_content(); ?></p>
								</div>
							</div>
							</div>
                            <?php
							      }								  
							 }
							?>
                         <!-- Classified content block close-->   
                            
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<span>Похожие объявления</span>
                           <?php
							if(is_user_logged_in())
							{
							  echo '<a class="pull-right" href="'.get_permalink(get_page_by_title("Classified add")).'">Добавить новое</a>';
							}
							else
							{							 
							 echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Добавить новое</a>';
							}?>
								</div>
                                <?php
								
								  query_posts(array('post_type'=>'classified','post__not_in' => array($cpostid),'post_status'=>'publish','posts_per_page'=>'3',
								          'tax_query' => array(
											array(
												'taxonomy' => 'classified_cat',
												'field'    => 'id',
												'terms'    =>$classifiedCat,
											),
									     )));
								   
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
											?>
                                    <div class="row ad-block">
									<div class="row col-xs-2 col-sm-2 col-md-2 col-lg-3">
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
											<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
											<span class='review'>
											<?php 
											//function Post view Count
											   if(function_exists("pvc_get_post_views"))
											   { 
												echo pvc_get_post_views($classId);
											   } 
									        ?>
                                     </span>
                                            <span class='glyphicon glyphicon-eye-open'></span>
											<span class='data'><?php 
											  $clsadDate= get_the_date('d/m/y');
											  	echo $clsadDate;										 
											?></span>
										</div>
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12">
											<p><?php the_excerpt();?></p>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<span><?php echo $clsPhone; ?></span>
                                            <span>$<?php echo $clsPrice; ?></span>
                                            <span><?php echo $clsLocation; ?></span>
										</div>
									</div>
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
							  $ttclassQry = query_posts(array('post_type'=>'classified','post__not_in' => array($cpostid),
							   'post_status'=>'publish',
								          'tax_query' => array(
											array('taxonomy' => 'classified_cat',
												'field'    => 'id',
												'terms'    =>$classifiedCat))));
								if(count($ttclassQry)>3)				
								{
							  ?>
								<div class="clear text-center more no-padding">
                                <a href="#" id="mr-more-classifieds-btn" data-curr-postid="<?php echo $cpostid; ?>"
                                data-cat-ids="<?php echo serialize($classifiedCat);?>">
                                Показать еще<img src="<?php echo bloginfo('template_url');?>/images/arrow_down.png"></a>
                                </div>
                               <?php } ?> 
							</div>
						</div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div><!-- end .row -->
		</div><!-- end col-lg-9 -->
	</div><!-- end .row -->
</div><!-- end .container -->
<script type="text/javascript">
// Code for load more posts
 jQuery(document).ready(function() {
 
	var pageno =2;
	jQuery("#mr-more-classifieds-btn").click(function(event)
	{	
	   event.preventDefault();	
       var catids = jQuery(this).attr('data-cat-ids');
	   var cpid = jQuery(this).attr('data-curr-postid');

	  jQuery.ajax({
		 url: "<?php echo get_template_directory_uri(); ?>/inc/custom-result.php?noofclassified="+pageno+"&catarr="+catids+"&postid="+cpid,
		 method: "POST",
		  dataType: "html",
		  success: function(result)
		  {
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
	
	jQuery("#select_category").change(function(){
		window.location = jQuery(this).val();
	});
	
	//Submit form 
	jQuery("#mr-search-frm-btn").click(function(){
		jQuery("#search-form").submit();
	});
 });
</script>

<?php get_footer(); ?>