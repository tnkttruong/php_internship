<?php

/**
 * Base Validator class
 *
 */
class Validator extends Model {
    public $useTable = false;
    public $validate = [];

    /**
     * Check staff is valid
     *
     * @param array $check
     * @param string $checkKey
     *
     * @return bool
     */
    public function checkStaffIsValid($check, $checkKey = 'staff_id') {

        $staffModel = ClassRegistry::init('Staff');

        $staff = $staffModel->find('first', [
            'conditions' => [
                'id'=> $check[$checkKey],
                'del_flg' => 0
            ]
        ]);

        return !empty($staff);
    }

    /**
     * Check customer is valid
     *
     * @param array $check
     * @param string $checkKey
     *
     * @return bool
     */
    public function checkCustomerIsValid($check, $checkKey = 'customer_pay_id') {

        $customerModel = ClassRegistry::init('Customer');

        $customer = $customerModel->getCustomerById($check[$checkKey]);

        return !empty($customer);
    }

    /**
     * Check Contractor category is valid
     *
     * @param array $check
     *
     * @return bool
     */
    public function checkCategoryIsValid($check) {
        $categoryModel = ClassRegistry::init('ContractorCategory');

        $category = $categoryModel->find('first', [
            'conditions' => [
                'id' => $check['category_id'],
                'del_flg' => 0
            ]
        ]);
        return !empty($category);
    }

    /**
     * Check payment method is valid
     *
     * @param array $check
     *
     * @return bool
     */
    public function checkPaymentMethodIsValid($check)
    {
        $contractorPaymentMethodModel = ClassRegistry::init('ContractorPaymentMethod');

        $contractorPaymentMethod = $contractorPaymentMethodModel->getContractorPaymentMethodById($check['id']);

        return !empty($contractorPaymentMethod);
    }

    /**
     * Check payment method type is valid
     *
     * @param array $check
     *
     * @return bool
     */
    public function checkPaymentTypeIsValid($check) {
        $paymentMethodTypes = Configure::read('PaymentMethodType');

        return in_array($check['type'], $paymentMethodTypes);
    }

    /**
     * Check input value is unsigned integer number
     *
     * @param array $check
     * @param string $checkKey
     * 
     * @return bool
     */
    public function isUnsignedNumber($check, $checkKey) {
        if (preg_match("/^[0-9]*$/",$check[$checkKey])) {
            return true;
        }

        return false;
    }

    /**
     * Check input value is unsigned and negative integer number
     *
     * @param array $check
     * @param string $checkKey
     *
     * @return bool
     */
    public function isValidNumber($check, $checkKey) {
        if (preg_match("/^-?[0-9]*$/",$check[$checkKey])) {
            return true;
        }

        return false;
    }

    /**
     * Reset validation error before validate
     *
     * @param array $options
     *
     * @return bool
     */
    public function beforeValidate($options = []) {
        // Reset validation errors
        $this->validationErrors = [];

        return parent::beforeValidate($options);
    }

    /**
     * Check input value is datetime format
     *
     * @param array $check
     * @internal param string $operation_start_time
     *
     * @return bool
     */
    function isDatetimeFormat($check) {

        if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) (?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/",$check['operation_start_time']) ||
            preg_match("/^(\d{4})-(\d{2})-(\d{2})$/",$check['operation_start_time']) ||
            preg_match("/^(\d{4})\/(\d{2})\/(\d{2}) (?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/",$check['operation_start_time']) ||
            preg_match("/^(\d{4})\/(\d{2})\/(\d{2})$/",$check['operation_start_time'])
        ) {
            $date = str_replace('/', '-', $check['operation_start_time']);
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $date);

            return $d && $d->format('Y-m-d H:i:s') == $date;
        } else {
            return false;
        }
    }

    /**
     * Check shop is valid
     *
     * @param array $check
     * @internal param int $id
     *
     * @return bool
     */
    public function checkShopIsValid($check) {
        $shopModel = ClassRegistry::init('Shop');

        $shop = $shopModel->getShopInfoById($check['id']);

        return !empty($shop);
    }

    /**
     * Check is time format
     *
     * @param array $check
     * @param string $checkKey
     * @internal param string $settlement_time
     *
     * @return bool
     */
    public function isTimeFormat($check, $checkKey) {
        if (preg_match("/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/",$check[$checkKey])) {
            return true;
        }

        return false;
    }

    /**
     * Check settlement cd is valid
     *
     * @param array $check
     *
     * @return bool
     */
    public function checkSettlementCdIsValid($check) {
        $settlementCds = Configure::read('SettlementCdArray');

        return in_array($check['settlement_cd'], $settlementCds);
    }

    /**
     * Check settlement type is valid
     *
     * @param array $check
     *
     * @return bool
     */
    public function checkSettlementTypeIsValid($check) {
        $settlementTypes = array_keys(Configure::read('SettlementType'));

        return in_array($check['settlement_type'], $settlementTypes);
    }

    /**
     * Check end date must be than start date
     *
     * @param array $check
     * @param string $beginDate
     *
     * @return bool
     */
    public function checkEndDateThanStartDate($check, $beginDate) {
        if (strtotime($check['end_date']) >= strtotime($beginDate)) {
            return true;
        }

        return false;
    }

    /**
     * Check deposit/withdraw/settlement/inspection record is valid
     *
     * @param array $check
     *
     * @return bool
     */
    public function checkDepositIsValid($check) {

        $stockMoneyLogModel = ClassRegistry::init('StockMoneyLog');

        $stock = $stockMoneyLogModel->getDepositDetailById($check['id']);

        return !empty($stock);
    }

}
