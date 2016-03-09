<?php 
/*
Template Name: Event Template
*/
 ?>
<?php get_header(); ?>
<div class="container classified-inside event-inside">
    <div class="row">
        <?php get_sidebar(); ?>
               <div class="top-buffer2 col-xs-9 col-sm-9 col-md-9 col-lg-9">
        
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <span>Самые ближайшие события</span>
                            <div class="pull-right">
                              <?php
							    if(is_user_logged_in())
								{
								  echo '<a href="'.get_permalink(get_page_by_title('Event Add')).'">Добавить новое</a>';
								}
								else
								{							 
                                 echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Добавить новое</a>';
                                }?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row event mr-more-events-block">
                         <?php
						 	$flag = 1;
						 	query_posts(array('post_type'=>'event','post_status'=>'publish','posts_per_page'=>'6'));
								   
							 if(have_posts())
							   {
									while(have_posts())
									{
									   the_post();											
									   $eventId = get_the_ID(); // Event id 
									   $website =  get_post_meta($eventId,"website",true);
									   $evPhone =  get_post_meta($eventId,"phone",true);
									   $evLocation =  get_post_meta($eventId,"location",true);
									   $evPrice =  get_post_meta($eventId,"price",true);							   								   		
							 ?>
                                    <div class="col-sm-4 col-md-4 col-lg-4 event-block">
                                        <div class="event-block-thumb">
                                            <div class="text_block">
                                                <p class="pull-left">Поделититесь с друзьями</p>
                                                <ul>
                                                    <li><a href="#" class="facebook">Facebook</a></li>
                                                    <li><a href="#" class="twitter">Twitter</a></li>
                                                </ul>
                                            </div>
                                            <a href="<?php the_permalink(); ?>">
											<?php 
                                                if(has_post_thumbnail())
                                                {
                                                the_post_thumbnail('thumbnail',false,false);
                                                }
                                                else
                                                {
                                                 echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">'; 
                                                }
                                                ?> 
                                                <div class="opacity">
                                                    <div class="event-info text-center">
                                                        <h3><?php the_title(); ?></h3>
                                                        <p>Спектакль</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="event-block-content">
                                            <p class="date text-center"><strong>23</strong> Января 5:00 PM</p>
                                            <p><?php the_excerpt(); ?></p>
                                        </div>
                                         <div class="bg-info event-block-location col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                           <p class="location">
										   <?php if(!empty($evLocation['address']))
							                     { echo $evLocation['address'].", OR"; }
											   ?>
                                             </p>
                                        </div>
                                        <div class="event-buybtn-out bg-primary text-center col-xs-12 col-sm-12 
                                        col-md-12 col-lg-12">
                                            <p>Стоимость билетов от <strong>
											<?php if($evPrice){ echo "$". $evPrice; }?></strong><p>
                                            <button class="btn btn-primary" type="button">Купить</button>
                                        </div>
                                    </div>
                             <?php
							 		if($flag % 3 == 0) { echo '<div class="clear"></div>'; }
									$flag++;
								}
							   }
							   wp_reset_query();
							 ?>
                        </div>
                        <?php
						  $eventQryc =query_posts(array('post_type'=>'event','post_status'=>'publish'));
						  if(count($eventQryc)>6)
						  {
						?>
                        <div class="clear text-center more no-padding">
                         <a href="#" id="mr-more-events-btn">Показать еще
                           <img src="<?php bloginfo('template_url');?>/images/arrow_down.png"></a>
                        </div>                        
  					  <?php } ?>
                    </div>
                    </div>
                </div>
            </div><!-- end .row -->
        </div><!-- end col-lg-9 -->
    </div>
<script type="text/javascript">
 //Code for load more posts
 jQuery(document).ready(function()
 {
	var pageno =3;	
	jQuery("#mr-more-events-btn").click(function(event)
	{	   
	   event.preventDefault();	
       var catids = jQuery(this).attr('data-cat-ids');
	   var cpid = jQuery(this).attr('data-curr-postid');

	  jQuery.ajax({
		  url: "<?php echo get_template_directory_uri(); ?>/inc/custom-result.php?noofevents="+pageno,
		  method: "POST",
		  dataType: "html",
		  success: function(result)
	      {
			if(jQuery(".mr-more-events-block").children('.No-moreposts').length>0)
			{		
				jQuery('#mr-more-events-btn').hide();
			}
			else
			{
			 jQuery(result).appendTo(".mr-more-events-block").show(2000);	
			}
	      }
	 });
	  pageno++;  
	});
	
 });
</script>
<?php get_footer(); ?>