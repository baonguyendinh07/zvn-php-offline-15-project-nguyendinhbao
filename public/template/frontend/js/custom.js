$(document).ready(function () {
    $('.filter-element').on('change', function () {
        $('#filter-form').submit();
    });
    
    $(document).on('change', '.btn-ajax-group-id', function (e) {
        e.preventDefault();
        var group_id = $(this).val();
        var url = $(this).data('url') + '&group_id=' + group_id;
        var parent = $(this).parent();
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                parent.html(response);
            }
        });
    });

    $(document).on('click', '.btn-ajax-status', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var parent = $(this).parent();
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                parent.html(response);
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
});