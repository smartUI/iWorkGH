/**
 * Zepto picLazyLoad Plugin
 * ximan http://ons.me/484.html
 * 20140517 v1.0
 */
; (function($) {
    $.fn.picLazyLoad = function(settings) {
        var $this = $(this),
        _winScrollTop = 0,
        _winHeight = $(window).height();
        settings = $.extend({
            threshold: 0,
            placeholder: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC'
        },
        settings || {});
        lazyLoadPic();
        $(window).on('scroll',
        function() {
            //_winScrollTop = $(window).scrollTop();
            _winScrollTop = getScrollTop();
            lazyLoadPic();
        });
        function lazyLoadPic() {
            alert(_winScrollTop);
            $this.each(function() {
                var $self = $(this);
                if ($self.is('img')) {
                    
                    if ($self.attr('data-original')) {
                        var _offsetTop = $self.offset().top;
                        if ((_offsetTop - settings.threshold) <= (_winHeight + _winScrollTop)) {
                            $self.attr('src', $self.attr('data-original'));
                            $self.removeAttr('data-original');
                        }
                    }
                } else {
                    if ($self.attr('data-original')) {
                        if ($self.css('background-image') == 'none') {
                            $self.css('background-image', 'url(' + settings.placeholder + ')');
                        }
                        var _offsetTop = $self.offset().top;
                        if ((_offsetTop - settings.threshold) <= (_winHeight + _winScrollTop)) {
                            $self.css('background-image', 'url(' + $self.attr('data-original') + ')');
                            $self.removeAttr('data-original');
                        }
                    }
                }
            });
        }
        //js 滚动条的垂直偏移
        function getScrollTop() {  
            var scrollPos;  
            if (window.pageYOffset) {  
            scrollPos = window.pageYOffset; }  
            else if (document.compatMode && document.compatMode != 'BackCompat')  
            { scrollPos = document.documentElement.scrollTop; }  
            else if (document.body) { scrollPos = document.body.scrollTop; }   
            return scrollPos;   
        }  
    }
})(Zepto);