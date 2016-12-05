<?php
App::uses('PSController', 'Controller');

/**
 * Class CategoriesController
 *
 * Category controller include functions execute get data
 * from contractor category, menu entity
 *
 * @property  ContractorCategory ContractorCategory
 *
 *
 * @since v1.0
 */
class CategoriesController extends PSController {

    public $uses = [
        'ContractorCategory',
        'MenuEntity4shop',
        'MenuEntity'
    ];

    /**
     * @return bool
     *
     * List all categories and Menu belong to category
     */
    public function getList(){

        $data = $this->ContractorCategory->getListCategoryAndMenu();

        $result = $this->ContractorCategory->buildResponses($data);

        return $this->_trueJson($result);
    }
}
