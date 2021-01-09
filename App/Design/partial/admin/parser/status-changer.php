<input type="checkbox" class="form-check-input-switchery __item_status_changer_btn"
       data-change-status-url="<?= $url; ?>"
       data-change-status-id="<?= $row['id']; ?>" <?= is_value_checked($status) ? 'checked="checked"' : ''; ?>>