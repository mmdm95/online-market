<!-- Script of easy file manager -->
<script>
    (function ($) {
        $.fn.tablesorter = function () {
            var $table = this;
            this.find('th:not(#chks)').click(function () {
                var idx = $(this).index();
                var direction = $(this).hasClass('sort_asc');
                $table.tablesortby(idx, direction);
            });
            return this;
        };

        $.fn.tablesortby = function (idx, direction) {
            var $rows = this.find('tbody tr');

            function elementToVal(a) {
                var $a_elem = $(a).find('td:nth-child(' + (idx + 1) + ')');
                var a_val = $a_elem.attr('data-sort') || $a_elem.text().toLowerCase();
                return (a_val == parseInt(a_val) ? parseInt(a_val) : a_val);
            }

            $rows.sort(function (a, b) {
                var a_val = elementToVal(a), b_val = elementToVal(b);
                return (a_val > b_val ? 1 : (a_val == b_val ? 0 : -1)) * (direction ? 1 : -1);
            });
            this.find('th').removeClass('sort_asc sort_desc');
            $(this).find('thead th:nth-child(' + (idx + 1) + ')').addClass(direction ? 'sort_desc' : 'sort_asc');
            for (var i = 0; i < $rows.length; i++) {
                this.append($rows[i]);
            }
            this.settablesortmarkers();
            this.movefoldersbeside();

            return this;
        };

        $.fn.movefoldersbeside = function () {
            var self = this;
            var $rows = self.find('tbody tr');

            $($rows.get().reverse()).each(function () {
                if ($(this).hasClass('is_dir')) {
                    $(this).prependTo(self.find('tbody'));
                }
            });

            return this;
        };

        $.fn.retablesort = function () {
            var $e = this.find('thead th.sort_asc, thead th.sort_desc');
            if ($e.length)
                this.tablesortby($e.index(), $e.hasClass('sort_desc'));

            return this;
        };

        $.fn.settablesortmarkers = function () {
            this.find('thead th span.indicator').remove();
            this.find('thead th.sort_asc').append('<span class="indicator ml-2">&darr;<span>');
            this.find('thead th.sort_desc').append('<span class="indicator ml-2">&uarr;<span>');
            return this;
        };
    })(jQuery);

    $(function () {
        var routes = {
            list: '/ajax/file-manager/list',
            rename: '/ajax/file-manager/rename',
            delete: '/ajax/file-manager/delete',
            mkdir: '/ajax/file-manager/mkdir',
            upload: '/ajax/file-manager/upload',
            download: '/ajax/file-manager/download',
        };

        var renameModal = $('#modal_rename'),
            renameInput = $('#renameInput');

        var
            adminCommon = new window.TheAdmin(),
            core = window.TheCore

        var XSRF = (document.cookie.match('(^|; )_sfm_xsrf=([^;]*)') || 0)[2];
        XSRF = XSRF ? XSRF : null;
        var MAX_UPLOAD_SIZE = <?= $the_options['MAX_UPLOAD_SIZE'] ?>;
        var $tbody = $('#list');
        $(window).on('hashchange', list).trigger('hashchange');
        $('#table').tablesorter();

        $('#table').on('click', '.delete', function () {
            var _ = this;
            adminCommon.toasts.confirm(null, function () {
                $.ajax({
                    url: routes.delete,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        file: $(_).attr('data-file'),
                        xsrf: XSRF,
                    },
                    success: function (response) {
                        if (null === response.error) {
                            list();
                        } else {
                            adminCommon.toasts.toast(response.error, {
                                type: 'error',
                            });
                        }
                    },
                    error: function (response) {
                        // console.warn(response.responseText);
                    },
                });
            }, {
                type: 'confirm',
            });
            return false;
        }).on('click', '.rename', function () {
            var path = $(this).attr('data-file');
            var name = $(this).attr('data-file-name');
            if (path && name) {
                renameModal.modal();
                renameInput.attr('data-path', path).val(name);
            }
            return false;
        });

        $('#renameFile').on('click', function () {
            var name = $.trim(renameInput.val());
            var path = renameInput.attr('data-path');
            if ('' !== name && path) {
                $.ajax({
                    method: 'POST',
                    url: routes.rename,
                    dataType: 'json',
                    data: {
                        file: path,
                        newName: name,
                        xsrf: XSRF,
                    },
                    success: function (response) {
                        if (null === response.error) {
                            list();
                        } else {
                            adminCommon.toasts.toast(response.error, {
                                type: 'error',
                            });
                        }
                    },
                });
            }
        });

        $('#mkdir').submit(function (e) {
            var hashval = decodeURIComponent(window.location.hash.substr(1)),
                $dir = $(this).find('#dirname[name="name"]');
            e.preventDefault();
            if ('' !== $.trim($dir.val())) {
                $dir.val().length && $.post(routes.mkdir, {
                    name: $dir.val(),
                    xsrf: XSRF,
                    file: hashval
                }, function (data) {
                }, 'json');
                $dir.val('');
                list();
            }
            return false;
        });

        $('#mvdir').on('click', function (e) {
            e.preventDefault();

            var foldersBody = $('#folders-body');
            var newPath = foldersBody.find('.selectable').first().attr('data-path');

            if (typeof newPath !== typeof undefined) {
                $.ajax({
                    url: routes.mvdir,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        newPath: newPath,
                        xsrf: XSRF,
                        file: JSON.stringify(chksPath)
                    },
                    success: function (response) {
                        if (null === response.error) {
                            if (99 == response.status_code) {
                                alert('برخی از فایل‌ها با نام یکسان جابجا نشدند!');
                            }

                            list();
                            foldersBody.find('.selectable').removeClass('selectable');
                            $('#tree-refresh').trigger('click');
                        } else {
                            adminCommon.toasts.toast(response.error, {
                                type: 'error',
                            });
                        }
                    },
                    error: function (response) {
                        // console.log(response.responseText);
                    },
                });
            } else {
                e.stopPropagation();
            }
        });

        <?php if($the_options['allow_upload']): ?>
        // file upload stuff
        $('#file_drop_target').on('dragover', function () {
            $(this).addClass('drag_over');
            return false;
        }).on('dragend', function () {
            $(this).removeClass('drag_over');
            return false;
        }).on('drop', function (e) {
            e.preventDefault();
            var files = e.originalEvent.dataTransfer.files;
            $.each(files, function (k, file) {
                uploadFile(file);
            });
            $(this).removeClass('drag_over');
        });
        $('#file_drop_target').find('input[type=file]').change(function (e) {
            e.preventDefault();
            $.each(this.files, function (k, file) {
                uploadFile(file);
            });
        });

        function uploadFile(file) {
            var folder = decodeURIComponent(window.location.hash.substr(1));
            if (file.size > MAX_UPLOAD_SIZE) {
                var $error_row = renderFileSizeErrorRow(file, folder);
                $('#upload_progress').append($error_row);
                window.setTimeout(function () {
                    $error_row.fadeOut();
                }, 5000);
                return false;
            }

            var $row = renderFileUploadRow(file, folder);
            $('#upload_progress').append($row);
            var fd = new FormData();
            fd.append('file_data', file);
            fd.append('file', folder);
            fd.append('xsrf', XSRF);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', routes.upload);
            xhr.onreadystatechange = function () {
                if (4 === this.readyState && 200 === this.status) {
                    var data = JSON.parse(this.responseText);
                    if (null === data.error) {
                        $row.remove();
                        list();
                    } else {
                        adminCommon.toasts.toast(data.error, {
                            type: 'error',
                        });
                    }
                }
            };
            xhr.upload.onprogress = function (e) {
                if (e.lengthComputable) {
                    $row.find('.progress').css('width', (e.loaded / e.total * 100 | 0) + '%');
                }
            };
            xhr.send(fd);
        }

        function renderFileUploadRow(file, folder) {
            return $row = $('<div class="d-flex align-items-center" />')
                .append($('<span class="fileuploadname" />').text(file.name))
                .append($('<div class="progress_track"><div class="progress"></div></div>'))
                .append($('<span class="size" style="direction: ltr;" />').text(formatFileSize(file.size)))
        }

        function renderFileSizeErrorRow(file, folder) {
            return $row = $('<div class="error" />')
                .append($('<span class="fileuploadname" />').text('Error: ' + file.name))
                .append($('<span/>').html(' file size - <b>' + formatFileSize(file.size) + '</b>'
                    + ' exceeds max upload size of <b>' + formatFileSize(MAX_UPLOAD_SIZE) + '</b>'));
        }
        <?php endif; ?>

        var
            lazyFirstTime = true,
            threshold;

        function list() {
            var hashval = window.location.hash.substr(1);
            $.get(routes.list, {
                file: hashval
            }, function (data) {
                $tbody.empty();
                $('#breadcrumb').empty().html(renderBreadcrumbs(hashval));

                if (null === data.error) {
                    $.each(data.data, function (k, v) {
                        $tbody.append(renderFileRow(v));
                    });

                    threshold = lazyFirstTime ? 500 : 0;
                    $('.lazy').lazy({
                        effect: "fadeIn",
                        effectTime: 800,
                        threshold: threshold,
                        appendScroll: $('#table').closest('.lazyContainer').length ? $('#table').closest('.lazyContainer') : window,
                        // callback
                        afterLoad: function (element) {
                            $(element).css({'background': 'none'});
                        }
                    });
                    lazyFirstTime = false;

                    $('[data-popup=lightbox]').on('click', function (e) {
                        e.preventDefault();
                    }).each(function () {
                        if ($.fn.fancybox) {
                            $(this).fancybox({
                                href: $(this).attr('data-url')
                            });
                        }
                    });

                    !data.data.length && $tbody.append('<tr><td class="empty" colspan="5">این پوشه خالی می‌باشد</td></tr>');

                    $('#table').trigger('efm:list');
                } else {
                    adminCommon.toasts.toast(data.error, {
                        type: 'error',
                    });
                }
                $('#table').retablesort();
            }, 'json');
        }

        function renderFileRow(data) {
            var $link = setImagesBg(data);
            var allow_direct_link = <?= $the_options['allow_direct_link'] ? 'true' : 'false'; ?>;
            if (!data.is_dir && !allow_direct_link) $link.css('pointer-events', 'none');

            // Download Icon
            var $dl_link = $('<a/>').attr('href', routes.download + '/' + data.path)
                .attr('target', "_blank").addClass('download btn btn-success btn-icon').text('دانلود').prepend("<i class='icon-download4 ml-2'></i>");
            var $delete_link = $('<a href="javascript:void(0);" />').attr('data-file', data.path).addClass('delete btn btn-light btn-icon').text('حذف').prepend("<i class='icon-cross3 ml-2'></i>");
            var $rename_link = $('<a href="javascript:void(0);" />').attr('data-file-name', data.name).attr('data-file', data.path).attr('data-toggle', 'modal').attr('data-target', '#modal_rename').addClass('rename btn btn-warning btn-icon').text('تغییر نام').prepend("<i class='icon-pencil7 ml-2'></i>");
            var perms = [];
            if (data.is_readable) perms.push('read');
            if (data.is_writable) perms.push('write');
            if (data.is_executable) perms.push('exec');
            return $('<tr />')
                .addClass(data.is_dir ? 'is_dir' : '')
                .append($('<td class="first" />').append($link))
                .append($('<td/>').attr('data-sort', data.size)
                    .html($('<span class="size" />').text(formatFileSize(data.size))))
                .append($('<td/>').attr('data-sort', data.mtime).text(formatTimestamp(data.mtime)))
                .append($('<td/>').text(perms.join('+')))
                .append($('<td/>').append($dl_link).append($rename_link).append(data.is_deleteable ? $delete_link : ''));
        }

        function setImagesBg(data) {
            var $link, extraClass, dataSrc;
            extraClass = '';
            switch (data.ext) {
                case 'png':
                case 'jpg':
                case 'jpeg':
                case 'gif':
                case 'svg':
                    dataSrc = '<?= url('image.show'); ?>' + data.path;
                    break;
                case 'js':
                    extraClass = 'js';
                    dataSrc = '<?= asset_path('be', false); ?>/images/file-icons/JS.png';
                    break;
                case 'css':
                    extraClass = 'css';
                    dataSrc = '<?= asset_path('be', false); ?>/images/file-icons/CSS.png';
                    break;
                case 'html':
                case 'xhtml':
                    extraClass = 'html';
                    dataSrc = '<?= asset_path('be', false); ?>/images/file-icons/HTML.png';
                    break;
                case 'xls':
                    extraClass = 'xls';
                    dataSrc = '<?= asset_path('be', false); ?>/images/file-icons/Excel.png';
                    break;
                case 'doc':
                case 'docx':
                    extraClass = 'doc';
                    dataSrc = '<?= asset_path('be', false); ?>/images/file-icons/Word.png';
                    break;
                case 'pdf':
                    extraClass = 'pdf';
                    dataSrc = '<?= asset_path('be', false); ?>/images/file-icons/PDF.png';
                    break;
                case 'php':
                case 'phtml':
                    extraClass = 'php';
                    dataSrc = '<?= asset_path('be', false); ?>/images/file-icons/PHP.png';
                    break;
                case 'mp4':
                case 'ogg':
                case 'webm':
                    extraClass = 'video';
                    dataSrc = '<?= asset_path('be', false); ?>/images/file-icons/Video.png';
                    break;
                default:
                    $link = $('<a class="name" />')
                        .attr('href', data.is_dir ? '#' + data.path : '<?= url('image.show'); ?>' + data.path)
                        .text(data.name);
                    return $link;
            }

            $link = $('<a class="name image ' + extraClass + '" />')
                .attr('href', 'javascript:void(0);').attr('data-url', data.path)
                .append($('<span class="img-name">' + data.name + '</span>'))
                .append($('<img class="lazy" data-src="' + dataSrc + '" alt="' + data.name + '" />'))
                .append($('<div style="clear: both;"></div>'));

            return $link;
        }

        function renderBreadcrumbs(path) {
            var base = "",
                pathArr = path.split('\\').join('/').split('/'), $html;

            if (pathArr.length == 1) {
                $html = $('<div/>').append($('<a href="#" class="text-dark">Home</a></div>'));
            } else {
                $html = $('<div/>').append($('<a href="#" class="text-muted">Home</a></div>'));
            }
            $.each(pathArr, function (k, v) {
                if (v) {
                    var v_as_text;
                    if (pathArr.length == k) {
                        v_as_text = decodeURIComponent(v);
                        $html.append($('<span/>').text(' ▸ '))
                            .append($('<a class="text-dark" />').attr('href', '#/' + base + v).text(v_as_text));
                    } else {
                        v_as_text = decodeURIComponent(v);
                        $html.append($('<span/>').text(' ▸ '))
                            .append($('<a class="text-muted" />').attr('href', '#/' + base + v).text(v_as_text));
                    }
                    base += v + '/';
                }
            });

            $html.find('a[href]').each(function () {
                var $this = $(this), prevTopScroll;
                $this.on('click', function () {
                    prevTopScroll = $('html').scrollTop();
                    setTimeout(function () {
                        $('html').animate({scrollTop: prevTopScroll}, 200);
                    }, 200);
                });
            });

            return $html;
        }

        function formatTimestamp(unix_timestamp) {
            var m = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var d = new Date(unix_timestamp * 1000);
            return [m[d.getMonth()], ' ', d.getDate(), ', ', d.getFullYear(), " ",
                (d.getHours() % 12 || 12), ":", (d.getMinutes() < 10 ? '0' : '') + d.getMinutes(),
                " ", d.getHours() >= 12 ? 'PM' : 'AM'].join('');
        }

        function formatFileSize(bytes) {
            var s = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'];
            for (var pos = 0; bytes >= 1000; pos++, bytes /= 1024) ;
            var d = Math.round(bytes * 10);
            return pos ? [parseInt(d / 10), ".", d % 10, " ", s[pos]].join('') : bytes + ' bytes';
        }

        $('#dirsearch').on('input', function () {
            var filter = $.trim($(this).val());
            if (filter != "") {
                filter = filter.split(' ').join('-');
                $('#table').find('td.first > a.name').each(function (i) {
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).closest('tr').stop().fadeOut(150);
                    } else {
                        $(this).closest('tr').stop().fadeIn(150);
                    }
                });
            } else {
                $('#table').find('tr').stop().fadeIn(150);
            }
        });
    })
</script>