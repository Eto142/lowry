(function($) {

    // Create the defaults once
    let downDefaults = {
        wrapper : 'outer',
    };

    let upDefaults = {
        wrapper : 'outer',
    };


    function slideDown(element) {
        let options = {};
        this.elem = element;
        this.options = $.extend({}, downDefaults, options);

        if (element[0]) {
            this.selector = element[0].attributes['id'].value;
            let selector = this.selector.replace('.', '');
            selector = selector.replace('#', '');
            this.wrapper = 'bs' + '_' + selector + '_' + this.options['wrapper'];
        }

        this.init();
    }

    function slideUp(element) {
        let options = {};
        this.elem = element;
        this.options = $.extend({}, upDefaults, options);

        if(element[0]) {
            this.selector = element[0].attributes['id'].value;
            let selector = this.selector.replace('.', '');
            selector = selector.replace('#', '');
            this.wrapper = 'bs' + '_' + selector + '_' + this.options['wrapper'];
        }

        this.init();
    }

    slideDown.prototype = {

        init: function() {

            // set up the wrapper around the element and apply styles
            let wrapper = this.wrapper;
            let target = $(this.elem);

            // If the parent wrapper has not been set up do so
            if(!target.parent().hasClass(wrapper)) {

                // Set up parent wrapper
                target.wrap('<div class="' + wrapper + '" />');
                $('.' + wrapper).css({height: 'auto', display:'none', overflow: 'hidden', width: '100%'});

                // Calculate the init height of the target.
                // With both elem hidden the height calc does not work
                // Currently it shows both elems, calculates the height and hides the wrapper
                // ***** There has to be a better way
                target.show();
                $('.' + wrapper).show();
                let h = target.outerHeight();
                $('.' + wrapper).hide();

                //move target to top of wrapper div so it can slide down
                target.css({top:'-' + h + 'px'});
            }

            // Do it
            this.slide(target, wrapper);
        },

        slide: function(target, wrapper) {
            //slide down the outer wrapper an animate the element position
            $('.' + wrapper).slideDown(200);
            target.animate({top:'0'}, 200);
        }
    };


    slideUp.prototype = {

        init: function() {

            // Do it
            this.slide($(this.elem), this.wrapper, this.speed);
        },

        slide: function(target, wrapper, speed) {
            // Get the height of the elem (performed each time for responsivenes)
            let h = target.outerHeight();

            $('.' + wrapper).slideUp(speed);
            target.animate({top: '-' + h}, speed);
        }
    };

    // the slideDown constructor
    $.fn['betterSlideDown'] = function(options) {
        new slideDown(this, options);
    };

    // the slideUp constructor
    $.fn['betterSlideUp'] = function(options) {
        new slideUp(this, options);
    };

})(jQuery, window, document);
