<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;
use Sim\Auth\DBAuth as Auth;
use Sim\Auth\Exceptions\IncorrectPasswordException;
use Sim\Auth\Exceptions\InvalidUserException;
use Sim\Auth\Interfaces\IDBException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

class UserModel extends BaseModel
{
    /**
     * @param array $credentials
     * @param bool $is_admin
     * @return bool
     */
    public function authenticate(array $credentials, bool $is_admin = false): bool
    {
        $authContainer = 'auth_home';
        if ($is_admin) {
            $authContainer = 'auth_admin';
        }

        /**
         * @var Auth $auth
         */
        try {
            $auth = \container()->get($authContainer);
        } catch (
        \ReflectionException |
        MethodNotFoundException |
        ParameterHasNoDefaultValueException |
        ServiceNotFoundException |
        ServiceNotInstantiableException $e
        ) {
            return false;
        }

        $res = true;
        try {
            $auth->login($credentials);
        } catch (
        IncorrectPasswordException |
        InvalidUserException |
        IDBException $e
        ) {
            $res = false;
        }

        if ($is_admin) {
            try {
                if (!$auth->isAdmin()) {
                    $auth->logout();
                    $res = false;
                }
            } catch (IDBException $e) {
                $res = false;
            }
        }

        return $res;
    }

    /**
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param int|null $limit
     * @return array
     */
    public function getUsers(
        array $columns = ['u.*', 'r.id AS role_id', 'r.name AS role_name', 'r.is_admin'],
        ?string $where = null,
        array $bind_values = [],
        int $limit = null
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_USERS . ' AS u')
            ->cols($columns)
            ->orderBy(['u.id DESC'])
            ->groupBy(['u.id']);

        try {
            $select
                ->leftJoin(
                    self::TBL_USER_ROLE . ' AS ur',
                    'ur.user_id=u.id'
                )->leftJoin(
                    self::TBL_ROLES . ' AS r',
                    'r.id=ur.role_id'
                );
        } catch (AuraException $e) {
            return [];
        }

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

        if (!empty($limit) && $limit > 0) {
            $select->limit($limit);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @return array
     */
    public function getSingleUser(
        array $columns = ['u.*', 'r.id AS role_id', 'r.name AS role_name', 'r.is_admin'],
        ?string $where = null,
        array $bind_values = []
    ): array
    {
        return $this->getUsers($columns, $where, $bind_values, 1);
    }

    /**
     * @param string|null $where
     * @param array $bindParams
     * @return int
     */
    public function getUsersCount(?string $where = null, array $bindParams = []): int
    {
        $select = $this->connector->select();
        $select
            ->cols(['COUNT(DISTINCT(u.id)) AS count'])
            ->from(self::TBL_USERS . ' AS u');

        try {
            $select->leftJoin(
                self::TBL_USER_ROLE . ' AS ur',
                'ur.user_id=u.id'
            )->leftJoin(
                self::TBL_ROLES . ' AS r',
                'r.id=ur.role_id'
            );
        } catch (AuraException $e) {
            die('unexpected error: ' . $e->getMessage());
        }

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bindParams);
        }

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * @param int $user_id
     * @return array
     */
    public function getUserRoles(int $user_id): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_ROLES . ' AS r')
            ->cols(['r.*', 'u.id AS user_id'])
            ->where('ur.user_id=:u_id')
            ->bindValues(['u_id' => $user_id]);

        try {
            $select->leftJoin(
                self::TBL_USER_ROLE . ' AS ur',
                'r.id=ur.role_id'
            );
        } catch (AuraException $e) {
            return [];
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param int $user_id
     * @return array
     */
    public function getUserAddresses(int $user_id): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_USER_ADDRESS . ' AS u_addr')
            ->cols(['u_addr.*', 'c.name AS city_name', 'p.name AS province_name'])
            ->where('u_addr.user_id=:u_id')
            ->bindValues([
                'u_id' => $user_id,
            ])
            ->orderBy(['u_addr.id DESC']);

        try {
            $select
                ->innerJoin(
                    self::TBL_PROVINCES . ' AS p',
                    'p.id=u_addr.province_id'
                )->innerJoin(
                    self::TBL_CITIES . ' AS c',
                    'c.id=u_addr.city_id'
                );
        } catch (AuraException $e) {
            return [];
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    public function getSingleUserAddress(int $addr_id, ?int $user_id = null): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_USER_ADDRESS . ' AS u_addr')
            ->cols(['u_addr.*', 'c.name AS city_name', 'p.name AS province_name'])
            ->where('u_addr.id=:addr_id')
            ->bindValues([
                'addr_id' => $addr_id,
            ])
            ->limit(1);

        if (!empty($user_id) && 0 !== $user_id) {
            $select
                ->where('u_addr.user_id=:u_id')
                ->bindValues([
                    'u_id' => $user_id,
                ]);
        }

        try {
            $select
                ->innerJoin(
                    self::TBL_PROVINCES . ' AS p',
                    'p.id=u_addr.province_id'
                )->innerJoin(
                    self::TBL_CITIES . ' AS c',
                    'c.id=u_addr.city_id'
                );
        } catch (AuraException $e) {
            return [];
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    /**
     * @param int $user_id
     * @param bool $soft
     * @return bool
     */
    public function delete(int $user_id, bool $soft = false): bool
    {
        if ($soft) {
            $deleteOrUpdate = $this->connector->update();
            $deleteOrUpdate
                ->table(self::TBL_USERS)
                ->cols([
                    'delete' => 1,
                ])
                ->where('id=:id')
                ->bindValues([
                    'id' => $user_id,
                ]);
        } else {
            $deleteOrUpdate = $this->connector->delete();
            $deleteOrUpdate
                ->from(self::TBL_USERS)
                ->where('id=:id')
                ->bindValues([
                    'id' => $user_id,
                ]);
        }

        $stmt = $this->db->prepare($deleteOrUpdate->getStatement());
        return $stmt->execute($deleteOrUpdate->getBindValues());
    }

    /**
     * @param array $data
     * @param int $user_id
     * @return bool
     */
    public function update(array $data, int $user_id): bool
    {
        $update = $this->connector->update();
        $update
            ->table(self::TBL_USERS)
            ->cols($data)
            ->where('id=:id')
            ->bindValues([
                'id' => $user_id,
            ]);

        $stmt = $this->db->prepare($update->getStatement());
        return $stmt->execute($update->getBindValues());
    }

    /**
     * @param int $addr_id
     * @return bool
     */
    public function deleteAddress(int $addr_id): bool
    {
        $delete = $this->connector->delete();
        $delete
            ->from(self::TBL_USER_ADDRESS)
            ->where('id=:id')
            ->bindValues([
                'id' => $addr_id,
            ]);

        $stmt = $this->db->prepare($delete->getStatement());
        return $stmt->execute($delete->getBindValues());
    }

    /**
     * @param array $data
     * @param int $addr_id
     * @return bool
     */
    public function updateAddress(array $data, int $addr_id): bool
    {
        $update = $this->connector->update();
        $update
            ->table(self::TBL_USER_ADDRESS)
            ->cols($data)
            ->where('id=:id')
            ->bindValues([
                'id' => $addr_id,
            ]);

        $stmt = $this->db->prepare($update->getStatement());
        return $stmt->execute($update->getBindValues());
    }
}