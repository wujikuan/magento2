define([
    'jquery'
], function ($) {
    "use strict";

    $.widget('zou.bannerslider', {
        /**
         * This method constructs a new widget.
         * @private
         */
        _create: function () {
            this.templates = JSON.parse(this.options.templateHtml);
            this.imgUrls = JSON.parse(this.options.imgUrls);
            this.tplDropdown = $('#banner_default_template');
            this.initObserve();
        },
        initObserve: function () {
            this.loadTemplate();
            this.changeImageUrl();
        },
        loadTemplate: function () {
            var self = this;
            var toggleMCEEditor = $('#togglebanner_content');
            var bannerContent = $('#banner_content');
            var btnLoadContent = $('#banner_load_template');
            btnLoadContent.on('click', function () {
                var tplId = self.tplDropdown.val();
                var tpl = self.templates[tplId]["tpl"];
                var replaceBy = self.templates[tplId]["var"];
                var regEx = new RegExp(replaceBy, 'g');
                var html = tpl.replace(regEx, tplId);

                if (bannerContent.css('display') === 'none') {
                    toggleMCEEditor.trigger('click');
                }
                bannerContent.val(html);
            });
        },
        changeImageUrl: function () {
            var imageUrls = this.imgUrls;
            var demoImg = $('#mp-demo-image');
            this.tplDropdown.on('change', function () {
                demoImg.attr('src', imageUrls[$(this).val()]);
            })
        }
    });

    return $.zou.bannerslider;
});
