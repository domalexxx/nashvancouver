<?php get_header(); ?>

	<div class="not-found">
		<div class="container">
			<div class="content">
				<h2>Запрашиваемая страница не найдена</h2>
				<p>Это могло произойти по нескольким причинам:</p>
				<ul>
					<li>Возможно она была переименована или перенесена</li>
					<li>Мы могли случайно удалить эту страницу</li>
					<li>Возможно она устарела и больше не доступна</li>
				</ul>
				<p>Мы предлагаем вам несколько вариантов:</p>
				<ul>
					<li>Начать просмотр сайта с <a href="<?php echo get_option('siteurl'); ?>">Главной страницы</a></li>
					<li>Перейти на страницу <a href="<?php echo get_permalink(get_page_by_title('Поддержки и написать запрос')); ?>">Поддержки и написать запрос</a></li>
					<li>Вернуться на <a href="<?php echo get_option('siteurl'); ?>">Предыдущую страницу</a></li>
				</ul>
			</div>
		</div>
	</div>

<?php get_footer(); ?>