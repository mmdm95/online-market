<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Models\FAQModel;
use App\Logic\Models\OurTeamModel;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
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
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function contact()
    {
        $data = [];

        var_dump(is_post(), request()->getMethod());
        var_dump($_POST, $_GET);
        if (is_post()) {
            // submit contact form
            $data['contact_errors'] = [
                'oh noo!',
                'go go go...'
            ];
            $data['contact_success'] = 'OK!';
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/contact');
        return $this->render($data);
    }

    public function contactPost()
    {
        var_dump(is_post(), request()->getMethod());
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