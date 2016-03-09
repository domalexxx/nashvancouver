<?php

get_header(); 

$cpostid = $post->ID;

global $wpdb;

$payHistorytab = $wpdb->prefix."payment_history";



if( check_post_expiration( $cpostid, "classified" ) <= 0 ) {

	

	$run = get_option('siteurl');

	echo '<script type="text/javascript">';

	echo "location.replace('$run');";

	echo '</script>';

}



if(isset($_REQUEST['yesdeletepost']) && $_REQUEST['yesdeletepost']) {



	wp_delete_post( $_REQUEST['deletepostid'], true );

	$_SESSION['delete_post_mess'] = "Ваше объявление успешно удалено!";	

	$run = get_permalink(get_page_by_title('Classifieds' ));

	echo '<script type="text/javascript">';

	echo "location.replace('$run');";

	echo '</script>';

}

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

                        <ul class="links text-right pull-right">

                          <li>

                                                    <?php

							if(is_user_logged_in())

							{

							  echo '<a class="pull-right" href="'.get_permalink(get_page_by_title("Classified add")).'">Добавить новое</a>';

							}

							else

							{							 

							 echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Добавить новое</a>';

							} ?>

                                                          </li>

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

							<select required class="form-control selectpicker" id="select_category" name="select_category" data-style="btn-info">

								<option value="0">Выберите категорию</option>

								 <?php

									$classified_catarr = get_terms('classified_cat', array('hide_empty' => true ));

									foreach($classified_catarr as $classcat) {

										echo '<option value="'.get_term_link( $classcat ).'">'.$classcat->name.'</option>';

									}

								?>

							</select>

						</div>

						<div class="text-center col-xs-12 col-sm-4 col-md-5 col-lg-6">

							 <?php

							if(is_user_logged_in()) {

								echo '<a class="btn btn-danger" href="'.get_permalink(get_page_by_title('Classified add')).'">Разместить новое объявление</a>';

							}

							else {							 

							 	echo '<a class="btn btn-danger" data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Разместить новое объявление</a>';

							}

							?><br><br>

						</div>

						<div class="text-right col-xs-12 col-sm-4 col-md-3 col-lg-3">

							<a class="btn btn-primary" href="<?php echo get_permalink(get_page_by_title('Classifieds'));?>">Все категории</a>

						</div>

					</div>

                    

					<?php if(isset($_GET['search']) && !empty($_GET['search'])) { ?>

					

						<div class="row">

						 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                <?php

									$my_post_array = array("-99");

									$my_post_args = array( 'post_type' => 'classified', 'post_status' => 'publish', 'posts_per_page' => -1, 's' => $str );



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

									

								  query_posts(array('post_type' => 'classified', 's' => $str, 'post_status' => 'publish', 'posts_per_page' => -1, 'post__in' => $my_post_array));

								   

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

											<?php if(!empty($clsLocation['address'])) { echo '<span>'.$clsLocation['address'].'</span>'; } ?>

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

								   $emailAddress =  get_post_meta($classId,"emailaddress",true);

								   $mapinfo = get_post_meta($classId,"info",true);

								   $clsCatList = get_the_terms($classId,'classified_cat'); // Categories

								   

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

							    <div id="classified">

								<div class="form-group" style="margin-bottom:0;">

									<h1><?php the_title(); ?></h1>

									<span class='review'><?php 

									   if(function_exists("pvc_get_post_views"))

									   { 

									    echo pvc_get_post_views($classId);

									   } 

									 ?></span><span class='glyphicon glyphicon-eye-open'></span>

									<span class='data'><?php echo get_the_date('d/m/y');?></span>

								</div>

								

									

                                    <?php $postdata = get_post( $classId, ARRAY_A ); ?>

									 <?php if( is_user_logged_in () && get_current_user_id() == $postdata['post_author'] ) { ?>
									 <div class="bg-expire-2">Размещение вашего объявления заканчивается через <?php echo ceil($numDays); ?> дня
										<?php if ( get_post_status ( get_the_ID() ) == 'pending' ) { ?>
										<span class="label label-right" style="color:#e21922;"><strong>На рассмотрении</strong></span>
										<?php } ?>
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

                                    

          						

							<div class="row upload" style="margin-top:15px;">

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

										

                                        <?php if($clsPrice) { ?>

                                            <div class="form-group">

                                                <div class="price text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label">Стоимость:</div>

                                                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-8">

                                                    <span><?php echo $clsPrice; ?></span>

                                                </div>

                                            </div>

                                        <?php } ?>

                                        

                                        <?php if(is_array($clsCatList)) { ?>

                                            <div class="form-group">

                                                <div class="category text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label">Категория:</div>

                                                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-8">

                                                    <span>

                                                        <?php

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

                                                        ?>

                                                    </span>

                                                </div>

                                            </div>

                                        <?php } ?>

                                        

                                        <?php if($web_site) { ?>

                                            <div class="form-group">

                                                <div class="website text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">Вебсайт:</div>

                                                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-8">

                                                    <span><a target="_blank" href="<?php echo $web_site; ?>"><?php echo $web_site; ?></a></span>

                                                </div>

                                            </div>

                                        <?php } ?>

                                        

                                        <?php if($clsPhone) { ?>

                                            <div class="form-group">

                                                <div class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">Телефон:</div>

                                                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-8">

                                                    <span><?php echo $clsPhone; ?></span>

                                                </div>

                                            </div>

                                         <?php } ?>

										 

										 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 error-container" style="display:none;">

											<h4 style="font-size:17px;" class="error">Не заполнены обязательные поля</h4>

											<ol>

												<li><label for="eventTitle" class="error">Please enter your name.</label></li>

												<li><label for="websiteUrl" class="error">Please enter your email.</label></li>

												<li><label for="organiser" class="error">Please enter your comment.</label></li>

											</ol>

										</div>

										

										<div class="text-center form-group">

											<div class="col-lg-12">

												<form role="form" style="max-width:80%;margin:auto;" name="contact-us-form" id="contact-us-form" method="post">

                                                	

                                                    <div id="sentemail" class="alert alert-success" style="display:none;"><?php _e( 'Сообщение успешно отправлено.', 'nashvan' ); ?></div>

													

                                                    <input type="hidden" value="<?php if($emailAddress) { echo $emailAddress; } else { echo get_option('admin_email'); } ?>" name="receiver_email" id="receiver_email">

													<div class="form-group">

														<input type="text" style="height:32px;" class="form-control" id="your_name" name="your_name" placeholder="Ваше имя" required />

													</div>

													

													<div class="form-group">

														<input type="email" class="form-control" style="height:32px;" id="your_email" name="your_email" placeholder="Ваш email" required />

													</div>

													

													<div class="form-group">

														<textarea class="form-control" style="height:100px;" id="your_message" rows="5" name="your_message" placeholder="Ваше сообщение" required></textarea>

													</div>

													

													<button type="submit" class="btn btn-primary" value="Отправить сообщение" name="sendmessage" id="sendmessage">Отправить сообщение</button>

												</form>

											</div>

										</div>

								</div>

							</div>

							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

								<div class="text col-xs-12 col-sm-12 col-md-12 col-lg-12">

									<p><?php the_content(); ?></p>

								</div>

							</div>

							

							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

									<p class="title4">Расположение:</p>

									<p class="location"> <img src="<?php echo bloginfo('template_url'); ?>/images/location.png">

										<?php if(!empty($clsLocation['address'])) { echo '<span class="mr-gmap-location-pop1">'.$clsLocation['address'].'</span>'; } ?>

									</p>

								</div>

							</div>

							<div class="clearfix"></div>

							<div id="sbussiness-google-map"></div>

							<p class="mapInfo"><?php echo $mapinfo; ?></p>							

							</div>

                            <?php

							      }								  

							 }

							?>

                         <!-- Classified content block close-->   

                            </div>

                            </div>

							

                            </div>

							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

								<div class="overflow">

									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

										<div class="title pull-left">

											<span>Похожие объявления</span>

										</div>

										<ul class="links text-right pull-right">

											<li>

											<?php

											if(is_user_logged_in())

											{

											echo '<a class="pull-right" href="'.get_permalink(get_page_by_title("Classified add")).'">Добавить новое</a>';

											}

											else

											{							 

											echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Добавить новое</a>';

											}?>

											</li>

										</ul>

									</div>

								</div>

								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

											<?php

											$my_post_array = array("-99");

											$my_post_args = array( 'post_type' => 'classified', 'post__not_in' => array($cpostid), 'post_status' => 'publish', 'posts_per_page' => 3, 'tax_query' => array( array( 'taxonomy' => 'classified_cat', 'field' => 'id', 'terms' => $classifiedCat ) ) );

		

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

											

											  query_posts(array('post_type'=>'classified','post__not_in' => array($cpostid), 'post__in' => $my_post_array, 'post_status'=>'publish', 'posts_per_page'=>'3',

													  'tax_query' => array(

														array(

															'taxonomy' => 'classified_cat',

															'field'    => 'id',

															'terms'    => $classifiedCat,

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

													<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12 fixed-height">

														<p><?php the_excerpt();?></p>

													</div>

													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

														<span><?php echo $clsPhone; ?></span>

														<span>$<?php echo $clsPrice; ?></span>

														<?php if(!empty($clsLocation['address'])) { echo '<span>'.$clsLocation['address'].'</span>'; } ?>

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

													echo '<div class="no-classified text col-xs-12 col-sm-12 col-md-12 col-lg-12"><p class="text-center">В данном разделе пока нет объявлений</p></div>';

											   }

											?>

											<!--Display more classifieds -->

											<div class="mr-more-classifieds-block">                                    

											</div>

																</div>

          </div>	



                              <!--Close -->

                              <?php

							  $ttclassQry = query_posts(array('post_type'=>'classified','post__not_in' => array($cpostid), 'post__in' => $my_post_array, 'post_status'=>'publish', 'tax_query' => array( array('taxonomy' => 'classified_cat', 'field' => 'id', 'terms' => $classifiedCat))));

								if(count($ttclassQry)>3)

								{

							  ?>

								<div class="clear text-center more no-padding">

                                <a href="#" id="mr-more-classifieds-btn" data-curr-postid="<?php echo $cpostid; ?>" data-cat-ids="<?php echo serialize($classifiedCat);?>">Показать еще<img src="<?php echo bloginfo('template_url');?>/images/arrow_down.png"></a>

                                </div>

                               <?php } ?>

							</div>

						</div>

					<?php } ?>

			<!-- </div>end .row -->

					<?php get_sidebar(); ?>

                    

                    <!-- delete modal -->

                    <div class="modal fade" id="deleteAdsModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdsModalLabel">

                        <div class="modal-dialog" role="document">

                            <form name="deleteform" id="deleteform" method="post">

                                <div class="modal-content text-center">

                                    <div class="modal-header">

                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>

                                        <h4 class="title4">Ваше объявление будет удалено!</h4>

                                    </div>

                                    <div class="modal-footer">

                                        <input type="hidden" name="deletepostid" id="deletepostid" />

                                        <button type="submit" id="yesdeletepost" class="btn btn-danger" name="yesdeletepost" value="Да я подтверждаю">Да я подтверждаю</button>

                                        <button type="button" class="btn btn-default cancel" data-dismiss="modal">Отменить</button>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                    <!-- end of delete modal -->



		</div><!-- end col-lg-9 -->

	</div><!-- end .row -->

</div><!-- end .container -->

<?php $clsLocation1 =  get_post_meta($cpostid,"location",true); ?>

<script type="text/javascript">



	function rinitMap(aDlat,aDlng)

	{ 

	   if(aDlat)

	   {aDlat=aDlat;}else{aDlat=62.120327;}		   

	  

	  if(aDlng)

	   {aDlng=aDlng;}else{aDlng=99.25048828125;}		   

	

		map = new google.maps.Map(document.getElementById('sbussiness-google-map'), {

		center: {lat:parseFloat(aDlat), lng:parseFloat(aDlng)},

		zoom: 10

	  });	  

		

		marker = new google.maps.Marker({

			map: map,

			draggable: true,

			animation: google.maps.Animation.DROP,

			position: {lat:parseFloat(aDlat), lng: parseFloat(aDlng)}

		});

	}

	

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

	

	<?php if( !empty($clsLocation1) ) { ?>

		setTimeout(function() { rinitMap(aDlat='<?php echo $clsLocation1["lat"]; ?>',aDlng='<?php echo $clsLocation1["lng"]; ?>'); },2000);

	<?php } ?>


	/* delete business code */

	 jQuery(".deletepostlink").click(function(){			

		jQuery("#deleteAdsModal").modal("show");

		jQuery("#deletepostid").val( jQuery(this).attr("data-id") );

	 });

	

	var container = jQuery('div.error-container');

	// validate the form when it is submitted

	var validator = jQuery("#contact-us-form").validate({

		rules: {

			your_name: "required",

			your_message: "required",

			your_email: {

				required: true,

				email: true

			},

			your_message: "required"

		},

		messages: {


			your_name: "Введите ваше имя",

			your_message: "Укажите ваш email адрес",

			your_email: "Напишите ваше сообщение"

		},

		errorContainer: container,

		errorLabelContainer: jQuery("ol", container),

		wrapper: 'li',

		submitHandler: function() {

			

			var receiver_email = jQuery('#receiver_email').val();

			var your_name      = jQuery('#your_name').val();

			var your_email     = jQuery('#your_email').val();

			var your_message   = jQuery('#your_message').val();

	

			jQuery.ajax({

				type:'post',

				url:'<?php echo get_bloginfo('stylesheet_directory'); ?>/send_email.php',

				data: {receiver_email:receiver_email,your_name:your_name,your_email:your_email,your_message:your_message},

				success:function(result) {

					jQuery( "#sentemail" ).show().fadeOut(5000);

					jQuery("#contact-us-form")[0].reset();

				}

			});

		}

	});

 });

</script>



<?php get_footer(); ?>