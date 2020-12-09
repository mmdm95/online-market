<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Forms\ComplaintForm;
use App\Logic\Forms\ContactForm;
use App\Logic\Forms\NewsletterForm;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\FAQModel;
use App\Logic\Models\OurTeamModel;
use App\Logic\Models\StaticPageModel;
use Jenssegers\Agent\Agent;
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
    public function complaint()
    {
        $data = [];

        if (is_post()) {
            /**
             * @var ComplaintForm $complaintForm
             */
            $complaintForm = container()->get(ComplaintForm::class);
            [$status, $errors] = $complaintForm->validate();
            if ($status) {
                $res = $complaintForm->store();
                // success or warning message
                if ($res) {
                    $data['complaint_success'] = 'اطلاعات با موفقیت ثبت شد.';
                } else {
                    $data['complaint_warning'] = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';
                }
            } else {
                $data['complaint_errors'] = $errors;
            }
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/complaint');
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
            return $this->show404();
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws FormException
     */
    public function addNewsletter()
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var NewsletterForm $registerForm
             */
            $registerForm = container()->get(NewsletterForm::class);
            [$status, $formattedErrors] = $registerForm->validate();
            if ($status) {
                $res = $registerForm->store();
                if ($res) {
                    $resourceHandler->data('شماره شما در خبرنامه با موفقیت ثبت شد.');
                } else {
                    $resourceHandler->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
                }
            } else {
                $resourceHandler->errorMessage(encode_html($formattedErrors));
            }
        } else {
            response()->httpCode(403);
            $resourceHandler->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    public function removeNewsletter()
    {
        // implement later if needed
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
        return $this->show404([], null, 'error.404');
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