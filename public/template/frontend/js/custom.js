$(document).ready(function () {  

    $(document).on('click', '.btn-ajax-quick-view', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        console.log(url);
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                $('#quick-view-content').html(response);
            }
        });
    });

});