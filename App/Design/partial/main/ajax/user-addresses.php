<?php if (isset($addresses) && is_array($addresses) && count($addresses) > 0): ?>
    <?php $k = 0; ?>
    <?php foreach ($addresses as $address): ?>
        <?php
        $item = [
            'full_name' => $address['full_name'],
            'mobile' => $address['mobile'],
            'province_id' => $address['province_id'],
            'city_id' => $address['city_id'],
            'postal_code' => $address['postal_code'],
            'address' => $address['address'],
        ];
        ?>
        <div class="form-group <?= 0 == $k ? 'border-bottom' : ''; ?>">
            <input class="form-check-input"
                <?= 0 == $k ? 'checked="checked"' : ''; ?>
                   type="radio" name="address-choose-inp"
                   id="addr_val_<?= ($k + 1); ?>" data-address-obj='<?= json_encode($item); ?>'>
            <label class="form-check-label w-100" for="addr_val_<?= ($k + 1); ?>">
                <span class="form-group d-block">
                    <?= $address['address']; ?>
                </span>
                <span class="form-group d-block">
                    <span class="d-flex align-items-center mb-3">
                        <i class="linearicons-map-marker-user address-icon-size mr-2"
                           aria-hidden="true"></i>
                        <span class="mr-2 text-muted font-size-sm">استان و شهر:</span>
                        <span>
                            <?= $address['province_name']; ?>
                            ،
                            <?= $address['city_name']; ?>
                        </span>
                    </span>
                </span>
                <span class="form-group d-block">
                    <span class="d-flex align-items-center mb-3">
                        <i class="linearicons-envelope address-icon-size mr-2"
                           aria-hidden="true"></i>
                        <span class="mr-2 text-muted font-size-sm">کد پستی:</span>
                        <span>
                            <?= $address['postal_code']; ?>
                        </span>
                    </span>
                </span>
                <span class="form-group d-block">
                    <span class="d-flex align-items-center mb-3">
                        <i class="linearicons-phone-bubble address-icon-size mr-2"
                           aria-hidden="true"></i>
                        <span class="mr-2 text-muted font-size-sm">موبایل:</span>
                        <span>
                            <?= $address['mobile']; ?>
                        </span>
                    </span>
                </span>
                <span class="form-group d-block">
                    <span class="d-flex align-items-center mb-3">
                        <i class="linearicons-user address-icon-size mr-2"
                           aria-hidden="true"></i>
                        <span class="mr-2 text-muted font-size-sm">نام گیرنده:</span>
                        <span>
                            <?= $address['full_name']; ?>
                        </span>
                    </span>
                </span>
            </label>
        </div>
        <?php ++$k; ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php load_partial('main/not-found-rows', [
        'show_border' => false,
    ]); ?>
<?php endif; ?>