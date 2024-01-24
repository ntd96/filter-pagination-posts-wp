<?php
// Danh sách các category
$categories = array('hoat-dong-chuyen-mon', 'hoat-dong-lap-dat-bao-hanh-bao-tri', 'hoat-dong-thien-nguyen');

foreach ($categories as $category) :
    $args = array(
        'category_name'  => $category,
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);
    if ($query->have_posts()) : ?>
        <!--         <div class="container-<?php sanitize_title($category) ?> "> -->
        <?php
        while ($query->have_posts()) :
            $query->the_post();
            // Lấy info tụi blog
            $post_category = get_the_category();
            $current_category_slug = $post_category[0]->slug;
            $post_category_name = !empty($post_category) ? esc_html($post_category[0]->name) : ''; //
            $post_title = get_the_title(); // Lấy title
            $post_time = get_the_time('F j, Y'); // format time
            $post_excerpt = get_the_excerpt(); // Mô tả ngắn
            $post_permalink = get_permalink(); // link
            $thumbnail_url = get_the_post_thumbnail_url(null, 'large'); // thumb
            if ($current_category_slug === 'hoat-dong-chuyen-mon') : ?>
                <div class="row-hoat-dong">
                    <div class="col-1">
                        <div class="category">
                            <?php echo $post_category_name; ?>
                        </div>
                        <h3 class="title"> <?php echo $post_title; ?> </h3>
                        <div class="time"> <i class="fa-regular fa-clock"></i> <?php echo $post_time; ?> </div>
                        <div class="excerpt"> <?php echo $post_excerpt; ?> </div>
                        <a href="<?php echo $post_permalink; ?>" class="read-more"> Xem thêm <i class="fa-solid fa-arrow-right-long"></i> </a>
                        <img style="margin-right:10px" src="https://imed.com.vn/wp-content/uploads/2024/01/image-36.png" class="patterm" alt="image">
                    </div>
                    <div class="col-2" style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');">
                        <div class="thumb"></div>
                    </div>
                </div>
            <?php
            elseif ($current_category_slug === 'hoat-dong-lap-dat-bao-hanh-bao-tri') : ?>
                <div class="row-hoat-dong">
                    <div class="col-1">
                        <div class="category">
                            <?php echo $post_category_name; ?>
                        </div>
                        <h3 class="title"> <?php echo $post_title; ?> </h3>
                        <div class="time"> <i class="fa-regular fa-clock"></i> <?php echo $post_time; ?> </div>
                        <div class="excerpt"> <?php echo  $post_excerpt; ?> </div>
                        <a href="<?php echo $post_permalink; ?>" class="read-more"> Xem thêm <i class="fa-solid fa-arrow-right-long"></i> </a>
                        <img src="https://imed.com.vn/wp-content/uploads/2024/01/image-37.png" class="patterm" alt="image">
                    </div>
                    <div class="col-2" style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');">
                        <div class="thumb"></div>
                    </div>
                </div>
            <?php
            elseif ($current_category_slug === 'hoat-dong-thien-nguyen') : ?>
                <div class="row-hoat-dong">
                    <div class="col-1">
                        <div class="category">
                            <?php echo $post_category_name; ?>
                        </div>
                        <h3 class="title"> <?php echo $post_title; ?> </h3>
                        <div class="time"> <i class="fa-regular fa-clock"></i> <?php echo $post_time; ?> </div>
                        <div class="excerpt"> <?php echo  $post_excerpt; ?> </div>
                        <a href="<?php echo $post_permalink; ?>" class="read-more"> Xem thêm <i class="fa-solid fa-arrow-right-long"></i> </a>
                        <img src="https://imed.com.vn/wp-content/uploads/2024/01/image-40.png" class="patterm" alt="image">
                    </div>
                    <div class="col-2" style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');">
                        <div class="thumb"></div>
                    </div>
                </div>
        <?php
            endif;
        endwhile; ?>
        <!--         </div> -->
<?php
        // Đặt lại trạng thái query
        wp_reset_postdata();
    else :
        echo '<div class="no-posts">Không có bài viết cho category ' . $category . '.</div>';
    endif;
endforeach;
?>

<style>
    .row-hoat-dong .col-1 {
        width: 52%;
        position: relative;
        background-color: #FDE267;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        gap: 12px;
        color: #272727;
        font-weight: 600;
        padding: 60px 70px;
    }

    .category {
        background-color: #83CBC9;
        font-size: 12px;
        padding: 7px 20px;
        border-radius: 50px;
        font-weight: 500;
        margin-bottom: 20px;
        z-index: 9;
    }

    .title {
        font-size: 38px;
        font-weight: 700;
        margin-bottom: 10px !important;
        line-height: 1;
        z-index: 9;
    }

    .time {
        font-weight: 400;
        margin-bottom: 10px;
        z-index: 9;
    }

    .fa-regular {
        margin-right: 5px;
    }

    .excerpt {
        font-size: 18px;
        line-height: normal;
        margin-bottom: 10px
    }

    .read-more {
        display: flex;
        justify-content: center;
        align-items: center;
        column-gap: 10px;
    }

    .fa-arrow-right-long {
        font-size: 18px;
    }

    .patterm {
        position: absolute;
        top: 0;
        right: 0;
    }

    .row-hoat-dong {
        display: flex;
        flex-wrap: wrap;
        box-shadow: 4px 4px 12px 0px rgba(0, 0, 0, 0.31);
        margin-bottom: 50px;
    }

    .thumb {
        height: 350px;
    }

    .row-hoat-dong .col-2 {
        max-width: 992px;
        width: 48%;
        min-width: 300px;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }

    .row-hoat-dong:nth-child(2) .col-1 {
        order: 2;
        background-color: #83CBC9;
    }

    .row-hoat-dong:nth-child(2) .category {
        background-color: #FDE267;
    }

    .row-hoat-dong:nth-child(2) .col-2 {
        order: 1;
    }

    @media screen and (max-width: 992px) {

        .row-hoat-dong .col-1 {
            width: 100%;
            padding: 35px 30px;
        }

        .row-hoat-dong .col-2 {
            width: 100%;
        }

    }
</style>

<script>
    var herobanners = document.querySelectorAll('.herobanner');
    var pageCount = herobanners.length;

    herobanners.forEach(function(e, i) {
        var pageNumber = i + 1;
        var pageIndicator = document.querySelector('.pagination-herobanner');
        pageIndicator.innerHTML =  `${pageNumber}/${pageCount}`;
    });
</script>