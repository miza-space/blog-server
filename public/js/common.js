$(document).ready(function() {
    // delegate sidebar menu btn
    $('.menu').delegate('li', 'click', function(event) {
        var sidebar = $('.sidebar'),
            curr_target = sidebar.attr('data-target'),
            menu_index = $(this).attr('data-index');

        if (menu_index == curr_target) {
            $('body').toggleClass('unfold-sidebar');
            return;
        }

        sidebar.attr('data-target', menu_index);
        $('.sidebar-item').hide();
        $('.sidebar-' + menu_index).show('slow');
        $('body').addClass('unfold-sidebar');

        if ($('body').hasClass('unfold-sidebar')) {
            return;
        }
    });

    $('.fade-bg').click(function(event) {
        $('body').removeClass('unfold-sidebar');
    });

    
});