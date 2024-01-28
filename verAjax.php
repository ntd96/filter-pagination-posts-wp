<!-- Include Pagination.js library -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.6.0/pagination.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.6.0/pagination.min.js"></script>

<div id="categories-verse">
	<div id="loading-spinner"><i class="fas fa-spinner fa-spin"></i></div>
	<button class="category-filter-verse" data-category-id="16">Thiền Sư Diệu Thiện</button>
	<button class="category-filter-verse" data-category-id="17">Thiền Sư Thông Hội</button>
	<button id="view-all-verse">Xem Tất Cả</button>
</div>
<div id="filtered-posts-verse">
</div>
<div id="pagination-container"></div>


<script>
	// Thêm script này vào footer của trang web hoặc nơi bạn muốn sử dụng
	jQuery(document).ready(function($) {
		const filteredPosts = $('#filtered-posts-verse');
		const loadingSpinner = $('#loading-spinner');

		function loadPosts(categoryId, page) {
			$('#filtered-posts-verse').css({
				opacity: 0,
				display: 'flex'
			});
			$.ajax({
				type: 'post',
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				data: {
					action: 'load_posts_by_category_verse',
					category_id: categoryId,
					page: page
				},
				success: function(response) {
					filteredPosts.html(response.posts_html).fadeIn(200);
					loadingSpinner.hide();
					// Xử lý phân trang
					initPagination(response.total_pages, categoryId, page);
					$('#filtered-posts-verse').animate({
						opacity: 1
					}, 200);
				},
			});

		}

		function initPagination(totalPages, categoryId, currentPage) {
			let pageNumbers = [];
			// Sử dụng vòng lặp để thêm số từ 1 đến totalPages vào mảng
			for (let i = 1; i <= totalPages; i++) {
				pageNumbers.push(i);
			}
			paginationInstance = $('#pagination-container').pagination({
				dataSource: pageNumbers,
				pageSize: 1,
				pageNumber: currentPage, // Thiết lập trang hiện tại
				callback: function(data, pagination) {
					var newPage = pagination.pageNumber;
					if (newPage !== currentPage) {
						loadingSpinner.show();
						loadPosts(categoryId, newPage);
					}
				}
			});
		}

		// Click item
		$('.category-filter-verse').on('click', function() {
			var categoryId = $(this).data('category-id');
			$('.category-filter-verse, #view-all-verse').removeClass('active-option');
			$(this).addClass('active-option');
			$('#loading-spinner').show();
			loadPosts(categoryId, 1);
		});

		// Click viewall
		$('#view-all-verse').on('click', function() {
			var categoryId = -1;
			$('.category-filter-verse, #view-all-verse').removeClass('active-option');
			$(this).addClass('active-option');
			$('#loading-spinner').show();
			loadPosts(categoryId, 1);
		});


		setInterval(() => {
			$('.read-more-btn').click(function() {
				$('#custom-popup').fadeIn(200);
			});
			// Đóng popup khi click vào nút đóng
			$('#close-popup').click(function() {
				$('#custom-popup').fadeOut(200);
			});
			$(document).click(function(e) {
				if ($(e.target).closest('#custom-popup').length != 0) return false;
				$('#custom-popup').hide();
			});
		})
		// Initial load
		loadPosts(-1, 1);
	});
</script>

<style>
	#categories-verse {
		margin-bottom: 30px;
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
	}

	.category-filter-verse,
	#view-all-verse {
		background-color: transparent;
		color: #6D440C;
		border: none;
		border-right: 1px solid gainsboro;
		border-radius: 0;
	}

	.category-filter-verse:hover,
	.category-filter-verse:active,
	.category-filter-verse:focus,
	#view-all-verse:hover,
	#view-all-verse:active,
	#view-all-verse:focus,
	.read-more-btn:hover,
	.read-more-btn:active,
	.read-more-btn:focus {

		background-color: #6D440C;
		color: #fff;
		outline: none;
	}

	#view-all-verse {
		border-right: none;
	}

	#filtered-posts-verse {
		width: 100%;
		display: flex;
		justify-content: flex-start;
		flex-wrap: wrap;
		gap: 15px;
		min-height: 200px
	}

	.categories-item-over p {
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 4;
		/* Số dòng tối đa trước khi hiển thị "..." */
		-webkit-box-orient: vertical;
	}

	.categories-item-verse {
		width: calc((100% / 3) - 10px);
		padding: 30px 15px;
	}

	.categories-item-verse:nth-child(odd) {
		color: #fff;
		background-color: #462C1A !important;
	}

	.categories-item-verse:nth-child(even) {
		background-color: #FDF7F4 !important;
	}

	#view-all-verse {
		color: #6D440C;
	}

	#loading-spinner {
		display: none;
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		z-index: 9999;
		font-size: 2em;
	}

	.category-filter-verse.active-option,
	#view-all-verse.active-option {
		background-color: #6D440C;
		color: #fff
	}

	#pagination-container {
		margin-top: 30px
	}

	.paginationjs {
		justify-content: center;
		align-items: center;
	}

	/* Popup	*/
	.custom-popup {
		display: none;
		position: fixed;
		z-index: 9999;
		left: 50%;
		top: 50%;
		transform: translate(-50%, -50%);
		background-color: #fefefe;
		border-radius: 5px;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		max-width: 600px;
		color: #462C1A;
		padding: 50px;
		width: 100%;
	}

	.close-popup {
		position: absolute;
		top: 10px;
		right: 10px;
		cursor: pointer;
		font-size: 20px;
		color: #aaa;
	}

	.close-popup:hover {
		color: black;
	}

	@media screen and (max-width: 768px) {
		.categories-item-verse {
			width: calc((100% / 2) - 10px);
		}

		.custom-popup {
			padding: 20px;

		}
	}

	@media screen and (max-width: 468px) {
		.categories-item-verse {
			width: 100%;
		}
	}
</style>