<?php
App::uses('AppModel', 'Model');

/**
 * Class Payslip
 *
 * @property ContractorPaymentMethod ContractorPaymentMethod
 */
class Payslip extends AppModel {

    /**
     * query get payment date and staff name
     *
     *
     * @param $shopId
     * @param $payslipId
     * @return array|null
     */
    public function queryGetPaymentDateAndStaffName($shopId,$payslipId){

        $options = [];
        $options['fields'] = [
            'Shop.shop_name',
            'Shop.specific_code',
            'Payslip.payment_date',
            'Payslip.payslip_code',
            'Staff.staff_name'
        ];
        $options['conditions'] = [
            'Payslip.shops_id' => $shopId,
            'Payslip.del_flg' => 0,
            'Payslip.id' => $payslipId
        ];
        $options['joins'] = $this->queryJoinModel();

        return $this->find('first',$options);
    }

    /**
     * Joins model shops, payslips and staff
     *
     * @return array
     *
     */
    public function queryJoinModel(){

        $joins =[
            [
                'table' => 'shops',
                'alias' => 'Shop',
                'type' => 'inner',
                'conditions' => [
                        'Shop.id = Payslip.shops_id'
                ]
            ],
            [
                'table' => 'staffs',
                'alias' => 'Staff',
                'type' => 'inner',
                'conditions' => [
                    'Staff.id = Payslip.staffs_id'
                ]
            ],
        ];
        return $joins;
    }

    /**
     * @param $data
     * @return array
     *
     *  build Responses
     */
    public function buildResponses($data){

        $result = [];

        foreach ($data as $payment) {

            if(!isset($result['Payslip'])) {
                $result['Payslip'] = [
                    'id' => $payment['Payslip']['id'],
                    'payslip_code' => $payment['Payslip']['payslip_code'],
                    'total_price' => $payment['Payslip']['total_price'],
                    'payslip_details' => [],
                    'payment_method' => []
                ];
            }

            if (!empty($payment['PayslipDetail'])) {
                $result['Payslip']['payslip_details'][] = [
                    'id' => $payment['PayslipDetail']['id'],
                    'name' => $payment['PayslipDetail']['name'],
                    'fixed_price' => $payment['PayslipDetail']['fixed_price'],
                    'price' => $payment['PayslipDetail']['price'],
                ];
            }

            if(!empty($payment['PayslipDetail4staff'])){
                $result['Payslip']['payslip_details'][]=[
                    'staffs_id' => $payment['PayslipDetail4staff']['staffs_id'],
                ];
            }

            if(!empty($payment['PaymentMethod'])){
                $result['Payslip']['payment_method'][]=[
                    'id' => $payment['PaymentMethod']['id'],
                    'payment_type' => $payment['PaymentMethod']['payment_type'],
                    'deposit_amount' => $payment['PaymentMethod']['deposit_amount']

                ];
            }

        }

        return $result;
    }
}
