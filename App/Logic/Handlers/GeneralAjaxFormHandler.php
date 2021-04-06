<?php

namespace App\Logic\Handlers;

use App\Logic\Interfaces\IPageForm;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Interfaces\IHandler;

class GeneralAjaxFormHandler implements IHandler
{
    /**
     * @var string
     */
    private $successMessage = 'اطلاعات با موفقیت ثبت شد.';

    /**
     * @var string
     */
    private $warningMessage = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';

    /**
     * @var ResourceHandler
     */
    private $resourceHandler;

    /**
     * GeneralAjaxFormHandler constructor.
     */
    public function __construct()
    {
        $this->resourceHandler = new ResourceHandler();
    }

    /**
     * Handle all form in same manner
     * Steps:
     *   1. Send an [IPageForm] class as first parameter.
     *   2. Send a prefix for errors and it gives you
     *      [$status_result, $prefix_success, $prefix_warning, $prefix_errors]
     *      as return array.
     *
     * @param mixed ...$_
     * @return ResourceHandler
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function handle(...$_): ResourceHandler
    {
        if (1 > count($_)) {
            throw new \InvalidArgumentException("Number of arguments is invalid. Expected 1, giving " . count($_));
        }

        [$form] = $_;
        /**
         * @var IPageForm $form
         */
        $form = container()->get($form);
        [$status, $errors] = $form->validate();

        if ($status) {
            $res = $form->store();
            // success or warning message
            if ($res) {
                $this->resourceHandler
                    ->type(RESPONSE_TYPE_SUCCESS)
                    ->data($this->successMessage);
                emitter()->dispatch('form.general.ajax:success', [&$this->resourceHandler]);
            } else {
                $this->resourceHandler
                    ->type(RESPONSE_TYPE_WARNING)
                    ->errorMessage($this->warningMessage);
                emitter()->dispatch('form.general.ajax:warning', [&$this->resourceHandler]);
            }
        } else {
            $this->resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage(implode("<br>", $errors));
            emitter()->dispatch('form.general.ajax:error', [&$this->resourceHandler]);
        }

        return $this->resourceHandler;
    }

    /**
     * @param string $message
     * @return static
     */
    public function setSuccessMessage(string $message)
    {
        if (!empty($message)) {
            $this->successMessage = $message;
        }
        return $this;
    }

    /**
     * @param string $message
     * @return static
     */
    public function setWarningMessage(string $message)
    {
        if (!empty($message)) {
            $this->warningMessage = $message;
        }
        return $this;
    }
}