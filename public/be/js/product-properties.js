(function ($) {

    'use strict';

    $(function () {
        var
            propertiesContainer = $('.__all_properties_container'),
            subPropertiesContainer = $('.__all_sub_property_container'),
            //-----
            propertySample = propertiesContainer.find('.__sample_product_property').first(),
            subPropertySample = subPropertiesContainer.find('.__sample_sub_product_property').first(),
            //-----
            inputNameStart = 'inp-item-product-properties[',
            subInputNameStart = 'inp-item-product-sub-properties[',
            titleNameEnd = '][title]',
            subTitleNameEnd = '][sub-title]',
            subPriorityNameEnd = '][sub-properties]';

        /**
         * @param clonedEl
         */
        function addRemoveClonedBtnAndEvent(clonedEl) {
            clonedEl = $(clonedEl);

            clonedEl.append(
                $('<div class="__clone_remover_btn btn btn-danger" />')
                    .append($('<i class="icon-trash" aria-hidden="true" />'))
            );

            $('.__clone_remover_btn')
                .off('click')
                .on('click', function (e) {
                    e.stopPropagation();

                    $(this).parent().fadeOut(300, function () {
                        $(this).remove();
                    });
                });
        }

        /**
         * @param clonedEl
         */
        function afterClonedFunctionality(clonedEl) {
            clonedEl = $(clonedEl);
            clonedEl.find('.bootstrap-tagsinput').remove();

            $('.tags-input').each(function () {
                var obj, maxTags, $this;
                $this = $(this);
                obj = {};

                // check for max tags
                maxTags = $this.attr('data-max-tags');
                maxTags = maxTags && !isNaN(parseInt(maxTags, 10)) ? parseInt(maxTags, 10) : null;
                if (maxTags) {
                    obj['maxTags'] = maxTags;
                }

                if ($this.data('tagsinput')) {
                    $this.tagsinput("destroy");
                    $this.removeData('tagsinput');
                }
                setTimeout(function () {
                    $this.tagsinput(obj);
                }, 1);
            });
        }

        /**
         * @param btn
         */
        function subPropertyCloner(btn) {
            var cloned, lastIndex, lastSubIndex, currContainer;

            cloned = subPropertySample.clone(true);
            cloned.removeClass('__sample_sub_product_property');
            cloned.find('input').val('');
            //-----
            lastIndex = $(btn).closest('.__product_properties').index();
            currContainer = $(btn).closest('.__product_properties').find('.__all_sub_property_container');
            lastSubIndex = currContainer.find('.__sub_product_properties').last().index() + 1;

            cloned.find('input[type=text][name$="' + subTitleNameEnd + '"]').attr('name', subInputNameStart + lastIndex + '][' + lastSubIndex + subTitleNameEnd);
            cloned.find('input[type=text][name$="' + subPriorityNameEnd + '"]').attr('name', subInputNameStart + lastIndex + '][' + lastSubIndex + subPriorityNameEnd);
            //-----
            currContainer.append(cloned);
            addRemoveClonedBtnAndEvent(cloned);
            afterClonedFunctionality(cloned);
        }

        $('#__property_cloner').off('click').on('click', function () {
            var cloned, lastIndex;

            cloned = propertySample.clone(true);
            cloned.removeClass('__sample_product_property');
            cloned.find('.__sub_product_properties').each(function () {
                if (!$(this).hasClass('__sample_sub_product_property')) {
                    $(this).remove();
                }
            });
            cloned.find('input').val('');
            //-----
            lastIndex = propertiesContainer.find('.__product_properties').last().index() + 1;

            cloned.find('input[type=text][name$="' + titleNameEnd + '"]').attr('name', inputNameStart + lastIndex + titleNameEnd);

            cloned.find('input[type=text][name$="' + subTitleNameEnd + '"]').attr('name', subInputNameStart + lastIndex + '][0' + subTitleNameEnd);
            cloned.find('input[type=text][name$="' + subPriorityNameEnd + '"]').attr('name', subInputNameStart + lastIndex + '][0' + subPriorityNameEnd);
            //-----
            propertiesContainer.append(cloned);
            addRemoveClonedBtnAndEvent(cloned);
            afterClonedFunctionality(cloned);
        });

        $('.__sub_property_cloner').off('click').on('click', function () {
            subPropertyCloner($(this));
        });
    });
})(jQuery);