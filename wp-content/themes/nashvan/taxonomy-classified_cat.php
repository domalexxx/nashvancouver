<?php get_header(); 
 $curCat_id = $wp_query->get_queried_object_id();
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
					   		<input type="text" name="search" id="search" class="form-control" value="<?php echo $str; ?>" placeholder="Что бы вы хотели найти?"> <p id="mr-search-frm-btn"></p>
					   	</form>
						</div>	
					</div>
					
                    <div class="row">
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
							<?php
								//Get the list of all business categrory
								$args = array('orderby'=>'name','order'=> 'ASC'); 
								$termsArr = get_terms('classified_cat', $args);							
							?>
							<select required class="form-control selectpicker" id="select_category" name="select_category" data-style="btn-info">
								<option value="0">Выберите категорию</option>
								
								 <?php
								  if(count($termsArr)>0)
								  {
									foreach($termsArr as $termData) { ?>								                           
								      <option <?php if($curCat_id == $termData->term_id){echo 'selected="selected"';} ?> value="<?php echo get_term_link( $termData ); ?>">
                                      <?php
										if($termData->parent > 0)
										{ echo "<strong>- </strong>"; }
										echo $termData->name.'</option>';
                                    }
								  }
							  ?>
							</select>
						</div>
						<div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-5">
							 <?php
							if(is_user_logged_in())
							{
							echo '<a class="btn btn-danger" href="'.get_permalink(get_page_by_title('Classified add')).'">Разместить новое объявление</a>';
							}
							else
							{							 
							 echo '<a class="btn btn-danger" data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Разместить новое объявление</a>';
							}?><br><br>
						</div>
						<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-3 mr-e">
							<a class="btn btn-primary" href="<?php echo get_permalink(get_page_by_title('Classifieds'));?>">Все категории</a>
						</div>
						
						<div class="col-xs-12 col-sm-4 col-md-2 col-lg-1">
                          <div class="pull-right row">
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
									$my_post_args = array( 'post_type' => 'classified', 's' => $str, 'post_status' => 'publish', 'posts_per_page' => $postPerPage, 'tax_query' => array( array( 'taxonomy' => 'classified_cat', 'field' => 'id', 'terms' => $curCat_id ) ) );
	
									$my_querys = null;
									$my_querys = new WP_Query($my_post_args);
	
									if( $my_querys->have_posts() ) {
										while ($my_querys->have_posts()) : $my_querys->the_post();
										
											if( check_post_expiration( get_the_ID(), "classified" ) > 0 ) {
												array_push($my_post_array, get_the_ID());
											}
	
										endwhile;
									}
									wp_reset_query();
								  
								  query_posts(
 							         array('post_type'=>'classified','s'=>$str,'post_status'=>'publish','post__in' => $my_post_array,'posts_per_page'=>$postPerPage,
								      'tax_query' => array(
											array(
												'taxonomy' => 'classified_cat',
												'field'    => 'id',
												'terms'    => $curCat_id,
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
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12 fixed-height">
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
				</div>
			</div><!-- end .row -->
					<?php get_sidebar(); ?>

		</div><!-- end col-lg-9 -->
	</div><!-- end .row -->
</div><!-- end .container -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
        <h4 class="modal-title" id="myModalLabel">Выберите категорию для нового объявления</h4>
      </div>
      <div class="modal-body">
        <div class="row">
        	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        		Авто
        		<div class="radio">
        			<label>
        				<input type="radio" name="category1" id="input" value="" checked="checked">
        				Куплю
        			</label>
        		</div>
        		<div class="radio">
        			<label>
        				<input type="radio" name="category1" id="input" value="">
        				Продаю
        			</label>
        		</div>
        		<div class="radio">
        			<label>
        				<input type="radio" name="category1" id="input" value="">
        				Обмен
        			</label>
        		</div>
        	</div>
        	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        		Недвижимость
        		<div class="radio">
        			<label>
        				<input type="radio" name="category1" id="input" value="" checked="checked">
        				Куплю
        			</label>
        		</div>
        		<div class="radio">
        			<label>
        				<input type="radio" name="category1" id="input" value="">
        				Продаю
        			</label>
        		</div>
        		<div class="radio">
        			<label>
        				<input type="radio" name="category1" id="input" value="">
        				Обмен
        			</label>
        		</div>
        	</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Сохранить изменения</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(e) {
		jQuery("#select_category").change(function(){
			window.location = jQuery(this).val();
		});
		
		//Show Noof bussiness  
		jQuery('#show-noof-business').on("change",function() {
		
			var noofbusiness = jQuery(this).val(); 
			if(noofbusiness)	  
			{
				var currentPageUrl = encodeURIComponent('<?php echo current_page_url(); ?>');
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