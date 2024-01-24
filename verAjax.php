<!-- Include Pagination.js library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.6.0/pagination.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.6.0/pagination.min.js"></script>

<div id="categories">
	<div id="loading-spinner"><i class="fas fa-spinner fa-spin"></i></div>
	<button class="category-filter" data-category-id="16">Thiền Sư Diệu Thiện</button>
	<button class="category-filter" data-category-id="17">Thiền Sư Thông Hội</button>
	<button id="view-all">Xem Tất Cả</button>
</div>
<div id="filtered-posts">
</div>
<div id="pagination-container"></div>


<script>
	// Thêm script này vào footer của trang web hoặc nơi bạn muốn sử dụng
	jQuery(document).ready(function ($) {
		const filteredPosts = $('#filtered-posts');
		const loadingSpinner = $('#loading-spinner');
		function loadPosts(categoryId, page) {

			 $('#filtered-posts').css({ opacity: 0, display: 'flex' });
				$.ajax({
					type: 'post',
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: { action: 'load_posts_by_category_verse', category_id: categoryId, page: page },
					success: function (response) {
						filteredPosts.html(response.posts_html).fadeIn(200);
						loadingSpinner.hide();
						
						// Xử lý phân trang
						initPagination(response.total_pages, categoryId, page);
						$('#filtered-posts').animate({ opacity: 1 }, 200);
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
				callback: function (data, pagination) {
					var newPage = pagination.pageNumber;
					if (newPage !== currentPage) { 
						loadingSpinner.show();
						loadPosts(categoryId, newPage);
					}
				}
			});
		}

		// Click item
		$('.category-filter').on('click', function () {
			var categoryId = $(this).data('category-id');
			$('.category-filter, #view-all').removeClass('active-option');
			$(this).addClass('active-option');
			$('#loading-spinner').show();
			loadPosts(categoryId, 1);
		});

		// Click viewall
		$('#view-all').on('click', function () {
			var categoryId = -1;
			$('.category-filter, #view-all').removeClass('active-option');
			$(this).addClass('active-option');
			$('#loading-spinner').show();
			loadPosts(categoryId, 1);
		});

		// Initial load
		loadPosts(-1, 1);


	});

</script>

<style>
	#categories {
		margin-bottom: 30px;
		display: flex;
		justify-content: center;
	}
	.category-filter, #view-all {
		background-color: transparent;
		color: #6D440C;
		border: none;
		border-right: 1px solid gainsboro;
		border-radius: 0;
	}
	.category-filter:hover,.category-filter:active, .category-filter:focus , #view-all:hover, #view-all:active , #view-all:focus{
		background-color: #6D440C;
		color: #fff;
		outline:none;
	}
	#view-all {
		border-right:none;
	}
	#filtered-posts {
		width: 100%;
		display: flex;
		justify-content: flex-start;
		flex-wrap: wrap;
		gap: 15px;
		min-height: 200px
	}

	.categories-item {
		width: calc((100% / 3) - 10px);
		padding: 30px 15px;
	}
	.categories-item:nth-child(odd) {
		background-color: #462C1A !important;
	}

	.categories-item:nth-child(even) {
		background-color: #FDF7F4 !important;
	}
	#view-all {
		color:#6D440C;
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

	.category-filter.active-option,
	#view-all.active-option {
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
</style>
