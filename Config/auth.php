<?php

return [
    /**
     * You can put as many namespaces you want but
     * you should handle them in Container by yourself
     */
    'namespaces' => [
        /**
         * Mostly it's up to you to create a structure for your
         * namespace and use it but it's just a proposal
         *
         * 'namespace name' => [
         *   'expire_time' => your expire time for this namespace in seconds,
         *   'suspend_time' => your needed suspend time for this namespace in seconds,
         *   ...
         * ],
         * ...
         */
        'home' => [
            'expire_time' => 31536000 /* 1 year */,
            'suspend_time' => 1800 /* 30 minutes */,
        ],
        'admin' => [
            'expire_time' => 31536000 /* 1 year */,
            'suspend_time' => 1800 /* 30 minutes */,
        ],
    ],
    /**
     * Usually you don't need to change structure
     */
    'structure' => [
        /**
         * Follow this only rule and you'll never have
         * problem with config file(s):
         * -- DO NOT CHANGE ANY KEY, JUST CHANGE VALUES --
         *
         * Please do not change keys to prevent
         * any problem :)
         */
        'blueprints' => [
            /**
             * lib table alias name => [
             *   'table_name' => actual table's name
             *   'columns' => [columns' name array],
             *   'types' => [
             *     column's name from columns section above => the sql type etc.
             *     ...
             *   ],
             *   'constraints' => [
             *     the keys are not important and values are the constraints
             *     ...
             *   ],
             *   ...
             * ],
             * ...
             *
             * Note:
             *   Please do not change keys and just
             *   change values of them
             */
            'users' => [
                'table_name' => 'users',
                'columns' => [
                    'id' => 'id',
                    'username' => 'username',
                    'password' => 'password',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'username' => 'VARCHAR(20) NOT NULL',
                    'password' => 'VARCHAR(255) NOT NULL',
                ],
                'constraints' => [
                    'ADD CONSTRAINT UC_Username UNIQUE (id,username)',
                ],
            ],
            'api_keys' => [
                'table_name' => 'api_keys',
                'columns' => [
                    'id' => 'id',
                    'username' => 'username',
                    'api_key' => 'api_key',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'username' => 'VARCHAR(20) NOT NULL',
                    'api_key' => 'VARCHAR(255) NOT NULL',
                ],
                'constraints' => [
                    'ADD CONSTRAINT UC_AK_Username UNIQUE (id,username)',
                ],
            ],
            'roles' => [
                'table_name' => 'roles',
                'columns' => [
                    'id' => 'id',
                    'name' => 'name',
                    'description' => 'description',
                    'is_admin' => 'is_admin'
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'name' => 'VARCHAR(20)',
                    'description' => 'VARCHAR(100)',
                    'is_admin' => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 0',
                ],
            ],
            'resources' => [
                'table_name' => 'resources',
                'columns' => [
                    'id' => 'id',
                    'name' => 'name',
                    'description' => 'description',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'name' => 'VARCHAR(20)',
                    'description' => 'VARCHAR(100)',
                ],
            ],
            'user_role' => [
                'table_name' => 'user_role',
                'columns' => [
                    'id' => 'id',
                    'user_id' => 'user_id',
                    'role_id' => 'role_id',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'user_id' => 'INT(11) UNSIGNED NOT NULL',
                    'role_id' => 'INT(11) UNSIGNED NOT NULL',
                ],
                'constraints' => [
                    'ADD CONSTRAINT fk_urp_u FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'ADD CONSTRAINT fk_urp_r FOREIGN KEY(role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE',
                ],
            ],
            'api_key_role' => [
                'table_name' => 'api_key_role',
                'columns' => [
                    'id' => 'id',
                    'api_key_id' => 'api_key_id',
                    'role_id' => 'role_id',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'api_key_id' => 'INT(11) UNSIGNED NOT NULL',
                    'role_id' => 'INT(11) UNSIGNED NOT NULL',
                ],
                'constraints' => [
                    'ADD CONSTRAINT fk_akr_ak FOREIGN KEY(api_key_id) REFERENCES api_keys(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'ADD CONSTRAINT fk_akr_r FOREIGN KEY(role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE',
                ],
            ],
            'role_res_perm' => [
                'table_name' => 'role_res_perm',
                'columns' => [
                    'id' => 'id',
                    'role_id' => 'role_id',
                    'resource_id' => 'resource_id',
                    'perm_id' => 'perm_id',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'role_id' => 'INT(11) UNSIGNED NOT NULL',
                    'resource_id' => 'INT(11) UNSIGNED NOT NULL',
                    'perm_id' => 'INT(11) UNSIGNED NOT NULL',
                ],
                'constraints' => [
                    'ADD CONSTRAINT fk_rpp_r FOREIGN KEY(role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'ADD CONSTRAINT fk_rpp_res FOREIGN KEY(resource_id) REFERENCES resources(id) ON DELETE CASCADE ON UPDATE CASCADE',
                ],
            ],
            'user_res_perm' => [
                'table_name' => 'user_res_perm',
                'columns' => [
                    'id' => 'id',
                    'user_id' => 'user_id',
                    'resource_id' => 'resource_id',
                    'perm_id' => 'perm_id',
                    'is_allow' => 'is_allow',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'user_id' => 'INT(11) UNSIGNED NOT NULL',
                    'resource_id' => 'INT(11) UNSIGNED NOT NULL',
                    'perm_id' => 'INT(11) UNSIGNED NOT NULL',
                    'is_allow' => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 1',
                ],
                'constraints' => [
                    'ADD CONSTRAINT fk_upp_u FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'ADD CONSTRAINT fk_upp_res FOREIGN KEY(resource_id) REFERENCES resources(id) ON DELETE CASCADE ON UPDATE CASCADE',
                ],
            ],
            'sessions' => [
                'table_name' => 'sessions',
                'columns' => [
                    'id' => 'id',
                    'uuid' => 'uuid',
                    'user_id' => 'user_id',
                    'ip_address' => 'ip_address',
                    'device' => 'device',
                    'browser' => 'browser',
                    'platform' => 'platform',
                    'expire_at' => 'expire_at',
                    'created_at' => 'created_at',
                ],
                'types' => [
                    'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                    'uuid' => 'VARCHAR(36)',
                    'user_id' => 'INT(11) UNSIGNED NOT NULL',
                    'ip_address' => 'VARCHAR(16)',
                    'device' => 'TEXT',
                    'browser' => 'TEXT',
                    'platform' => 'TEXT',
                    'expire_at' => 'INT(11) UNSIGNED',
                    'created_at' => 'INT(11) UNSIGNED',
                ],
            ],
        ],
    ],
];