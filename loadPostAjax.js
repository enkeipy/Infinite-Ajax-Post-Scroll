jQuery(document).ready(function($) {

    var window_height = $( window ).height();

    var process_ajax = true;

    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        var last_article = $( "article" ).last();
        var last_article_height = last_article.height();
        var last_article_offset_top = last_article.offset().top;

        var last_article_to_bottom = window_height + scroll - last_article_offset_top - last_article_height;

        if (last_article_to_bottom > 0 && process_ajax == true) {
            process_ajax = false;
            var id = last_article.attr("id").substring(5);
            this2 = this;
            $.post(my_ajax_obj.ajax_url, {
                action: "load_previous_post",
                id: id
            }, function(data) {
                last_article.after(data);
                process_ajax = true;
            });
        }

    });
});
