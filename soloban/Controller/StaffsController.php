<?php
App::uses('PSController', 'Controller');

/**
 * Class StaffsController
 *
 * @property Staff Staff
 */
class StaffsController extends PSController {

     public $uses = [
        'Staff',
        'StaffWork',
        'ReservedTable'
     ];

    /**
     * @return false|string
     *
     *  today
     */
     public function date(){
         $now = date('Y-m-d');
         return $now;
     }

    /**
     * @return bool
     *
     * List all staffs working on the day
     */
     public function getList(){

         $date = $this->date();

         $data = $this->Staff->queryStaffsWorkOnTheDay($date);

        return $this->_trueJson($this->Staff->buildResponses($data));

     }

    /**
     * password authentication
     *
     * @return bool
     *
     */
    public function passwordAuthentication(){

        $validator = $this->getValidator();
        $validator->set($this->request->data);

        /**
         *  validate
         */
        $validator->validate = [
            'password' => [
                'required'=>[
                    'rule' => 'notBlank',
                    'message' => __('Password is required'),
                    'required' => true
                ],
                'sizeMin' => [
                    'rule' => array('minLength', '4'),
                    'message' => __('Password must be minimum 4 characters'),
                    'required' => true
                ],
                'sizeMax' => [
                    'rule' => array('maxLength', '4'),
                    'message' => __('Password must be maximum 4 characters'),
                    'required' => true
                ],
                'numeric' =>[
                    'rule' => 'Numeric',
                    'message' => __('Password should be numeric'),
                    'required' => true
                ]
            ]
        ];

        if (!$validator->validates()) {
            return $this->_falseJson(ApiResponseCode::BAD_REQUEST, null, $validator->validationErrors);
        }

        $result = $this->Staff->checkStaffsPasswordExist($this->request->data);

        return $this->_trueJson(
            ['flag' => $result ]);
    }

}
