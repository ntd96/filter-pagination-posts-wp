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
function debug_to_console($data, $context = 'Debug in Console')
{
    ob_start();
    $output  = 'console.info(\'' . $context . ':\');';
    $output .= 'console.log(' . json_encode($data) . ');';
    $output  = sprintf('<script>%s</script>', $output);
    echo $output;
}
function load_posts_by_category_book()
{
    // Bắt đầu session và bất cứ công việc cần thiết khác
    // ...
    // Xác định term_id được gửi từ Ajax
    $term_id = isset($_POST['term_id']) ? $_POST['term_id'] : '';
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    // Thực hiện truy vấn để lấy danh sách bài viết tương ứng với term_id
    // Ví dụ:
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 2,
        'cat' => $term_id,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $query = new WP_Query($args);
    // Hiển thị danh sách bài viết
    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $excerpt = get_the_excerpt();
            $excerpt = wp_trim_words($excerpt, 20);
            $parent_category_name = get_category($term_id);;
        ?>
            <div class="item-book">
                <div class="col-1">
                    <div class="thumbnail">
                        <?php the_post_thumbnail('medium'); // Hiển thị hình ảnh thumbnail với kích thước medium 
                        ?>
                    </div>
                </div>
                <div class="col-2">
                    <div class="content">
                        <h2 class="parent_title"> <?php echo $parent_category_name->name; ?> </h2>
                        <h3 class="title"><?php the_title(); ?></h3>
                        <p class="excerpt"><?php echo $excerpt; ?></p>
                        <a href="<?php the_permalink(); ?>" class="read-more-button">Xem thêm</a>
                    </div>
                </div>
            </div>
            <div id="pagination-container-book"></div>
<?php
        }
        wp_reset_postdata();
        $posts_html = ob_get_clean();
        $total_pages = $query->max_num_pages;
        wp_send_json(array(
            'posts_html'  => $posts_html,
            'total_pages' => $total_pages,
        ));
    } else {
        echo 'Không có bài viết nào.';
    }
    // Kết thúc session hoặc công việc khác nếu cần
    // ...
    wp_die(); // Kết thúc kịch bản Ajax
}
add_action('wp_ajax_load_posts_by_category_book', 'load_posts_by_category_book');
add_action('wp_ajax_nopriv_load_posts_by_category_book', 'load_posts_by_category_book');
