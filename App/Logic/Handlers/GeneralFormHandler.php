<?php

namespace App\Logic\Handlers;

use App\Logic\Interfaces\IPageForm;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Interfaces\IHandler;

class GeneralFormHandler implements IHandler
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
     * Handle all form in same manner
     * Steps:
     *   1. Send an [IPageForm] class as first parameter.
     *   2. Send a prefix for errors and it gives you
     *      [$status_result, $prefix_success, $prefix_warning, $prefix_errors]
     *      as return array.
     *
     * @param mixed ...$_
     * @return array
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function handle(...$_): array
    {
        if (2 !== count($_)) {
            throw new \InvalidArgumentException("Number of arguments is invalid. Expected 2, giving " . count($_));
        }

        $data = [];
        [$form, $prefix] = $_;
        /**
         * @var IPageForm $form
         */
        $form = container()->get($form);
        [$status, $errors] = $form->validate();

        if ($status) {
            $res = $form->store();
            // success or warning message
            if ($res) {
                $data["{$prefix}_success"] = $this->successMessage;
                emitter()->dispatch('form.general:success', [&$data]);
            } else {
                $data["{$prefix}_warning"] = $this->warningMessage;
                emitter()->dispatch('form.general:warning', [&$data]);
            }
        } else {
            $data["{$prefix}_errors"] = $errors;
            emitter()->dispatch('form.general:error', [&$data]);
        }

        return $data;
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