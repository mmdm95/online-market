<?php if (count($pagination ?? [])): ?>
    <?php
    $pageBefore = $pageBefore ?? 4;
    $pageAfter = $pageAfter ?? 4;
    ?>

    <?php if ($pagination['total'] && ($pagination['last_page'] - $pagination['first_page']) != 0): ?>
        <div class="row">
            <div class="col-12 mt-2 mt-md-4">
                <ul class="pagination pagination_style1 justify-content-center">
                    <li class="page-item <?= $pagination['first_page'] == $pagination['current_page'] ? 'disabled' : ''; ?>">
                        <?php
                        $queryArr = http_build_query($_GET, null, '&', PHP_QUERY_RFC3986);
                        ?>
                        <a class="page-link" data-page-no="<?= $pagination['first_page']; ?>"
                            <?= $pagination['first_page'] != $pagination['current_page'] ? 'href="' . $pagination['base_url'] . (!empty($queryArr) ? '?' . $queryArr : '') . '"' : ''; ?>
                           tabindex="-1">
                            <i class="linearicons-arrow-left" aria-hidden="true"></i>
                        </a>
                    </li>

                    <?php if (($pagination['current_page'] - $pageBefore) > $pagination['first_page']): ?>
                        <li class="page-item disabled">
                            <a class="page-link">
                                <i class="linearicons-ellipsis" aria-label="true"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = $pagination['current_page'] - $pageBefore; $i < $pagination['current_page']; $i++): ?>
                        <?php if ($i <= 0) continue; ?>
                        <li class="page-item <?= $pagination['current_page'] == $i ? 'active' : ''; ?>">
                            <?php
                            $queryArr = http_build_query(array_merge($_GET, [
                                'page' => $i,
                            ]), null, '&', PHP_QUERY_RFC3986);
                            ?>
                            <a class="page-link" data-page-no="<?= $i; ?>"
                               href="<?= $pagination['base_url'] . (!empty($queryArr) ? '?' . $queryArr : '') . '"'; ?>">
                                <?= local_number($i); ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <?php for ($i = $pagination['current_page']; $i <= $pagination['current_page'] + $pageAfter && $i <= $pagination['last_page']; $i++): ?>
                        <li class="page-item <?= $pagination['current_page'] == $i ? 'active' : ''; ?>">
                            <?php
                            $queryArr = http_build_query(array_merge($_GET, [
                                'page' => $i,
                            ]), null, '&', PHP_QUERY_RFC3986);
                            ?>
                            <a class="page-link" data-page-no="<?= $i; ?>"
                               href="<?= $pagination['base_url'] . (!empty($queryArr) ? '?' . $queryArr : '') . '"'; ?>">
                                <?= local_number($i); ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <?php if (($pagination['current_page'] + $pageAfter) < $pagination['last_page']): ?>
                        <li class="page-item disabled">
                            <a class="page-link">
                                <i class="linearicons-ellipsis" aria-label="true"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="page-item" <?= $pagination['last_page'] == $pagination['current_page'] ? 'disabled' : ''; ?>>
                        <?php
                        $queryArr = http_build_query($_GET, null, '&', PHP_QUERY_RFC3986);
                        ?>
                        <a class="page-link" data-page-no="<?= $pagination['last_page']; ?>"
                            <?= $pagination['last_page'] != $pagination['current_page'] ? 'href="' . $pagination['base_url'] . (!empty($queryArr) ? '?' . $queryArr : '') . '"' : ''; ?>
                           tabindex="-1">
                            <i class="linearicons-arrow-right" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>