<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Forms\ContactForm;
use App\Logic\Models\ContactUsModel;
use App\Logic\Models\FAQModel;
use App\Logic\Models\OurTeamModel;
use App\Logic\Models\StaticPageModel;
use App\Logic\Models\UserModel;
use App\Logic\Validations\FormValidations;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class PageController extends AbstractHomeController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function about()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/main/about');

        /**
         * @var OurTeamModel $ourTeamModel
         */
        $ourTeamModel = \container()->get(OurTeamModel::class);

        return $this->render([
            'our_team' => $ourTeamModel->get(['name', 'position', 'images', 'socials']),
        ]);
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
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function faq()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/main/faq');

        /**
         * @var FAQModel $faqModel
         */
        $faqModel = \container()->get(FAQModel::class);

        return $this->render([
            'faq' => $faqModel->get(['question', 'answer'], 'publish=:pub', ['pub' => DB_YES]),
        ]);
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
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws FormException
     */
    public function contact()
    {
        $data = [];

        if (is_post()) {
            /**
             * @var ContactForm $contactForm
             */
            $contactForm = container()->get(ContactForm::class);
            [$status, $errors] = $contactForm->validate();
            if ($status) {
                $res = $contactForm->store();
                // success or warning message
                if ($res) {
                    $data['contact_success'] = 'اطلاعات با موفقیت ثبت شد.';
                } else {
                    $data['contact_warning'] = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';
                }
            } else {
                $data['contact_errors'] = $errors;
            }
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/contact');
        return $this->render($data);
    }

    /**
     * @param $url
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function pages($url)
    {
        /**
         * @var StaticPageModel $pageModel
         */
        $pageModel = container()->get(StaticPageModel::class);

        $page = $pageModel->get(['title', 'body'], 'url=:url', ['url' => $url ?? '']);

        if (!count($page)) {
            $this->show404();
        } else {
            $page = $page[0];
            $this->setLayout($this->main_layout)->setTemplate('view/main/static-page');
            return $this->render([
                'title' => title_concat(\config()->get('settings.title.value'), $page['title']),
                'sub_title' => $page['title'],
                'breadcrumb' => [
                    [
                        'url' => url('home.index'),
                        'text' => 'خانه',
                        'is_active' => false,
                    ],
                    [
                        'text' => $page['title'],
                        'is_active' => true,
                    ],
                ],
                'page_content' => $page['body'],
            ]);
        }
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function notFound()
    {
        return $this->show404();
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function adminNotFound()
    {
        return $this->setTemplate('error.404')->show404();
    }

    /**
     * @return string
     * @throws \ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function serverError()
    {
        return $this->show500();
    }
}