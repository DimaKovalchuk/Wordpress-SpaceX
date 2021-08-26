<!-- подключить заголовок -->
<?php get_header(); ?>
 
<div class="container">
	<!-- нужен цикл, хотя там только один пост -->
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="row">
		<div class="col-xs-12">
			<!-- показать заголовок поста -->
			<h1><?php the_title(); ?></h1>
			<p class="author">
				<!-- показать автора -->
				Автор поста: <?php the_author(); ?> &bull;
				<!-- показать дату опубликованной статьи -->
				Опубликовано: <?php echo get_the_date(); ?> &bull;
				<!-- показать количество комментариев -->
				<?php comments_number(); ?>
				<!-- показывать метки, присвоенные статьи -->
				<?php the_tags(); echo get_post_meta($post->ID, 'meta1_field_1', true); ?>
			</p>
			<hr>
			<p><b>Дата:</b> <?php echo get_post_meta($post->ID, 'meta1_data', true); ?></p>
			<p><b>ракета:</b> <?php echo get_post_meta($post->ID, 'meta2_rocet', true); ?></p>
			<img src="<?php echo get_post_meta($post->ID, 'meta1_picture_url', true); ?>" >
			<hr>
		</div>
	</div>
						
	<div class="row">
		<div class="col-xs-12">
			<div class="content">
				<!-- показать содержание статьи -->
				<?php the_content(); ?>
				<a href="https://www.youtube.com/watch?v=<?php echo get_post_meta($post->ID, 'meta1_video_url', true); ?>">Видео материал</a>
			</div>
		</div>
	</div>
	<hr>
	<!-- если комментарии разрешены, показать комментарии -->
	<?php if (comments_open() || get_comments_number()): ?>
	<div class="well">
		<?php comments_template(); ?>
	</div>
	<?php endif; ?>
 
	<?php endwhile; endif; ?>
</div>
 
<!-- подключить подвал -->
<?php get_footer(); ?>