<?php

namespace App\Logic\Models;

use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

class RoleModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_ROLES;

    /**
     * @param string|null $role_name
     * @return int|null
     */
    public function getIDFromRoleName(?string $role_name): ?int
    {
        if (empty($role_name)) return null;
        $select = $this->connector->select();
        $select
            ->from($this->table)
            ->cols(['id'])
            ->where('name=:role_name')
            ->bindValues(['role_name' => $role_name]);

        $id = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (!count($id)) return null;
        return (int)$id[0]['id'];
    }

    public function addRolesToUser($username, array $roles): bool
    {
        $this->db->beginTransaction();

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        $userId = $userModel->getIDFromUsername($username);
        if (empty($userId)) {
            $this->db->rollBack();
            return false;
        }

        $res = true;
        foreach ($roles as $role) {
            $roleId = $this->getIDFromRoleName($role);

            // if there is no role with specific id
            if (empty($roleId)) {
                $this->db->rollBack();
                return false;
            }

            $insert = $this->connector->insert();
            $insert
                ->into(self::TBL_USER_ROLE)
                ->cols([
                    'user_id' => $userId,
                    'role_id' => $roleId,
                ]);
            $stmt = $this->db->prepare($insert->getStatement());
            $res = $res && $stmt->execute($insert->getBindValues());
        }

        if ($res) {
            $this->db->commit();
        } else {
            $this->db->rollBack();
        }
        return $res;
    }

    /**
     * @param $username
     * @return bool
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function deleteUserRoles($username): bool
    {
        $this->db->beginTransaction();

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        $userId = $userModel->getIDFromUsername($username);
        if (empty($userId)) {
            $this->db->rollBack();
            return false;
        }

        $delete = $this->connector->delete();
        $delete
            ->from(self::TBL_USER_ROLE)
            ->where('user_id=:uId')
            ->bindValues([
                'uId' => $userId,
            ]);
        $stmt = $this->db->prepare($delete->getStatement());
        $res = $stmt->execute($delete->getBindValues());

        if ($res) {
            $this->db->commit();
        } else {
            $this->db->rollBack();
        }
        return $res;
    }
}