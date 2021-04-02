<?php


namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\Setting\SettingBuyForm;
use App\Logic\Forms\Admin\Setting\SettingContactForm;
use App\Logic\Forms\Admin\Setting\SettingFooterForm;
use App\Logic\Forms\Admin\Setting\SettingMainForm;
use App\Logic\Forms\Admin\Setting\SettingOtherForm;
use App\Logic\Forms\Admin\Setting\SettingPageAboutForm;
use App\Logic\Forms\Admin\Setting\SettingPageIndexForm;
use App\Logic\Forms\Admin\Setting\SettingSMSForm;
use App\Logic\Forms\Admin\Setting\SettingSocialForm;
use App\Logic\Forms\Admin\Setting\SettingTopMenuForm;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Utils\ConfigUtil;
use ReflectionException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class SettingController extends AbstractAdminController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function main()
    {
        $data = [];
        if (is_post()) {
            $this->settingRewriteEvent();
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(SettingMainForm::class, 'setting_main');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/setting/main');
        return $this->render($data);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function topMenu()
    {
        $data = [];
        if (is_post()) {
            $this->settingRewriteEvent();
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(SettingTopMenuForm::class, 'setting_top_menu');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/setting/top-menu');
        return $this->render($data);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function buy()
    {
        $data = [];
        if (is_post()) {
            $this->settingRewriteEvent();
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(SettingBuyForm::class, 'setting_buy');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/setting/buy');
        return $this->render($data);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function sms()
    {
        $data = [];
        if (is_post()) {
            $this->settingRewriteEvent();
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(SettingSMSForm::class, 'setting_sms');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/setting/sms');
        return $this->render($data);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function contact()
    {
        $data = [];
        if (is_post()) {
            $this->settingRewriteEvent();
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(SettingContactForm::class, 'setting_contact');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/setting/contact');
        return $this->render($data);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function social()
    {
        $data = [];
        if (is_post()) {
            $this->settingRewriteEvent();
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(SettingSocialForm::class, 'setting_social');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/setting/social');
        return $this->render($data);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function footer()
    {
        $data = [];
        if (is_post()) {
            $this->settingRewriteEvent();
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(SettingFooterForm::class, 'setting_footer');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/setting/footer');
        return $this->render($data);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function indexPage()
    {
        $data = [];
//        if (is_post()) {
//            $this->settingRewriteEvent();
//            $formHandler = new GeneralFormHandler();
//            $data = $formHandler->handle(SettingPageIndexForm::class, 'setting_index');
//        }

        $this->setLayout($this->main_layout)->setTemplate('view/setting/index-page');
        return $this->render($data);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function aboutPage()
    {
        $data = [];
        if (is_post()) {
            $this->settingRewriteEvent();
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(SettingPageAboutForm::class, 'setting_about');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/setting/about-page');
        return $this->render($data);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function other()
    {
        $data = [];
        if (is_post()) {
            $this->settingRewriteEvent();
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(SettingOtherForm::class, 'setting_other');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/setting/other');
        return $this->render($data);
    }

    /**
     * Fetch all settings from db and store it to config again
     */
    private function settingRewriteEvent()
    {
        emitter()->addListener('form.general:success', function () {
            /**
             * @var ConfigUtil $configUtil
             */
            $configUtil = \container()->get(ConfigUtil::class);
            $configUtil->pullConfig();
        });
    }
}