<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * Class User
 */
class User extends AppModel {

    public $hasAndBelongsToMany = [
        'Shop' => [
            'className' => 'Shop',
            'joinTable' => 'pos_user4shops',
            'foreignKey' => 'users_id',
            'associationForeignKey' => 'shops_id',
            'conditions' => [
                'Shop.del_flg' => 0,
                'PosUser4shop.del_flg' => 0
            ],
            'order' => ['Shop.shop_name ASC']
        ],
        'Raspi' => [
            'className' => 'Raspi',
            'joinTable' => 'user_available_raspis',
            'foreignKey' => 'users_id',
            'associationForeignKey' => 'raspis_id',
            'conditions' => [
                'Raspi.del_flg' => 0,
                'UserAvailableRaspi.del_flg' => 0
            ],
            'order' => ['Raspi.order_number ASC']
        ]
    ];

    /**
     * Check exist user id
     *
     * @param array $check
     * @internal param int $user_id
     *
     * @return bool
     */
    public function checkUserIdExist($check) {
        $conditions = [
            'User.user_id' => $check['user_id'],
            'User.del_flg' => 0
        ];
        $result =  $this->find('first', ['conditions' => $conditions]);

        return empty($result) ? false : true;
    }

    /**
     * Check exist password
     *
     * @param array $check
     * @internal param string $password
     *
     * @return bool
     */
    public function checkUserPasswordExist($check) {
        $simplePassword = new SimplePasswordHasher();
        $conditions = array('User.password' => $simplePassword->hash($check['password']));
        $result =  $this->find('first', ['conditions' => $conditions]);

        return empty($result) ? false : true;
    }

    /**
     * Get shop id by user id
     *
     * @param int $userId
     *
     * @return null
     */
    public function queryGetShopIdByUserId($userId) {
        $result = null;

        if (empty($userId)) {
            return null;
        }

        $conditions = ['User.id' => $userId];
        $response =  $this->find('first', array('conditions' => $conditions));

        if (empty($response)) {
            return null;
        }

        foreach ($response['Shop'] as $v) {
            $result = $v['id'];
        }
        return $result;
    }

}
