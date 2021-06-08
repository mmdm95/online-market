<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Forms\Ajax\NewsletterForm as AjaxNewsletterForm;
use App\Logic\Forms\ComplaintForm;
use App\Logic\Forms\ContactForm;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\CityModel;
use App\Logic\Models\FAQModel;
use App\Logic\Models\OurTeamModel;
use App\Logic\Models\ProvinceModel;
use App\Logic\Models\StaticPageModel;
use Jenssegers\Agent\Agent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
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
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function about()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/main/about');

        /**
         * @var OurTeamModel $ourTeamModel
         */
        $ourTeamModel = \container()->get(OurTeamModel::class);

        return $this->render([
            'our_team' => $ourTeamModel->get(['name', 'position', 'image', 'socials']),
        ]);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function contact()
    {
        $data = [];

        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(ContactForm::class, 'contact');
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
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function complaint()
    {
        $data = [];

        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(ComplaintForm::class, 'complaint');
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
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function addNewsletter()
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('شماره شما در خبرنامه با موفقیت ثبت شد.')
                ->handle(AjaxNewsletterForm::class);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    public function removeNewsletter()
    {
        // implement later if needed
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getProvinces()
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var ProvinceModel $provinceModel
             */
            $provinceModel = container()->get(ProvinceModel::class);
            $resourceHandler
                ->type(RESPONSE_TYPE_SUCCESS)
                ->data($provinceModel->get([
                    'id', 'name'
                ], 'is_deleted=:del', [
                    'del' => DB_NO,
                ]));
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $province_id
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getCities($province_id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var CityModel $cityModel
             */
            $cityModel = container()->get(CityModel::class);
            $resourceHandler
                ->type(RESPONSE_TYPE_SUCCESS)
                ->data($cityModel->get([
                    'id', 'name'
                ], 'province_id=:pId AND is_deleted=:del', [
                    'pId' => $province_id,
                    'del' => DB_NO,
                ]));
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
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
        return $this->show404([], '', 'error/404');
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