$(document).ready(function() {
    var gallery,
        galleryInit,
        galleryCtr,
        getBlogs,
        animateArticle,
        showLocationInMap,
        shareToWeibo;

    // gallery controller
    galleryCtr = function (ctr_node) {
        if (ctr_node.hasClass('gallery-prev')) {
            gallery.prev();
        } else {
            gallery.next();
        }
    };

    galleryInit = function (target_node, show_type) {
        var gallery_node = target_node.closest('.pictures'),
            gallery_data_node,
            gallery_data,
            curr_index,
            // hidePageScrollbars = false,
            gallery_container = '#fullscreen-blueimp-gallery',
            onopen_func = undefined;

        // checking gallery is active in current box
        if (gallery_node.hasClass('gallery-active') && gallery && show_type == 'carousel') {
            galleryCtr(target_node);
            return;
        }

        // close exist gallery
        if (gallery) {
            gallery.close();
        }

        // init new gallery
        curr_index          = parseInt(gallery_node.attr('data-gindex')) || 0;
        gallery_data_node   = gallery_node.find('.gallery-data');
        gallery_data        = (gallery_data_node.length > 0) ? $.parseJSON(gallery_data_node.val()) : [gallery_node.find('img').attr('src')];

        if (show_type == 'carousel') {
            // hidePageScrollbars = false;
            gallery_container = gallery_node.find('.blueimp-gallery');
            onopen_func = function () {
                $('.gallery-active').removeClass('gallery-active');
                gallery_node.addClass('gallery-active');
            };
        } else {
            // check fullscreen gallery container exist or not
            if ($('#fullscreen-blueimp-gallery').length == 0) {
                $('body').append([
                    '<div id="fullscreen-blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">',
                    '<div class="slides"></div>',
                    '<h3 class="title"></h3>',
                    '<a class="prev">‹</a>',
                    '<a class="next">›</a>',
                    '<a class="close">×</a>',
                    '<ol class="indicator"></ol>',
                '</div>'
                ].join(''));
            }
        }

        gallery = blueimp.Gallery(gallery_data, {
            container: gallery_container,
            hidePageScrollbars: false,
            stretchImages: true,
            displayTransition: false,
            onopen: onopen_func,
            onclose: function () {
                var curr_index = gallery.getIndex(),
                    current_image = gallery_data[curr_index];
                
                gallery_node.attr('data-gindex', curr_index);
                gallery_node.find('.gallery-holder').attr('style', 'background-image: url(' + current_image + '); background-size:cover;');
            },
            onclosed: function () {
                gallery_node.removeClass('gallery-active');
                gallery = null;
            },
            index: curr_index
        });

        if (show_type == 'carousel') {
            galleryCtr(target_node);
        }
    };

    animateArticle = function () {
        $('.timeline .animated').removeClass('animated');
    };

    getBlogs = function () {
        var more_btn = $('.more'), skip, take, page;

        more_btn.addClass('loading');
        more_btn.removeClass('error');

        skip = parseInt(more_btn.attr('data-skip')) || 0;
        page = parseInt(more_btn.attr('data-page')) || 0;
        take = parseInt(more_btn.attr('data-take')) || 18;
        skip+= page * 40 + take;

        $.ajax({
            url: '/api/blogs/' + skip + '/' + take,
            type: 'GET'
        })
        .done(function(data, status) {

            if (status != 'success') {
                more_btn.addClass('error');
                return;
            }

            if (data.is_all) {
                more_btn.addClass('all_done');
                more_btn.text('真有耐心，你居然都看完了 :)');
                window.is_loaded = true;
            } else if (data.skip > 40) {
                // need to next page
                more_btn.addClass('next_page');
                more_btn.text('next');
                window.is_loaded = true;
            }

            more_btn.attr('data-skip', skip);
            more_btn.attr('data-take', take);

            $('.timeline').append(data.view);
            setTimeout(animateArticle, 100);
        })
        .fail(function() {
            more_btn.text('貌似出错了，点击重新刷新呗');
            more_btn.addClass('error');
        })
        .always(function() {
            window.is_loading_blog = false;
            more_btn.removeClass('loading');
        });
        
    };

    showLocationInMap = function (event) {
        // check map container exist
        if ($('.blog-map').length == 0)
        {
            $('body').append('<div class="blog-map animated"></div>');
        }
    };

    shareToWeibo = function (event) {
        var container = $(this).closest('.content'),
            title = container.find('h3').text() || 'zhangge blog有新内容更新',
            title = encodeURIComponent(title),
            url = encodeURIComponent(location.href),
            pictures = container.find('.pictures'),
            pictures_list;

        // if (pictures.length > 0) {
        //     pictures_list = pictures.find('.gallery-data');
        //     if (pictures_list.length > 0) {
        //         pictures_list = $.parseJSON(pictures_list.val());
        //         pictures = [];
        //         for (var i = Things.length - 1; i >= 0; i--) {
        //             Things[i]
        //         };
        //     }
        // }

        var share_url = 'http://service.weibo.com/share/share.php?title=' + title + '&url=' + url;
        var win = window.open(share_url, '_blank');
        win.focus();
    };

    // delegate prev/next button
    $('.timeline').delegate('.gallery-tool', 'click', function(event) {
        galleryInit($(this), 'carousel');
    });

    // delegate fullscreen button
    $('.timeline').delegate('.pic-fullscreen', 'click', function(event) {
        galleryInit($(this), 'fullscreen');
    });

    $('.timeline').delegate('.share', 'click', shareToWeibo);
    $('.timeline').delegate('.location', 'click', showLocationInMap);

    // click on load more btn
    $('.more').click(function(event) {
        var btn = $(this);
        if (btn.hasClass('all_done')) return;

        if (btn.hasClass('next_page')) {
            var page = parseInt(btn.attr('data-page')) || 0;
            page += 1;
            window.reload('/' + page);

            return;
        }

        //reload page
        getBlogs();
    });

    $(window).scroll(function () {
        if (window.is_loaded || window.is_loading_blog) return;

        var vTop=$(document).scrollTop();
        var scrollHeight = $(document).height() - $(window).height();

        if (scrollHeight - vTop < 120) {
            var btn = $('.more');

            if (btn.hasClass('no-data')) {
                window.is_loaded = true;
                return;
            }

            window.is_loading_blog = true;
            btn.addClass('loading');
            setTimeout(getBlogs, 1000);
        }
    });

    animateArticle();
});