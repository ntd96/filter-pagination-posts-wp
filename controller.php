<?php
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
        while ($query->have_posts()) : $query->the_post();
            $raw_content = get_the_content();
            $trimmed_content = wp_trim_words($raw_content, 50, '...');
            $word_count = str_word_count(strip_tags($trimmed_content));
?>
            <div class="categories-item-verse">
                <?php
                if ($word_count > 48) : ?>
                    <div class="categories-item-over">
                        <h3><?php the_title(); ?></h3>
                        <p class="content-full"><?php echo the_content() ?></p>
                        <div class="read-more-container">
                            <button class="read-more-btn">Xem thêm</button>
                        </div>
                    </div>
                    <div id="custom-popup" class="custom-popup">
                        <span id="close-popup" class="close-popup">&times;</span>
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo the_content() ?></p>
                    </div>
                <?php else : ?>
                    <div class=" categories-item_less">
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo the_content() ?></p>
                    </div>
                <?php endif; ?>
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

// BOOK
function load_posts_by_category_book () {
	// Bắt đầu session và bất cứ công việc cần thiết khác
	// ...
	// Xác định term_id được gửi từ Ajax
	$term_id = isset($_POST['term_id']) ? $_POST['term_id'] : '';

	// Thực hiện truy vấn để lấy danh sách bài viết tương ứng với term_id
	// Ví dụ:
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'cat' => $term_id // Sử dụng term_id để lấy bài viết trong danh mục tương ứng
	);
	$query = new WP_Query($args);

	// Hiển thị danh sách bài viết
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			// Hiển thị tiêu đề hoặc nội dung bài viết tùy ý
			the_title();
		}
		wp_reset_postdata();
	} else {
		echo 'Không có bài viết nào.';
	}
	// Kết thúc session hoặc công việc khác nếu cần
	// ...
	wp_die(); // Kết thúc kịch bản Ajax
}
add_action('wp_ajax_load_posts_by_category_book', 'load_posts_by_category_book');
add_action('wp_ajax_nopriv_load_posts_by_category_book', 'load_posts_by_category_book');