<?php
function debug_to_console($data, $context = 'Debug in Console')
{
	ob_start();
	$output  = 'console.info(\'' . $context . ':\');';
	$output .= 'console.log(' . json_encode($data) . ');';
	$output  = sprintf('<script>%s</script>', $output);
	echo $output;
}
//Lấy thằng cha book
$subcategories = get_categories(array(
	'child_of' => 29,
	'orderby' => 'post_date',
	'order' => 'DESC',
	'hide_empty' => false
));
?>

<div class="categories-book">
	<div class="sidebar-book">
		<?php foreach ($subcategories as $sub) :
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'category__in' => array($sub->term_id)
			);
			$query = new WP_Query($args);
		?>
			<h3 class="title" data-id="<?php echo $sub->term_id; ?>"> <?php echo $sub->name ?> <i class="fa-solid fa-angle-down"></i></h3>
			<ul class="list" style="display: none">
				<?php
				if ($query->have_posts()) {
					while ($query->have_posts()) {
						$query->the_post(); ?>
						<li><a href="<?php echo the_permalink(); ?>"><?php echo the_title(); ?></a></li>
				<?php
					}
					wp_reset_postdata();
				}
				?>
			</ul>
		<?php endforeach; ?>
	</div>
	<div class="filtered-posts-book">
	</div>
	<div id="loading-spinner"><i class="fas fa-spinner fa-spin"></i></div>
</div>

<script src="https://kit.fontawesome.com/9a811ce03d.js" crossorigin="anonymous"></script>
<script>
	jQuery(document).ready(function($) {
		let loadingSpiner = $('#loading-spinner');

		function activeFirstBook() {
			let firstItem = $('.sidebar-book .title:first').data('id');
			$('.sidebar-book .title:first').addClass('active').next('.list').slideDown(200);
			loadPostsBook(firstItem);
		}
		activeFirstBook();

		function loadPostsBook(term_id) {
			$('#loading-spinner').show();
			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				type: 'post',
				data: {
					action: 'load_posts_by_category_book',
					term_id: term_id
				},
				success: function(res) {
					resultPostsBook(res);
				},
				complete: function() {
					// Ẩn spinner khi dữ liệu đã được load xong
					$('#loading-spinner').hide();
				}
			});
		}

		function resultPostsBook(res) {
			let $filteredPosts = $('.filtered-posts-book');
			$filteredPosts.html(res);
			// Lặp qua từng phần tử và áp dụng hiệu ứng fade in
			$filteredPosts.find('.item-book').each(function(index) {
				$(this).delay(200 * index).animate({
					opacity: 1
				}, 300); // Delay và fade in từng phần tử
			});
		}

		function toggleList() {
			var term_id = $(this).data('id');
			$(this).next('.list').slideToggle(200); // Open
			$(".sidebar-book .title").not(this).next(".list").slideUp(); // Close != index
			// Active Icon
			$(this).siblings('.title').find('.fa-angle-down').removeClass('active');
			$(this).find('.fa-angle-down').toggleClass('active');
			loadPostsBook(term_id);
		}


		$('.sidebar-book .title').click(toggleList);
	});
</script>

<style>
	/* CSS */
	.categories-book {
		display: flex;
		align-items: center;
	}

	.sidebar-book {
		max-width: 300px;
		width: 100%;
	}

	.sidebar-book .title {
		position: relative;
		font-size: 20px
	}

	.sidebar-book .list {
		padding-left: 10px;
		list-style-type: none;
	}

	.sidebar-book .list a {
		text-decoration: none;
		font-size: 18px
	}

	.sidebar-book .fa-angle-down {
		position: absolute;
		right: 0;
		transition: transform 0.3s ease;
	}

	.sidebar-book .fa-angle-down.active {
		transform: rotate(180deg);
	}

	.filtered-posts-book {
		width: calc(100% - 300px);
		position: relative;
		display: flex
	}

	.filtered-posts-book .item-book {
		display: flex;
		width: calc(100% / 2);
		opacity: 0;
		transition: opacity 0.3s ease;
	}

	.filtered-posts-book .item-book .content {
		padding: 0 15px;
	}

	.filtered-posts-book .item-book .content .title {
		font-size: 22px;
	}

	.filtered-posts-book .item-book .content .excerpt {
		font-size: 18px;
	}

	.filtered-posts-book .item-book .col-1 {
		width: 40%;
	}

	.filtered-posts-book .item-book .col-2 {
		width: 60%;
	}

	#loading-spinner {
		display: none;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		z-index: 9999;
		font-size: 2em;
	}
</style>