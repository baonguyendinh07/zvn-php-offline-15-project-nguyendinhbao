$(document).ready(function () {

    $('.filter-element').on('change', function () {
        $('#filter-form').submit();
    });

    $(document).on('change', '.btn-ajax-group-id', function (e) {
        e.preventDefault();
        var group_id = $(this).val();
        var url = $(this).data('url') + '&group_id=' + group_id;
        var tdTag = $(this).parent();
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                tdTag.html(response);
                tdTag.find('select.btn-ajax-group-id').notify("Success", { className: 'success', position: 'top-center' });
            }
        });
    });

    $(document).on('change', '.btn-ajax-category-id', function (e) {
        e.preventDefault();
        var category_id = $(this).val();
        var url = $(this).data('url') + '&category_id=' + category_id;
        var tdTag = $(this).parent();
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                tdTag.html(response);
                tdTag.find('select.btn-ajax-category-id').notify("Success", { className: 'success', position: 'top-center' });
            }
        });
    });

    $(document).on('change', '.btn-ajax-ordering', function (e) {
        e.preventDefault();
        let ele = $(this);
        var ordering = ele.val();
        var url = ele.data('url') + '&ordering=' + ordering;
        $.ajax({
            type: "GET",
            url: url,
            success: function () {
                ele.notify("Success", { className: 'success', position: 'top-center' });
            }
        });
    });

    $(document).on('click', '.btn-ajax-status', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var tdTag = $(this).parent();
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                tdTag.html(response);
                tdTag.find('a.btn-ajax-status').notify("Success", { className: 'success', position: 'top-center' });
            }
        });
    });

    $(document).on('click', '.btn-ajax-special', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var tdTag = $(this).parent();
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                tdTag.html(response);
                tdTag.find('a.btn-ajax-special').notify("Success", { className: 'success', position: 'top-center' });
            }
        });
    });

    $('.btn-ajax-pw').click(function () {
        var url = $('.btn-ajax-pw').val();
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                $('#random-password').val(response);
            }
        });
    });

    activeMenu();

    function activeMenu() {
        let controller = getUrlParam('controller');
        let action     = getUrlParam('action');

        if (controller == 'user' && action == 'changeAccountPassword') controller = action;

        let $currentMenuItemLevel1 = $('.nav-sidebar > .nav-item > [data-active="' + controller + '"]');
        $currentMenuItemLevel1.addClass('active');

        let $navTreeview = $currentMenuItemLevel1.next();
        if ($navTreeview.length > 0) {
            let $currentMenuItemLevel2 = $navTreeview.find('[data-active="' + action + '"]');
            $currentMenuItemLevel2.addClass('active');
            $currentMenuItemLevel1.parent().addClass('menu-open');
        } else {
            $('.nav-sidebar > .nav-item > [data-active="' + action + '"]').addClass('active');
        }
    }

    function getUrlParam(key) {
        let searchParams = new URLSearchParams(window.location.search);
        return searchParams.get(key);
    }

    $('.filter-element').on('change', function () {
        $('#sort-form').submit();
    });

    $('.btn-delete').click(function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        Swal.fire({
            title: 'Xác nhận?',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    });

    $('#checkAll').click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $('#submit-main-form').click(function(){
        Swal.fire({
            title: 'Xác nhận?',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#main-form').submit();
            }
        })
        
    });
});