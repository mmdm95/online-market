(function ($) {

    'use strict';

    $(function () {
        var
            menuContainer = $('.__all_menu_container'),
            subMenuContainer = $('.__all_sub_menu_container'),
            //-----
            menuSample = menuContainer.find('.__sample_menu_item'),
            subMenuSample = subMenuContainer.find('.__sample_sub_menu_item'),
            //-----
            inputNameStart = 'inp-setting-menu[',
            subInputNameStart = 'inp-setting-sub-menu[',
            titleNameEnd = '][title]',
            linkNameEnd = '][link]',
            priorityNameEnd = '][priority]',
            subTitleNameEnd = '][sub-title]',
            subLinkNameEnd = '][sub-link]',
            subPriorityNameEnd = '][sub-priority]';

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
         * @param btn
         */
        function subMenuCloner(btn) {
            var cloned, lastIndex, lastSubIndex, currContainer;

            cloned = subMenuSample.clone(true);
            cloned.removeClass('__sample_sub_menu_item');
            cloned.find('input').val('');
            //-----
            lastIndex = $(btn).closest('.__menu_items').index();
            currContainer = $(btn).closest('.__menu_items').find('.__all_sub_menu_container');
            lastSubIndex = currContainer.find('.__sub_menu_items').last().index() + 1;

            cloned.find('input[type=text][name$="' + subTitleNameEnd + '"]').attr('name', subInputNameStart + lastIndex + '][' + lastSubIndex + subTitleNameEnd);
            cloned.find('input[type=text][name$="' + subLinkNameEnd + '"]').attr('name', subInputNameStart + lastIndex + '][' + lastSubIndex + subLinkNameEnd);
            cloned.find('input[type=text][name$="' + subPriorityNameEnd + '"]').attr('name', subInputNameStart + lastIndex + '][' + lastSubIndex + subPriorityNameEnd);
            //-----
            currContainer.append(cloned);
            addRemoveClonedBtnAndEvent(cloned);
        }

        $('#__menu_cloner').off('click').on('click', function () {
            var cloned, lastIndex;

            cloned = menuSample.clone(true);
            cloned.removeClass('__sample_menu_items');
            cloned.find('.__sub_menu_items').each(function () {
                if (!$(this).hasClass('__sample_sub_menu_item')) {
                    $(this).remove();
                }
            });
            cloned.find('input').val('');
            //-----
            lastIndex = menuContainer.find('.__menu_items').last().index() + 1;

            cloned.find('input[type=text][name$="' + titleNameEnd + '"]').attr('name', inputNameStart + lastIndex + titleNameEnd);
            cloned.find('input[type=text][name$="' + linkNameEnd + '"]').attr('name', inputNameStart + lastIndex + linkNameEnd);
            cloned.find('input[type=text][name$="' + priorityNameEnd + '"]').attr('name', inputNameStart + lastIndex + priorityNameEnd);

            cloned.find('input[type=text][name$="' + subTitleNameEnd + '"]').attr('name', subInputNameStart + lastIndex + '][0' + subTitleNameEnd);
            cloned.find('input[type=text][name$="' + subLinkNameEnd + '"]').attr('name', subInputNameStart + lastIndex + '][0' + subLinkNameEnd);
            cloned.find('input[type=text][name$="' + subPriorityNameEnd + '"]').attr('name', subInputNameStart + lastIndex + '][0' + subPriorityNameEnd);
            //-----
            menuContainer.append(cloned);
            addRemoveClonedBtnAndEvent(cloned);
        });

        $('.__sub_menu_cloner').off('click').on('click', function () {
            subMenuCloner($(this));
        });
    });
})(jQuery);