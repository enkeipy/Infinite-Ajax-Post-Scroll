jQuery(document).ready(function($) {

    var windowHeight = $( window ).height();

    var processAjax = true;

    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        var lastArticle = $( "article" ).last();
        var lastArticleHeight = lastArticle.height();
        var lastArticleOffsetTop = lastArticle.offset().top;

        var lastArticleToBottom = windowHeight + scroll - lastArticleOffsetTop - lastArticleHeight;

        if (lastArticleToBottom > 0 && processAjax == true) {
            processAjax = false;
            var id = lastArticle.attr("id").substring(5);
            this2 = this;
            $.post(load_post_obj.ajax_url, {
                action: "load_previous_post",
                id: id
            }, function(data) {
                lastArticle.after(data);
                processAjax = true;
            });
        }

    });
});
