<?php
/**
 * The sidebar containing the main widget area
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<aside id="sidebar">
  <p class="title">Добавляйтесь к нам!</p>
  <div class="text-center">
	<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-2' ); ?>
    <?php endif; ?>
    <?php /*?><img src="<?php bloginfo('template_url')?>/images/social.png"><?php */?>
  </div>
 <!-- 
  <div class="bannerside"> <img src="http://dummyimage.com/250x150/4d494d/686a82.gif&text=BANNER 250x150" alt="BANNER 250x150"> </div>
  <div class="bannerside"> <img src="http://dummyimage.com/250x150/4d494d/686a82.gif&text=BANNER 250x150" alt="BANNER 250x150"> </div>-->
<!--  <p class="title">Наш инстаграмм</p>
  <iframe src="http://snapwidget.com/in/?u=bmFzaHZhbmNvdXZlcnxpbnw4MHwzfDR8fG5vfDV8bm9uZXxvblN0YXJ0fHllc3xubw==&ve=041215" title="Instagram Widget" class="snapwidget-widget" allowTransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:255px; height:340px"></iframe>
  <p class="title">Голосование на сайте</p>
  <div class="block">
    <p class="title3">Как часто вы заходите в<br>
      Tim Hortons?</p>
    <form action="" method="POST" role="form">
      <div class="radio">
        <label>
        <input type="radio" name="vote" value="once" checked="checked">
        1 раз в неделю </label>
      </div>
      <div class="radio">
        <label>
        <input type="radio" name="vote" value="twice" checked="checked">
        2-4 раза в неделю </label>
      </div>
      <div class="radio">
        <label>
        <input type="radio" name="vote" value="daily" checked="checked">
        Захожу каждый день </label>
      </div>
      <div class="text-center top-buffer">
        <button type="submit" class="btn btn-primary">Отправить</button>
      </div>
    </form>
    <p class="results">Посмотреть результаты</p>
    <p class="results"><a href="#">Архивы голосований</a></p>
  </div>-->
  
 <!--<div class="fb-page" data-href="https://www.facebook.com/nashvancouver" data-tabs="timeline" data-height="350" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/nashvancouver"><a href="https://www.facebook.com/nashvancouver">Наш Ванкувер</a></blockquote></div></div>-->
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</aside>