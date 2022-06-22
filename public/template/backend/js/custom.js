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
});