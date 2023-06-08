jQuery(document).ready(function ($) {
    $('.zpp-size-chart').on('click', function (e) {
        e.preventDefault();
        var product_id = $(this).data('product_id');
        var content = $('#zpp-content-' + product_id).html();

        var modal_content = '<div class="zpp-modal"><div class="zpp-modal-content"><span class="zpp-close">&times;</span>' + content + '</div></div>';

        $('body').append(modal_content);
        var modal = $('.zpp-modal');
        modal.show();
        $('.zpp-close, .zpp-modal').on('click', function () {
            modal.remove();
        });
    });
});