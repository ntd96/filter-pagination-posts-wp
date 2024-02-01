<?php

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
<!-- <div id="pagination-container"></div> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.6.0/pagination.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.6.0/pagination.min.js"></script>

<script src="https://kit.fontawesome.com/9a811ce03d.js" crossorigin="anonymous"></script>
<script>
	jQuery(document).ready(function($) {
		let loadingSpiner = $('#loading-spinner');

		function activeFirstBook() {
			let firstItem = $('.sidebar-book .title:first').data('id');
			$('.sidebar-book .title:first').addClass('active').next('.list').slideDown(200);
			loadPostsBook(firstItem, 1);
		}
		activeFirstBook();

		function loadPostsBook(term_id, page) {
			loadingSpiner.show();
			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				type: 'post',
				data: {
					action: 'load_posts_by_category_book',
					term_id: term_id,
					page: page
				},
				success: function(res) {
					resultPostsBook(res.posts_html);
					initPaginationBook(res.total_pages, term_id, page)

					if (window.innerWidth < 768) {
						// Xác định vị trí của sidebar-book .title
						var titlePosition = $('.sidebar-book .title').offset().top;
						// Cuộn trang lên đến vị trí của sidebar-book .title
						$('html, body').animate({
							scrollTop: titlePosition
						}, 1000); // Thời gian cuộn, đơn vị là mili giây
					}
				},
				complete: function() {
					// Ẩn spinner khi dữ liệu đã được load xong
					$('#loading-spinner').hide();
				}
			});
		}

		function initPaginationBook(totalPages, term_id, currentPage) {
			let pageNumbers = [];
			// Sử dụng vòng lặp để thêm số từ 1 đến totalPages vào mảng
			for (let i = 1; i <= totalPages; i++) {
				pageNumbers.push(i);
			}
			paginationInstance = $('#pagination-container-book').pagination({
				dataSource: pageNumbers,
				pageSize: 1,
				pageNumber: currentPage, // Thiết lập trang hiện tại
				callback: function(data, pagination) {
					let newPage = pagination.pageNumber;
					if (newPage !== currentPage) {
						loadingSpiner.show();
						loadPostsBook(term_id, newPage);
					}
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
			loadPostsBook(term_id, 1);
		}
		$('.sidebar-book .title').click(toggleList);

	});
</script>

<style>
	/* CSS */
	.categories-book {
		display: flex;
		align-items: center;
		gap: 20px;
	}

	.sidebar-book {
		max-width: 300px;
		width: 100%;
		padding: 50px 25px;
		background-color: #db952a57;
		border-radius: 0 40px 0 0;
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
		font-size: 16px;
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: 1;
		-webkit-box-orient: vertical;
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
		width: calc(100% - 320px);
		position: relative;
		display: flex;
		flex-wrap: wrap;
	}

	.filtered-posts-book .item-book {
		display: flex;
		flex-wrap: wrap;
		width: calc(100% / 2);
		opacity: 0;
		transition: opacity 0.3s ease;
	}

	.filtered-posts-book .item-book .content {
		padding: 0 15px;
		max-height: 250px;
		display: grid;
		height: 250px;
		align-items: center;
	}

	.filtered-posts-book .item-book .content .parent_title {
		font-size: 18px;
		font-weight: 600;
		color: #D88A12;
		font-style: italic;
	}

	.filtered-posts-book .item-book .content .title {
		font-size: 22px;
		text-transform: uppercase;
		letter-spacing: 1px;
		color: #D88A12;
		font-family: 'Raleway';
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}

	.filtered-posts-book .item-book .content .excerpt {
		font-size: 18px;
	}

	.filtered-posts-book .item-book img {
		border-radius: 5px;
		box-shadow: rgba(255, 255, 255, 0.1) 0px 1px 1px 0px inset, rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;
	}

	.filtered-posts-book .item-book .col-1 {
		width: 40%;
	}

	.filtered-posts-book .item-book .col-2 {
		width: 60%;
	}

	#pagination-container-book {
		position: absolute;
		bottom: -60px;
		left: 50%;
		transform: translateX(-50%);
	}

	.paginationjs {
		justify-content: center;
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

	@media screen and (max-width: 1200px) {
		.filtered-posts-book .item-book {
			width: 100%;
		}
	}

	@media screen and (max-width: 992px) {
		.filtered-posts-book .item-book .content {
			align-content: center;
		}
	}

	@media screen and (max-width: 768px) {
		.categories-book {
			flex-wrap: wrap;
		}

		.sidebar-book {
			max-width: 100%;
			width: 100%;
			border-radius: 0;
			padding: 15px 25px;
			display: flex;
			justify-content: space-evenly;
			align-items: center;
			white-space: nowrap;
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
		}

		.sidebar-book .list,
		.sidebar-book .fa-angle-down {
			display: none !important;
		}

		.sidebar-book .title {
			margin: 0
		}

		.filtered-posts-book {
			width: 100%;
		}

		.filtered-posts-book .item-book {
			margin-bottom: 25px;
		}

		.filtered-posts-book .item-book .col-1,
		.filtered-posts-book .item-book .col-2 {
			width: 100%;
		}

		.filtered-posts-book .item-book .col-1 {
			text-align: center;
		}

		::-webkit-scrollbar {
			display: none;
		}
	}

	</style