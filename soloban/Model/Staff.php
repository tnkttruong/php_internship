<?php
App::uses('AppModel', 'Model');

/**
 * Staff Model
 *
 */
class Staff extends AppModel {

    /**
     * @param $date
     *
     *  query list staffs working on the day
     */

    public function queryStaffsWorkOnTheDay($date){

        $options = [];

        $options['fields'] = [
            'Staff.id',
            'Staff.staff_name',
            'ReservedTable.reserved_table_date'
        ];

        $options['conditions'] = [
            'ReservedTable.reserved_table_date' => $date
        ];

        $options['joins'] = $this->queryJoinModel();

        return $this->find('all',$options);
    }

    /**
     * check password exist staff
     *
     * @param array $check
     * @return bool
     *
     */
    public function checkStaffsPasswordExist($check) {

        $simplePassword = new SimplePasswordHasher();

        $conditions = [
            'Staff.id'=> $check['id'],
            'Staff.del_flg'=>0,
            'Staff.pin' => $simplePassword->hash($check['password']),
        ];

        $result =  $this->find('first', ['conditions' => $conditions]);

        return empty($result) ? false : true;
    }

    /**
     * @return array
     *
     *  query JoinModel table:
     *      + staffs
     *      + staff_work
     *      + reserved_tables
     *
     */
    public function queryJoinModel(){

            $joins=[
                [   'table' => 'staff_works',
                    'alias' => 'StaffWork',
                    'type' => 'inner',
                    'conditions' => 'Staff.id = StaffWork.staffs_id'
                ],
                [   'table' => 'reserved_tables',
                    'alias' => 'ReservedTable',
                    'type' => 'inner',
                    'conditions' => 'ReservedTable.id = StaffWork.reserved_tables_id'
                ]
            ];

            return $joins;
    }

    /**
     * @param $data
     * @return array
     *
     *  buildResponses
     */
    public function buildResponses($data){

        $result = [];

        foreach ($data as $staff){
            $staffId = $staff['Staff']['id'];
            $result[$staffId]=[
                'id' => $staff['Staff']['id'],
                'name' => $staff['Staff']['staff_name'],
                'date' => $staff['ReservedTable']['reserved_table_date']
            ];
        }

        return $result;
    }
}
