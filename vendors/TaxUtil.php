<?php

App::import('Model', 'Tax');
App::import('Model', 'Contractor');

class TaxUtil {
    
    /**
     * 現在税の情報とオーナーに紐ずける税の計算方法(切り下げ、切り上げ、四捨五入)
     * @param string $contractorId
     * @return array $taxInfo
     */
    public static function getTaxInfo($contractorId, $time = null) {
        $taxInfo = array();
        $taxModel = new Tax();
        $contractorModel = new Contractor();
        $time = (empty($time))? date("Y-m-d H:i:s") : $time;
        
        $conditions = array('Tax.start_time <=' => $time
                           ,'Tax.end_time >' => $time);
        $taxData = $taxModel->find('first', array('conditions' => $conditions));

        if ($taxData) {
            $taxRate = $taxData['Tax']['tax_rate'];
            $taxInfo['tax_rate'] = $taxRate;

            $contractorsInfo = $contractorModel->findById($contractorId);
            $taxInfo['tax_rounding_type'] = $contractorsInfo['Contractor']['tax_rounding_type'];
        }

        return $taxInfo;        
    }

}