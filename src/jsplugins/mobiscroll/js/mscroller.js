function mScroller(elm, type, options){
    $(elm).scroller({
        preset: type,
        lang: 'br',
        theme: 'default',
        display: 'bubble',
        animate: 'slidehorizontal',
        mode: 'mixed',
        showNow: true,
        rows: '3',
        onBeforeShow: function(valueText, inst) {
            if(typeof(options.min) !== 'undefined') $(elm).scroller('option', 'minDate', options.min());
            if(typeof(options.max) !== 'undefined') $(elm).scroller('option', 'maxDate', options.max());
            if(typeof(options.onBef) === 'function'){
                options.onBef(valueText, inst);
            }
        }
    });
}