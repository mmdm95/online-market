<?php

namespace App\Logic\Models;

use Aura\SqlQuery\Exception as AuraException;
use Pecee\Http\Input\InputItem;
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
     * @var string
     */
    protected $table = self::TBL_USERS;

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
     * @param array $user_info
     * @param array $roles
     * @return bool
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function registerUser(array $user_info, array $roles = [ROLE_USER]): bool
    {
        $this->db->beginTransaction();

        // insert new user to database and get the id
        $userId = $this->insert($user_info, true);

        // if there is no user with specific id
        if (empty($userId)) {
            $this->db->rollBack();
            return false;
        }

        /**
         * @var RoleModel $roleModel
         */
        $roleModel = container()->get(RoleModel::class);

        $res2 = false;
        foreach ($roles as $role) {
            $tmpRole = $role;
            if ($role instanceof inputItem) {
                $tmpRole = $role->getValue();
            }

            // get role id
            $roleId = $roleModel->getIDFromRoleName($tmpRole);

            // if there is no role with specific id
            if (empty($roleId)) {
                $this->db->rollBack();
                return false;
            }

            // add role to user
            $insert = $this->connector->insert();
            $insert
                ->into(self::TBL_USER_ROLE)
                ->cols([
                    'user_id' => $userId,
                    'role_id' => $roleId
                ]);
            $stmt = $this->db->prepare($insert->getStatement());
            $res2 = $stmt->execute($insert->getBindValues());

            // if role insertion failed
            if (!$res2) {
                $this->db->rollBack();
                return false;
            }
        }

        $username = $user_info['username'] ?? null;
        if (empty($username)) {
            $this->db->rollBack();
            return false;
        }

        // add wallet info
        $insert = $this->connector->insert();
        $insert
            ->into(self::TBL_WALLET)
            ->cols([
                'username' => $username,
                'balance' => 0,
            ]);
        $stmt = $this->db->prepare($insert->getStatement());
        $res3 = $stmt->execute($insert->getBindValues());

        if ($res2 && $res3) {
            $this->db->commit();
            return true;
        } else {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * @param string $username
     * @param array $user_info
     * @param string $where
     * @param array $bind_values
     * @return bool
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function updateNRegisterUser(string $username, array $user_info, string $where, array $bind_values = []): bool
    {
        $this->db->beginTransaction();

        if (empty($username)) {
            $this->db->rollBack();
            return false;
        }

        // get user id from username
        $userId = $this->getIDFromUsername($username);
        /**
         * @var RoleModel $roleModel
         */
        $roleModel = container()->get(RoleModel::class);
        // get role id
        $roleId = $roleModel->getIDFromRoleName(ROLE_USER);
        // if there is no user with specific id or
        // if there is no role with specific id
        if (empty($userId) || empty($roleId)) {
            $this->db->rollBack();
            return false;
        }
        // update user info
        $update = $this->connector->update();
        $res = $update
            ->table($this->table)
            ->cols($user_info)
            ->where($where)
            ->bindValues($bind_values);

        // get role id
        $roleId = $roleModel->getIDFromRoleName(ROLE_USER);

        // if there is no role with specific id
        if (empty($roleId)) {
            $this->db->rollBack();
            return false;
        }

        // add role to user
        $insert = $this->connector->insert();
        $insert
            ->into(self::TBL_USER_ROLE)
            ->cols([
                'user_id' => $userId,
                'role_id' => $roleId
            ]);
        $stmt = $this->db->prepare($insert->getStatement());
        $res2 = $stmt->execute($insert->getBindValues());

        // if role insertion failed
        if (!$res2) {
            $this->db->rollBack();
            return false;
        }

        // add wallet info
        $insert = $this->connector->insert();
        $insert
            ->into(self::TBL_WALLET)
            ->cols([
                'username' => $username,
                'balance' => 0,
            ]);
        $stmt = $this->db->prepare($insert->getStatement());
        $res3 = $stmt->execute($insert->getBindValues());

        if ($res && $res2 && $res3) {
            $this->db->commit();
            return true;
        } else {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * @param int|null $id
     * @return string|null
     */
    public function getUsernameFromID(?int $id): ?string
    {
        if (0 === $id || empty($id)) return null;
        $select = $this->connector->select();
        $select
            ->from($this->table)
            ->cols(['username'])
            ->where('id=:id')
            ->bindValues(['id' => $id]);

        $username = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (!count($username)) return null;
        return $username[0]['username'];
    }

    /**
     * @param string|null $username
     * @return int|null
     */
    public function getIDFromUsername(?string $username): ?int
    {
        if (empty($id)) return null;
        $select = $this->connector->select();
        $select
            ->from($this->table)
            ->cols(['id'])
            ->where('username=:username')
            ->bindValues(['username' => $username]);

        $id = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (!count($id)) return null;
        return (int)$id[0]['id'];
    }

    /**
     * Use [u for users], [r for roles], [ur for user_role]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @param int|null $limit
     * @param int|null $offset
     * @param array $order_by
     * @param array $group_by
     * @return array
     */
    public function getUsers(
        array $columns = ['u.*', 'r.id AS role_id', 'r.name AS role_name', 'r.is_admin'],
        ?string $where = null,
        array $bind_values = [],
        int $limit = null,
        int $offset = 0,
        array $order_by = ['u.id DESC'],
        array $group_by = ['u.id']
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from($this->table . ' AS u')
            ->cols($columns)
            ->offset($offset)
            ->orderBy($order_by)
            ->groupBy($group_by);

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
     * Use [u for users], [r for roles], [ur for user_role]
     *
     * @param array $columns
     * @param string|null $where
     * @param array $bind_values
     * @return array
     */
    public function getSingleUser(
        ?string $where = null,
        array $bind_values = [],
        array $columns = ['u.*', 'r.id AS role_id', 'r.name AS role_name', 'r.is_admin']
    ): array
    {
        $res = $this->getUsers($columns, $where, $bind_values, 1);
        if (count($res)) return $res[0];
        return [];
    }

    /**
     * Use [u for users], [r for roles], [ur for user_role]
     *
     * @param string|null $where
     * @param array $bind_values
     * @return int
     */
    public function getUsersCount(?string $where = null, array $bind_values = []): int
    {
        $res = $this->getUsers(['u.id', 'COUNT(DISTINCT(u.id)) AS count'], $where, $bind_values, null, 0, [], []);
        if (count($res)) {
            return (int)$res[0]['count'];
        }
        return 0;
    }

    /**
     * Use [r for roles], [ur for user_role]
     *
     * @param int $user_id
     * @param string|null $where
     * @param array $bind_values
     * @param array $columns
     * @return array
     */
    public function getUserRoles(
        int $user_id,
        ?string $where = null,
        array $bind_values = [],
        array $columns = ['r.*', 'ur.user_id']
    ): array
    {
        $select = $this->connector->select();
        $select
            ->from(self::TBL_ROLES . ' AS r')
            ->cols($columns)
            ->where('ur.user_id=:u_id')
            ->bindValues(['u_id' => $user_id]);

        if (!empty($where)) {
            $select
                ->where($where)
                ->bindValues($bind_values);
        }

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
     * @param array $data
     * @param int $user_id
     * @return bool
     */
    public function updateWithID(array $data, int $user_id): bool
    {
        $update = $this->connector->update();
        $update
            ->table($this->table)
            ->cols($data)
            ->where('id=:id')
            ->bindValues([
                'id' => $user_id,
            ]);

        $stmt = $this->db->prepare($update->getStatement());
        return $stmt->execute($update->getBindValues());
    }

    /**
     * @param int $user_id
     * @param bool $soft
     * @return bool
     */
    public function deleteWithID(int $user_id, bool $soft = false): bool
    {
        if ($soft) {
            $deleteOrUpdate = $this->connector->update();
            $deleteOrUpdate
                ->table($this->table)
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
                ->from($this->table)
                ->where('id=:id')
                ->bindValues([
                    'id' => $user_id,
                ]);
        }

        $stmt = $this->db->prepare($deleteOrUpdate->getStatement());
        return $stmt->execute($deleteOrUpdate->getBindValues());
    }
}