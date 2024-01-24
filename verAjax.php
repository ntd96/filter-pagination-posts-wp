<!-- Include Pagination.js library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.6.0/pagination.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.6.0/pagination.min.js"></script>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.min.js"></script> -->
<div id="categories">
<div id="loading-spinner"><i class="fas fa-spinner fa-spin"></i></div>
<button class="category-filter" data-category-id="16">Thiền Sư Diệu Thiện</button>
<button class="category-filter" data-category-id="17">Thiền Sư Thông Hội</button>
<button id="view-all">Xem Tất Cả</button>
</div>
<div id="filtered-posts"></div>
<div id="pagination-container"></div>

<script>
// Thêm script này vào footer của trang web hoặc nơi bạn muốn sử dụng
jQuery(document).ready(function ($) {
    const filteredPosts = $('#filtered-posts');
    const loadingSpinner = $('#loading-spinner');
    let isLoading = false;
    let paginationInstance = null; // Lưu trữ thể hiện của phân trang

    function loadPosts(categoryId, page) {
        filteredPosts.fadeOut(200, function () {
            $.ajax({
                type: 'post',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: { action: 'load_posts_by_category_verse', category_id: categoryId, page: page },
                success: function (response) {
                    filteredPosts.html(response.posts_html).fadeIn(200);
                    loadingSpinner.hide();
                    // Xử lý phân trang
                    initPagination(response.total_pages, categoryId, page);
                },
            });
        });
    }

    function initPagination(totalPages, categoryId, currentPage) {
        if (paginationInstance !== null) {
            paginationInstance.pagination('destroy'); // Rỗng phân trang cũ
        }

        paginationInstance = $('#pagination-container').pagination({
            dataSource: Array.from({ length: totalPages }, (_, i) => i + 1),
            pageSize: 1,
            pageNumber: currentPage, // Thiết lập trang hiện tại
            callback: function (data, pagination) {
                var newPage = pagination.pageNumber;
                if (newPage !== currentPage) { // Chỉ gọi khi trang mới khác trang hiện tại
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
    gap: 10px;
    justify-content: center;
}

#filtered-posts {
    width: 100%;
    display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;
    gap: 15px;
}

.categories-item {
    width: calc((100% / 3) - 10px);
    padding: 30px 15px;
}

.categories-item:nth-child(odd) {
    color: #fff;
    background-color: #462C1A !important;
}

.categories-item:nth-child(even) {
    background-color: #FDF7F4 !important;
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
    background-color: aqua !important;
}
</style>
