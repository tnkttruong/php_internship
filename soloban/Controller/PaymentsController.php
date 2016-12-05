<?php
App::uses('PSController', 'Controller');

/**
 * Class PaymentsController
 *
 * @property Payslip Payslip
 * @property  PayslipDetail PayslipDetail
 * @property  PayslipDetail4staff PayslipDetail4staff
 * @property  PaymentMethod PaymentMethod
 *
 *
 * @since v1.0
 */
class PaymentsController extends PSController {

    public $uses = [
        'User',
        'Payslip',
        'PayslipDetail',
        'PayslipDetail4staff',
        'PaymentMethod'
    ];

    /**
     * API-PAYMENT-01
     * List Payment API
     * List payment from DB
     *
     * @internal param array $keywords
     * @internal param string $begin_date
     * @internal param string $end_date
     *
     * @return bool
     */
    public function getList() {

        // Validate
        $validator = $this->getValidator();
        $validator->set($this->request->query);
        $validator->validate = [
            'begin_date' => [
                'rule' => ['date', 'ymd'],
                'message' => __('有効な開始日を入力してください。')
            ],
            'end_date' => [
                'date' => [
                    'rule' => ['date', 'ymd'],
                    'message' => __('有効な終了日を入力してください。')
                ],
                'validThanDate' => [
                    'rule' => ['checkEndDateThanStartDate', !empty($this->request->query['begin_date']) ? $this->request->query['begin_date'] : ""],
                    'message' => __('終了日は開始日より後の日付を入力してください。')
                ]
            ]
        ];

        if (!$validator->validates()) {
            return $this->_falseJson(ApiResponseCode::BAD_REQUEST, null, $validator->validationErrors);
        }

        /** @var int $shopId Get shop id */
        $shopId = $this->User->queryGetShopIdByUserId($this->Auth->user('id'));

        // Get param search
        $params['keywords'] = isset($this->request->query['keywords']) ? explode(" ", $this->request->query['keywords']) : [];
        $params['beginDate'] = isset($this->request->query['begin_date']) ? date('Y-m-d', strtotime($this->request->query['begin_date'])) : null;
        $params['endDate'] = isset($this->request->query['end_date']) ? date('Y-m-d', strtotime($this->request->query['end_date'])) : null;

        $payments = $this->Payslip->getListPayslipByShopId($shopId, $params, ['search']);
        $responses = $this->Payslip->buildResponses($payments, ['search']);

        return $this->_trueJson($responses);

    }

    /**
     * API-PAYMENT-02
     * Get Detail Payment API
     * Get detail payment information
     *
     * @internal param int $id
     *
     * @return bool
     */
    public function getDetail() {
        $validator = $this->getValidator();
        $validator->set($this->request->params);
        $validator->validate = [
            'id' => [
                'notBlank' => [
                    'rule' => 'notBlank',
                    'required' => true,
                    'message' => __('PayslipIDは必須項目です。')
                ],
                'isUnsignedNumber' => [
                    'rule' => ['isUnsignedNumber', 'id'],
                    'message' => __('PayslipIDを入力してください。')
                ]
            ]
        ];

        if (!$validator->validates()) {
            return $this->_falseJson(ApiResponseCode::BAD_REQUEST, null, $validator->validationErrors);
        }

        // Get detail
        $payment = $this->Payslip->getDetail($this->request->param('id'));

        if (empty($payment)) {
            return $this->_falseJson(ApiResponseCode::NOT_FOUND, __('Payslipレコードが見つかりません。'));
        }

        return $this->_trueJson($this->Payslip->buildResponse($payment, ['payment_detail']));
    }

    /**
     * @return bool
     *
     *  API create Payment:
     *      + create payslip
     *      + create payslip detail
     *      + create payment method
     */
    public function create(){

        if(empty($this->data)){
            return false;
        }

        /**
         *  validate
         */
        $validator = $this->getValidator();

        /**
         *  validate payslips
         */
        $validator->set($this->data);

        $validator->validate = [
            'payslip_code'=>[
                'required'=>[
                    'rule' => 'notBlank',
                    'message' => __('Payslip code is required'),
                    'required' => true
                ],
                'size' =>[
                    'rule' => ['lengthBetween', 5, 15],
                    'message' => __('payslip code must be between 5 to 15 characters'),
                    'required' => true
                ],
                'alphanumeric'=>[
                    'rule' => 'alphaNumeric',
                    'message' => __('Payslip code only alphabets and numbers allowed'),
                    'required' => true
                ]
            ],
            'total_price'=>[
                'required'=>[
                    'rule' => 'notBlank',
                    'message' => __('Total price is required'),
                    'required' => true
                ],
                'numeric'=>[
                    'rule' => 'Numeric',
                    'message' => __('Total price only numbers allowed'),
                    'required' => true
                ],
            ],
        ];

        $result= [];

        if (!$validator->validates()) {
            $result =  $validator->validationErrors;
        }
        /**
         *   validate payslip detail
         */

        $detail = $this->data['detail'];
        $index = 0;

        foreach ($detail as $data){

            $validator->set($data);

            $validator->validate = [
                'name' => [
                    'required'=>[
                        'rule' => 'notBlank',
                        'message' => __('Name is required'),
                        'required' => true
                    ],
                    'size' =>[
                        'rule' => ['lengthBetween', 5, 30],
                        'message' => __('Name must be between 5 to 30 characters'),
                        'required' => true
                    ]
                ],
                'fixed_price'=>[
                    'required'=>[
                        'rule' => 'notBlank',
                        'message' => __('Fixed price is required'),
                        'required' => true
                    ],
                    'numeric'=>[
                        'rule' => 'Numeric',
                        'message' => __('Fixed price only numbers allowed'),
                        'required' => true
                    ]
                ],
                'price'=>[
                    'required'=>[
                        'rule' => 'notBlank',
                        'message' => __('Price is required'),
                        'required' => true
                    ],
                    'numeric'=>[
                        'rule' => 'Numeric',
                        'message' => __('Price only numbers allowed'),
                        'required' => true
                    ]
                ],
            ];

            if (!$validator->validates()) {
                $result['detail'][] = ['detail_'.$index=> $validator->validationErrors];
            }

            $index++;

        }

        /**
         *      validate payment method
         */

        $method = $this->data['method'];
        $index = 0;

        foreach ($method as $data){

            $validator->set($data);
            $validator->validate = [
                'payment_type' => [
                    'required'=>[
                        'rule' => 'notBlank',
                        'message' => __('Name is required'),
                        'required' => true
                    ],
                    'size' =>[
                        'rule' => ['lengthBetween', 1, 2],
                        'message' => __('Name must be between 1 to 2 characters'),
                        'required' => true
                    ]
                ],
                'deposit_amount'=>[
                    'required'=>[
                        'rule' => 'notBlank',
                        'message' => __('fixed price is required'),
                        'required' => true
                    ],
                    'numeric'=>[
                        'rule' => 'Numeric',
                        'message' => __('fixed price only numbers allowed'),
                        'required' => true
                    ]
                ]

            ];

            if (!$validator->validates()) {
                $result['method'][]=['method_'.$index => $validator->validationErrors];
            }

            $index++;
        }

        if(!empty($result)){
            return $this->_falseJson(ApiResponseCode::BAD_REQUEST, null, $result);
        }

        /**
         *  create payslip
         */
        $payslip = $this->Payslip->save([
            'shops_id' => $this->data['shops_id'],
            'customers_id' => $this->data['customers_id'],
            'staffs_id' => $this->data['staffs_id'],
            'payslip_code' => $this->data['payslip_code'],
            'total_price' => $this->data['total_price'],
        ]);

        $result=[$payslip];

        /**
         *  create payment method
         */
        $params = $this->data['method'];

        foreach ($params as $method){

            $this->PaymentMethod->create();

            $paymentMethod = $this->PaymentMethod->save([
                'payslips_id' => $payslip['Payslip']['id'],
                'payment_type' => $method['payment_type'],
                'deposit_amount' => $method['deposit_amount'],
            ]);

           array_push($result,$paymentMethod);
        }

        /**
         *  create payslip detail and payslipdetail4staff
         */
        $params = $this->data['detail'];

        foreach ($params as $detail) {

                $this->PayslipDetail->create();

                $payslipDetail = $this->PayslipDetail->save([
                    'payslips_id' => $payslip['Payslip']['id'],
                    'shops_id' =>$detail['shops_id'],
                    'name' => $detail['name'],
                    'fixed_price' => $detail['fixed_price'],
                    'price' => $detail['price'],
                    'menu_entity4shops_id' => $detail['menu_entity4shops_id'],
                    'contractor_categories_id' => $detail['contractor_categories_id']

                ]);

                $payslipDetail4staff = $this->PayslipDetail4staff->save([
                    'payslip_details_id'=> $payslipDetail['PayslipDetail']['id'],
                    'staffs_id' => $detail['staffs_id']
                ]);

                array_push($result,$payslipDetail,$payslipDetail4staff);
        }

        return $this->_trueJson($this->Payslip->buildResponses($result));

    }

}
