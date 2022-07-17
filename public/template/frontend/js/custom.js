$(document).ready(function () {

    /*     activeMenu();
    
        function activeMenu() {
            let action = getUrlParam('action');
            let category_id = getUrlParam('category_id');
    
            if (category_id != null) action = 'category';
    
            let $currentMenuItem = $('.my-menu-link[data-active="' + action + '"]');
            $currentMenuItem.addClass('active');
        }
    
        function getUrlParam(key) {
            let searchParams = new URLSearchParams(window.location.search);
            return searchParams.get(key);
        } */

    $(document).on('click', '.btn-ajax-quick-view', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            type: "GET",
            url: url,
            beforeSend: function () {
                $('#quick-view-content').html('<div class="fa-5x d-flex justify-center align-items-center"><i class="fas fa-spinner fa-spin"></i></div>');
            },
            success: function (response) {
                $('#quick-view-content').html(response);
            }
        });
    });

    $(document).on('click', '.btn-ajax-addOneToCart', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                $('#totalQuantities').html(response);
                $('span#totalQuantities').notify("Sản phẩm đã được thêm vào giỏ hàng", { className: 'success', position: 'bottom-right' });
            }
        });
    });

    $(document).on('click', '#btn-ajax-addManyToCart', function (e) {
        e.preventDefault();
        var quantities = $('.quantities').val();
        var url = $(this).attr('href') + quantities;
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                $('#totalQuantities').html(response);
                $('span#totalQuantities').notify("Sản phẩm đã được thêm vào giỏ hàng", { className: 'success', position: 'bottom-right' });
            }
        });
    });

    $(document).on('click', '#btn-ajax-addItemToCart', function (e) {
        e.preventDefault();
        var quantities = $('#quantities').val();
        var url = $(this).attr('href') + quantities;
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                $('#totalQuantities').html(response);
                $('span#totalQuantities').notify("Sản phẩm đã được thêm vào giỏ hàng", { className: 'success', position: 'bottom-right' });
                $('#addtocart').modal('show');
            }
        });
    });

    $('.change-quantities').change(function () {
        let ele = $(this);
        var quantities = ele.val() - ele.data('quantities-saved');
        var url = ele.data('url') + '&quantities=' + quantities;
        $.ajax({
            type: "GET",
            url: url
        });
        location.reload();
    });

    $(document).on('click', '.delete-item-cart', function () {
        var url = $(this).attr('href');
        $.ajax({
            type: "GET",
            url: url
        });
    });
});