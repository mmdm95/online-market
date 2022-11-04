<?php

namespace App\Logic\Models;

class SettingModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_SETTINGS;

    /**
     * @param array $info
     * @return bool
     */
    public function updateMainSetting(array $info): bool
    {
        $res1 = $this->update([
            'setting_value' => $info[SETTING_LOGO],
        ], 'setting_name=:name', ['name' => SETTING_LOGO]);
        $res2 = $this->update([
            'setting_value' => $info[SETTING_LOGO_LIGHT],
        ], 'setting_name=:name', ['name' => SETTING_LOGO_LIGHT]);
        $res3 = $this->update([
            'setting_value' => $info[SETTING_FAVICON],
        ], 'setting_name=:name', ['name' => SETTING_FAVICON]);
        $res4 = $this->update([
            'setting_value' => $info[SETTING_TITLE],
        ], 'setting_name=:name', ['name' => SETTING_TITLE]);
        $res5 = $this->update([
            'setting_value' => $info[SETTING_DESCRIPTION],
        ], 'setting_name=:name', ['name' => SETTING_DESCRIPTION]);
        $res6 = $this->update([
            'setting_value' => $info[SETTING_KEYWORDS],
        ], 'setting_name=:name', ['name' => SETTING_KEYWORDS]);
        $res7 = $this->update([
            'setting_value' => $info[SETTING_LOGO_FOOTER],
        ], 'setting_name=:name', ['name' => SETTING_LOGO_FOOTER]);
        $res8 = $this->update([
            'setting_value' => $info[SETTING_LOGO_LIGHT_FOOTER],
        ], 'setting_name=:name', ['name' => SETTING_LOGO_LIGHT_FOOTER]);

        return $res1 && $res2 && $res3 && $res4 && $res5 && $res6 && $res7 && $res8;
    }

    /**
     * @param array $info
     * @return bool
     */
    public function updateBuySetting(array $info): bool
    {
        $res1 = $this->update([
            'setting_value' => $info[SETTING_STORE_PROVINCE],
        ], 'setting_name=:name', ['name' => SETTING_STORE_PROVINCE]);
        $res2 = $this->update([
            'setting_value' => $info[SETTING_STORE_CITY],
        ], 'setting_name=:name', ['name' => SETTING_STORE_CITY]);
        $res3 = $this->update([
            'setting_value' => $info[SETTING_CURRENT_CITY_POST_PRICE],
        ], 'setting_name=:name', ['name' => SETTING_CURRENT_CITY_POST_PRICE]);
        $res4 = $this->update([
            'setting_value' => $info[SETTING_MIN_FREE_PRICE],
        ], 'setting_name=:name', ['name' => SETTING_MIN_FREE_PRICE]);

        return $res1 && $res2 && $res3 && $res4;
    }

    /**
     * @param array $info
     * @return bool
     */
    public function updateSMSSetting(array $info): bool
    {
        $res1 = $this->update([
            'setting_value' => $info[SETTING_SMS_ACTIVATION],
        ], 'setting_name=:name', ['name' => SETTING_SMS_ACTIVATION]);
        $res2 = $this->update([
            'setting_value' => $info[SETTING_SMS_RECOVER_PASS],
        ], 'setting_name=:name', ['name' => SETTING_SMS_RECOVER_PASS]);
        $res3 = $this->update([
            'setting_value' => $info[SETTING_SMS_BUY],
        ], 'setting_name=:name', ['name' => SETTING_SMS_BUY]);
        $res4 = $this->update([
            'setting_value' => $info[SETTING_SMS_ORDER_STATUS],
        ], 'setting_name=:name', ['name' => SETTING_SMS_ORDER_STATUS]);
        $res5 = $this->update([
            'setting_value' => $info[SETTING_SMS_WALLET_CHARGE],
        ], 'setting_name=:name', ['name' => SETTING_SMS_WALLET_CHARGE]);
        $res6 = $this->update([
            'setting_value' => $info[SETTING_SMS_RETURN_ORDER_STATUS],
        ], 'setting_name=:name', ['name' => SETTING_SMS_RETURN_ORDER_STATUS]);

        return $res1 && $res2 && $res3 && $res4 && $res5 && $res6;
    }

    /**
     * @param array $info
     * @return bool
     */
    public function updateContactSetting(array $info): bool
    {
        $res1 = $this->update([
            'setting_value' => $info[SETTING_MAIN_PHONE],
        ], 'setting_name=:name', ['name' => SETTING_MAIN_PHONE]);
        $res2 = $this->update([
            'setting_value' => $info[SETTING_ADDRESS],
        ], 'setting_name=:name', ['name' => SETTING_ADDRESS]);
        $res3 = $this->update([
            'setting_value' => $info[SETTING_PHONES],
        ], 'setting_name=:name', ['name' => SETTING_PHONES]);
        $res4 = $this->update([
            'setting_value' => $info[SETTING_FEATURES],
        ], 'setting_name=:name', ['name' => SETTING_FEATURES]);
        $res5 = $this->update([
            'setting_value' => $info[SETTING_EMAIL],
        ], 'setting_name=:name', ['name' => SETTING_EMAIL]);
        $res6 = $this->update([
            'setting_value' => $info[SETTING_LAT_LNG],
        ], 'setting_name=:name', ['name' => SETTING_LAT_LNG]);

        return $res1 && $res2 && $res3 && $res4 && $res5 && $res6;
    }

    /**
     * @param array $info
     * @return bool
     */
    public function updateSocialSetting(array $info): bool
    {
        $res1 = $this->update([
            'setting_value' => $info[SETTING_SOCIAL_TELEGRAM],
        ], 'setting_name=:name', ['name' => SETTING_SOCIAL_TELEGRAM]);
        $res2 = $this->update([
            'setting_value' => $info[SETTING_SOCIAL_INSTAGRAM],
        ], 'setting_name=:name', ['name' => SETTING_SOCIAL_INSTAGRAM]);
        $res3 = $this->update([
            'setting_value' => $info[SETTING_SOCIAL_WHATSAPP],
        ], 'setting_name=:name', ['name' => SETTING_SOCIAL_WHATSAPP]);

        return $res1 && $res2 && $res3;
    }

    /**
     * @param array $info
     * @return bool
     */
    public function updateFooterSetting(array $info): bool
    {
        $res1 = $this->update([
            'setting_value' => $info[SETTING_FOOTER_TINY_DESC],
        ], 'setting_name=:name', ['name' => SETTING_FOOTER_TINY_DESC]);
        $res2 = $this->update([
            'setting_value' => $info[SETTING_FOOTER_NAMADS],
        ], 'setting_name=:name', ['name' => SETTING_FOOTER_NAMADS]);
        $res3 = $this->update([
            'setting_value' => $info[SETTING_FOOTER_SECTION_1],
        ], 'setting_name=:name', ['name' => SETTING_FOOTER_SECTION_1]);
        $res4 = $this->update([
            'setting_value' => $info[SETTING_FOOTER_SECTION_2],
        ], 'setting_name=:name', ['name' => SETTING_FOOTER_SECTION_2]);
        $res5 = $this->update([
            'setting_value' => $info[SETTING_FOOTER_COPYRIGHT],
        ], 'setting_name=:name', ['name' => SETTING_FOOTER_COPYRIGHT]);

        return $res1 && $res2 && $res3 && $res4 && $res5;
    }

    /**
     * @param array $info
     * @return bool
     */
    public function updateIndexPageSetting(array $info): bool
    {
        $res1 = $this->update([
            'setting_value' => $info[SETTING_INDEX_TABBED_SLIDER],
        ], 'setting_name=:name', ['name' => SETTING_INDEX_TABBED_SLIDER]);
        $res2 = $this->update([
            'setting_value' => $info[SETTING_INDEX_3_IMAGES],
        ], 'setting_name=:name', ['name' => SETTING_INDEX_3_IMAGES]);
        $res3 = $this->update([
            'setting_value' => $info[SETTING_INDEX_GENERAL_SLIDERS],
        ], 'setting_name=:name', ['name' => SETTING_INDEX_GENERAL_SLIDERS]);
        $res4 = $this->update([
            'setting_value' => $info[SETTING_INDEX_MAIN_SLIDER_SIDE_IMAGES],
        ], 'setting_name=:name', ['name' => SETTING_INDEX_MAIN_SLIDER_SIDE_IMAGES]);
        $res5 = $this->update([
            'setting_value' => $info[SETTING_INDEX_TABBED_SLIDER_SIDE_IMAGE],
        ], 'setting_name=:name', ['name' => SETTING_INDEX_TABBED_SLIDER_SIDE_IMAGE]);

        return $res1 && $res2 && $res3 && $res4 && $res5;
    }

    /**
     * @param array $info
     * @return bool
     */
    public function updateOtherSetting(array $info): bool
    {
        $res1 = $this->update([
            'setting_value' => $info[SETTING_PRODUCT_EACH_PAGE],
        ], 'setting_name=:name', ['name' => SETTING_PRODUCT_EACH_PAGE]);
        $res2 = $this->update([
            'setting_value' => $info[SETTING_BLOG_EACH_PAGE],
        ], 'setting_name=:name', ['name' => SETTING_BLOG_EACH_PAGE]);
        $res3 = $this->update([
            'setting_value' => $info[SETTING_DEFAULT_IMAGE_PLACEHOLDER],
        ], 'setting_name=:name', ['name' => SETTING_DEFAULT_IMAGE_PLACEHOLDER]);

        return $res1 && $res2 && $res3;
    }
}