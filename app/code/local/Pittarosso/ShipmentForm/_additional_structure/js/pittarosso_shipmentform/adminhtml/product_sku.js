var ProductSku = Class.create();
ProductSku.prototype = {
    initialize: function(params) {
        this.window = $('product_sku_window');
        this.windowMask = $('popup-window-mask');

        this.cancelCallback = params.cancelCallback;
        this.confirmCallback = params.confirmCallback;
    },

    showWindow: function(options) {
        this.options = options;

        this.window.show().setStyle({
            'marginLeft': -this.window.getDimensions().width/2 + 'px'
        });
        this.windowMask.setStyle({
            height: $('html-body').getHeight() + 'px'
        }).show();
    },

    cancel: function() {
        productSku.window.hide();
        productSku.windowMask.hide();
        if (Object.isFunction(this.cancelCallback)) {
            this.cancelCallback();
        }
    },

    confirm: function() {
        if (Object.isFunction(this.confirmCallback)) {
            this.confirmCallback();
        }
    }
};