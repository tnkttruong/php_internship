<?php
App::uses('PSController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Class EmailsController
 *
 * @property  User User
 * @property  Payslip Payslip
 */

class EmailsController extends PSController   {

    public $uses = [
        'User',
        'Payslip',
        'Shop',
        'Staff'
    ];


    /**
     * Send mail
     *
     * @return bool
     *
     */

    public function email(){
        if(empty($this->request->data)){
            return false;
        }
        /**
         *  Validate
         */
        $validator = $this->getValidator();

        $validator->set($this->request->data);
        $validator->validate = [
               'to' => [
                     'required'=>[
                          'rule' => 'notBlank',
                          'message' => __('to address is required'),
                          'required' => true
                     ],
                     'email'=>[
                          'rule' => 'email',
                          'message' => __('Please supply a valid email address'),
                          'required' => true
                     ],
               ],
               'subject' => [
                     'required'=>[
                           'rule' => 'notBlank',
                           'message' => __('subject is required'),
                           'required' => true
                     ],
               ],
        ];

        if (!$validator->validates()) {
            return $this->_falseJson(ApiResponseCode::BAD_REQUEST, null, $validator->validationErrors);
        }

        $user =  $this->Auth->user();
        $shopId = $this->User->queryGetShopIdByUserId($user['id']);
        $payslipId = $this->request->data['payslip_id'];

        $detail = $this->Payslip->queryGetPaymentDateAndStaffName($shopId,$payslipId);

        $contents = [
            'shop_name' => $detail['Shop']['shop_name'],
            'specific_code' => $detail['Shop']['specific_code'],
            'payment_date'=> $detail['Payslip']['payment_date'],
            'payslip_code' => $detail['Payslip']['payslip_code'],
            'staff_name' => $detail['Staff']['staff_name']
        ];

        $to = $this->request->data['to'];
        $subject = $this->request->data['subject'];
        $email = new CakeEmail('gmail');
        $email  -> from(['thanhdongqn94@gmail.com' => 'Ho Thanh Dong'])
                -> to($to)
                -> subject($subject)
                -> emailFormat('html')
                -> template('contactForm')
                -> viewVars($contents);

        if($email->send()){
            return $this->_trueJson([
                'from' => $email->from(),
                'to' => $email->to(),
                'subject' => $email->subject(),
                'contents' => $email->viewVars()
            ]);
        }
    }
}