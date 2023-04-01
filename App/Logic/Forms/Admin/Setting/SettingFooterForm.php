<?php

namespace App\Logic\Forms\Admin\Setting;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SettingModel;
use App\Logic\Utils\FooterUtil;
use voku\helper\AntiXSS;

class SettingFooterForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function validate(): array
    {
        /**
         * @var FooterUtil $footerUtil
         */
        $footerUtil = container()->get(FooterUtil::class);
        // assemble footer section 1
        $footerSection1Title = input()->post('inp-setting-sec1-title')->getValue() ?: '';
        $footerSection1Names = input()->post('inp-setting-sec1-name') ?: [];
        $footerSection1Links = input()->post('inp-setting-sec1-link') ?: [];
        $footerSection1Priorities = input()->post('inp-setting-sec1-priority') ?: [];
        $section1 = $footerUtil->assembleFooterLinks($footerSection1Title, $footerSection1Names, $footerSection1Links, $footerSection1Priorities);
        // assemble footer section 2
        $footerSection2Title = input()->post('inp-setting-sec2-title')->getValue() ?: '';
        $footerSection2Names = input()->post('inp-setting-sec2-name') ?: [];
        $footerSection2Links = input()->post('inp-setting-sec2-link') ?: [];
        $footerSection2Priorities = input()->post('inp-setting-sec2-priority') ?: [];
        $section2 = $footerUtil->assembleFooterLinks($footerSection2Title, $footerSection2Names, $footerSection2Links, $footerSection2Priorities);
        // assemble and validate namads
        $footerNamads = input()->post('inp-setting-namads') ?: [];
        $namads = $footerUtil->assembleFooterNamads($footerNamads);
        //
        session()->setFlash('setting-footer-sec1-assembled', $section1);
        session()->setFlash('setting-footer-sec2-assembled', $section2);
        session()->setFlash('setting-footer-namads-assembled', $namads);

        return [
            true,
            [],
        ];
    }

    /**
     * {@inheritdoc}
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function store(): bool
    {
        /**
         * @var SettingModel $settingModel
         */
        $settingModel = container()->get(SettingModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $tinyDesc = $xss->xss_clean(input()->post('inp-setting-desc', '')->getValue());
            $section1 = $xss->xss_clean(json_encode(session()->getFlash('setting-footer-sec1-assembled') ?: []));
            $section2 = $xss->xss_clean(json_encode(session()->getFlash('setting-footer-sec2-assembled') ?: []));
            // it does not need xss cleaning, it had cleaned with its own parameter before
            $namads = json_encode(session()->getFlash('setting-footer-namads-assembled') ?: []);
            $copyright = $xss->xss_clean(input()->post('inp-setting-copyright', '')->getValue());

            return $settingModel->updateFooterSetting([
                SETTING_FOOTER_TINY_DESC => trim($tinyDesc),
                SETTING_FOOTER_NAMADS => trim($namads),
                SETTING_FOOTER_SECTION_1 => trim($section1),
                SETTING_FOOTER_SECTION_2 => trim($section2),
                SETTING_FOOTER_COPYRIGHT => trim($copyright),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
