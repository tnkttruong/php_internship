<?php
App::uses('AppModel', 'Model');

class Item extends AppModel {

    /**
     * query get list product
     *
     * @param $shopId
     * @return array|null
     *
     */
    public function queryGetListProduct($shopId){

        $options = [];

        $options['fields'] = [
            'Item.id',
            'Item.name',
            'ItemBarcode.barcode'
        ];

        $options['joins'] = $this->queryJoinModel();

        $options['conditions'] = [
          'Shop.id' => $shopId,
        ];

        return $this->find('all', $options);
    }

    /**
     *  query get product detail
     *
     * @param $itemId
     * @return array|null
     *
     */
    public function queryGetProductDetail($itemId){

        $options = [];

        $options['fields'] = [
            'Item.id',
            'Item.name',
            'Item.used_amount_limit',
            'Item.unit_quantity',
            'Item.used_expiry_date',
            'ItemBarcode.barcode',
            'ItemImage.pathname',
            'ItemImage.verification_cd',
            'ItemPurchase.arrival_info_type',
            'ItemPurchase.purchase_date',
            'ItemPurchase.purchase_num',
            'ItemPurchase.cost_price',
            'ItemPurchase.notes',
            'Supplier.name as supplier_name',
            'Staff.staff_name '
        ];

        $options['joins'] =[
            [
                'table' => 'item_barcodes',
                'alias' => 'ItemBarcode',
                'type' => 'inner',
                'conditions' => [
                    'Item.id = ItemBarcode.items_id'
                ]
            ],
            [
                'table' => 'item_images',
                'alias' => 'ItemImage',
                'type' => 'inner',
                'conditions' => [
                    'Item.id = ItemImage.items_id'
                ]
            ],
            [
                'table' => 'item_purchases',
                'alias' => 'ItemPurchase',
                'type' => 'inner',
                'conditions' => [
                    'Item.id = ItemPurchase.items_id'
                ]
            ],
            [
                'table' => 'suppliers',
                'alias' => 'Supplier',
                'type' => 'inner',
                'conditions' => [
                    'Supplier.id = ItemPurchase.suppliers_id'
                ]
            ],
            [
                'table' => 'staffs',
                'alias' => 'Staff',
                'type' => 'inner',
                'conditions' => [
                    'Staff.id = ItemPurchase.staffs_id'
                ]
            ],

        ];

        $options['conditions'] = [
            'Item.id' => $itemId
        ];

        return $this->find('all',$options);

    }

    /**
     * count item condition type A
     *
     * @param $itemId
     * @return array|null
     *
     */
    public function countItemConditionType($itemId) {

        $options = [];

        $options['joins'] = [
            [
                'table' => 'item_details',
                'alias' => 'ItemDetail',
                'type' => 'inner',
                'conditions' => [
                    'Item.id = ItemDetail.items_id'
                ]
            ],
        ];

        $options['conditions'] = [
            'ItemDetail.items_id' => $itemId,
            'ItemDetail.item_condition_type' => 'A'
        ];

        return $this->find('count',$options);
    }

    /**
     * query joins model  contractors, shops, item_details and item_barcodes
     *
     * @return array
     */
    public function queryJoinModel() {

        $joins = [
            [
                'table' => 'contractors',
                'alias' => 'Contractor',
                'type' => 'inner',
                'conditions' => [
                    'Contractor.id = Item.contractors_id'
                ]
            ],
            [
                'table' => 'shops',
                'alias' => 'Shop',
                'type' => 'inner',
                'conditions' => [
                    'Contractor.id = Shop.contractors_id'
                ]
            ],

            [
                'table' => 'item_barcodes',
                'alias' => 'ItemBarcode',
                'type' => 'inner',
                'conditions' => [
                    'Item.id = ItemBarcode.items_id'
                ]
            ],

        ];

        return $joins;
    }

    /**
     *  build response
     *
     * @param $data
     * @return array
     */
    public function buildResponse($data) {

        $result = [];
        $count = 0;
        foreach ($data as $item) {

            $itemId = $item['Item']['id'];

            if(!isset($result[$itemId])) {
                $result[$itemId] = [
                    'id' => $item['Item']['id'],
                    'name' => $item['Item']['name'],
                    'used_amount_limit' => $item['Item']['used_amount_limit'],
                    'unit_quantity' => $item['Item']['unit_quantity'],
                    'used_expiry_date' => $item['Item']['used_expiry_date'],
                    'stock number' => $this->countItemConditionType($item['Item']['id']),
                    'arrival_info_type' => $item['ItemPurchase']['arrival_info_type'],
                    'purchase_date' => $item['ItemPurchase']['purchase_date'],
                    'purchase_num' => $item['ItemPurchase']['purchase_num'],
                    'cost_price' => $item['ItemPurchase']['cost_price'],
                    'notes' => $item['ItemPurchase']['notes'],
                    'supplier_name' => $item['Supplier']['supplier_name'],
                    'staff_name' => $item['Staff']['staff_name'],
                    'image_pathname'=> $item['ItemImage']['pathname'],
                    'image_verification_cd' => $item['ItemImage']['verification_cd'],
                    'barcode' => [],
                ];
            }

            if(!empty($item['ItemBarcode'])) {
                $result[$itemId]['barcode'][] = [
                    'barcode No.'.$count++ => $item['ItemBarcode']['barcode']
                ];
            }
        }

        return $result;
    }

    /**
     * build responses
     *
     * @param array $data
     * @return array
     */
    public function buildResponses($data) {

        $result = [];
        $count = 0;
        foreach ($data as $item) {

            $itemId = $item['Item']['id'];
            if(!isset($result[$itemId])) {
                $result[$itemId] = [
                    'id' => $item['Item']['id'],
                    'name' => $item['Item']['name'],
                    'barcode' => [],
                    'stock number' => $this->countItemConditionType($itemId)
                ];
            }

            if(!empty($item['ItemBarcode'])){
                $result[$itemId]['barcode'][] =[
                    'barcode No.'.$count++ => $item['ItemBarcode']['barcode']
                ];
            }

        }

        return $result;

    }
}