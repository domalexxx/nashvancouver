<?php 
/*
Template Name: Photo Gallery */
 ?>
<?php get_header(); ?>
<div class="container classified-inside videogallery-inside">
	<div class="row">
		<?php get_sidebar(); ?>
		<div class="top-buffer2 col-xs-9 col-sm-9 col-md-9 col-lg-9">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<span>Доска объявлений</span>
							<a href="#" class="pull-right">Добавить новое</a>
						</div>					
					</div>
                    
					 <div class="row">
                         
                       <div class="mr-photo-gallery-out"> 
					   <?php
                          query_posts(
                           array('post_type'=>'photogallery','post_status'=>'publish','posts_per_page'=>'-1'));
                           
                           if(have_posts())
                           {
                                while(have_posts())
                                {
                                 the_post();
								 $videoId = get_the_ID();
						   ?>
                                 <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mr-photo-bx-outer">                                    <div class="mr-galleryphoto">
                                   <?php
									 if(has_post_thumbnail()) 
									 {
									   the_post_thumbnail();	 
									 }
									 else
									 {
									   	 
									 }
								   ?>
                                   </div>
                                   <div class="pgallery-content-box">
                                     <h2 class="pgtitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                     <div class="pgsummry-txt"><?php the_excerpt(); ?></div>
                                     <div class="pg-metadata">
                                       <ul>
                                        <li><?php 
											  $pgdDate= get_the_date('d.m.y');
											  echo $pgdDate;										 
											?></li>
                                        <li></li>
                                        <li>	
										   <?php 
											//function Post view Count
											if(function_exists("pvc_get_post_views"))
											{ 
											 echo pvc_get_post_views($videoId);
											} 
											?>
                                            </li>
                                       </ul>
                                     </div>
                                   </div>
                                   
                                 </div>
                          <?php }
						   }
						 ?> 
                         <div class="clearfix"></div>
                       </div>						 	   						
					  </div>
					</div>
				</div>
			</div><!-- end .row -->
		</div><!-- end col-lg-9 -->
	</div><!-- end .row -->
</div><!-- end .container -->

<?php get_footer(); ?>