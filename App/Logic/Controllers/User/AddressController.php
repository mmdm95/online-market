<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Models\AddressCompanyModel;
use App\Logic\Models\AddressModel;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class AddressController extends AbstractUserController
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
    public function index()
    {
        /**
         * @var AddressModel $addressModel
         */
        $addressModel = container()->get(AddressModel::class);
        /**
         * @var AddressCompanyModel $addressCompanyModel
         */
        $addressCompanyModel = container()->get(AddressCompanyModel::class);

        $user = $this->getDefaultArguments()['user'];

        $addresses = $addressModel->getUserAddresses([
            'u_addr.id', 'u_addr.full_name', 'u_addr.mobile', 'u_addr.address',
            'u_addr.postal_code', 'c.name AS city_name', 'p.name AS province_name'
        ], 'u_addr.user_id=:id', ['id' => $user['id']]);

        $addressesCompany = $addressCompanyModel->getUserAddresses([
            'uc_addr.id', 'uc_addr.company_name', 'uc_addr.economic_code',
            'uc_addr.economic_national_id', 'uc_addr.registration_number',
            'uc_addr.landline_tel', 'uc_addr.address', 'uc_addr.postal_code',
            'c.name AS city_name', 'p.name AS province_name'
        ], 'uc_addr.user_id=:id', ['id' => $user['id']]);

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/address/index');
        return $this->render([
            'addresses' => $addresses,
            'addresses_company' => $addressesCompany,
        ]);
    }
}
