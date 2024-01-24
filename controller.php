<?php

// functions.php
function load_posts_by_category_verse()
{
$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;

$args = array(
'cat'            => ($category_id != -1) ? $category_id : 15,
'posts_per_page' => 6, // Số bài viết trên mỗi trang
'orderby'        => 'date',
'order'          => 'DESC',
'paged'          => $page,
);

$query = new WP_Query($args);

if ($query->have_posts()) :
ob_start();
while ($query->have_posts()) : $query->the_post(); ?>
<div class="categories-item">
<h3><?php the_title(); ?></h3>
<p><?php the_content(); ?></p>
<a href="<?php echo get_permalink(); ?>">Xem thêm</a>
</div>
<?php endwhile;
wp_reset_postdata();
$posts_html = ob_get_clean();

$total_pages = $query->max_num_pages;

wp_send_json(array(
'posts_html'  => $posts_html,
'total_pages' => $total_pages,
));
else :
echo 'Không có bài viết nào trong danh mục.';
endif;

die();
}

add_action('wp_ajax_load_posts_by_category_verse', 'load_posts_by_category_verse');
add_action('wp_ajax_nopriv_load_posts_by_category_verse', 'load_posts_by_category_verse');
