<?php
App::uses('PSController', 'Controller');

/**
 * Class ItemsController
 *
 * @property Item Item
 */
class ItemsController extends PSController {

    public $uses = [
        'User',
        'Shop',
        'Contractor',
        'Item',
        'ItemDetail',
        'ItemBarcode',
    ];

    /**
     * API get list product
     *
     * @return bool
     */
    public function getList(){

        $shopId = $this->User->queryGetShopIdByUserId($this->Auth->user()['id']);

        $items = $this->Item->queryGetListProduct($shopId);

        return $this->_trueJson($this->Item->buildResponses($items));
    }


    /**
     *  API get product detail
     *
     * @return bool
     */
    public function getDetail(){

        $itemId = $this->request->query['id'];

        $item = $this->Item->queryGetProductDetail($itemId);

        return $this->_trueJson($this->Item->buildResponse($item));
    }

}