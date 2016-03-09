<?php 
/*
Template Name: Video Gallery */
 ?>
<?php get_header(); ?>
<div id="main-container" class="container classified-inside videogallery-inside">
	<div class="row top-buffer2">
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<span>Видеогалерея</span>
							<!--<a href="#" class="pull-right">Добавить новое</a>-->
                            <?php
							if(is_user_logged_in())
							{
							  echo '<a class="pull-right" href="'.admin_url().'post-new.php?post_type=videogallery">Добавить новое</a>';
							}
							else
							{							 
							 echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);"
							 class="pull-right">Добавить видео</a>';
							}?>
						</div>					
					</div>
                    
					 <div class="row">
                         
                       <div class="mr-video-gallery-out"> 
					   <?php
                          query_posts(
                           array('post_type'=>'videogallery','post_status'=>'publish','posts_per_page'=>'8'));
                           
                           if(have_posts())
                           {
                                while(have_posts())
                                {
                                 the_post();
								 $videoId = get_the_ID();
						       ?>
                                 <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mr-video-bx-outer">                                   <?php
									 if(has_post_thumbnail()) 
									 { ?>
                    <a href="<?php the_permalink(); ?>">
                      <?php the_post_thumbnail(array(252, 252)); ?>
                      <div class="text_block" style="width:92%;">
                        <h2><?php echo mb_strimwidth(get_the_title(),0,44,'...'); ?></h2>
                        <ul class="info">
                          <li><span class="glyphicon glyphicon-eye-open"></span><?php echo pvc_get_post_views(get_the_ID()); ?></li>
                          <li><?php echo number_format_i18n( get_comments_number( get_the_ID() ) ); ?></li>
                        </ul>
                      </div>
                    </a>
				<?php } ?>
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
<?php get_footer(); ?>