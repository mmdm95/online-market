<?php

namespace App\Logic\Validations;

use Sim\Form\Abstracts\AbstractValidation;

class AlphaNumericSpaceValidation extends AbstractValidation
{
    /**
     * @var string
     */
    protected $error_message = 'You should just use alpha, number, dash, underline and space.';

    /**
     * Please specify [scalar value] to validate
     *
     * {@inheritdoc}
     */
    public function validate(...$_): bool
    {
        if (count($_) < 1 || !is_scalar($_[0])) {
            return false;
        }

        [$value] = $_;
        return (bool)preg_match('/^([0-9a-z-_\s])+$/i', (string)$value);
    }
}