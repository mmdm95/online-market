<a class="nav-link cart_trigger"
   href="#"
   data-toggle="dropdown">
    <i class="linearicons-cart"></i>
    <span class="cart_count"><?= local_number($count ?? 0); ?></span>
</a>

<div class="cart_box dropdown-menu dropdown-menu-right">
    <?php if (count($items ?? [])): ?>
        <ul class="cart_list">
            <?php foreach ($items ?? [] as $item): ?>
                <li>
                    <a href="javascript:void(0);" class="item_remove"><i class="ion-close"></i></a>
                    <?php if (isset($item['title']) || isset($item['image'])): ?>
                        <a href="<?= url('home.product.show', [
                            'id' => $item['product_id'],
                            'slug' => $item['slug'] ?? '',
                        ]); ?>">
                            <?php if (isset($item['image'])): ?>
                                <img src="<?= url('image.show') . $item['image']; ?>"
                                     alt="<?= $item['title'] ?? 'product ' . $item['code']; ?>">
                            <?php endif; ?>
                            <?= $item['title']; ?>
                        </a>
                    <?php endif; ?>
                    <span class="cart_quantity">
                        <?= $item['qnt']; ?>
                        عدد
                        <?= number_format($item['qnt'] * (int)$item['price']); ?>
                        <span class="cart_amount">
                            <span class="price_symbole">تومان</span>
                        </span>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="cart_footer">
            <p class="cart_total">
                <strong>جمع:</strong>
                <?= local_number($total_amount ?? 0); ?>
                <span class="cart_price"> <span class="price_symbole">تومان</span></span>
            </p>
            <p class="cart_buttons">
                <a href="<?= url('home.cart'); ?>" class="btn btn-fill-out view-cart">
                    سبد خرید
                </a>
            </p>
        </div>
    <?php else: ?>
        <div>
            هیچ محصولی در سبد خرید وجود ندارد.
        </div>
    <?php endif; ?>
</div>