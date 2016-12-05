<?php
App::uses('AppModel', 'Model');

/**
 * Class ContractorCategory
 */
class ContractorCategory extends AppModel {

    /**
     * @return array|null
     *
     *     List all categories and menu
     */
    public function getListCategoryAndMenu(){

        $options = [];

        $options['fields']=[
            'ContractorCategory.id',
            'ContractorCategory.name',
            'MenuEntity4shop.id',
            'MenuEntity4shop.name',
            'MenuEntity4shop.price',
            'MenuEntity4shop.menu_cd',
            'MenuUnit.price AS fixed_price'
        ];

        $options['joins'] = $this->queryJoinModel();

        return $this->find('all',$options);
    }

    /**
     * @return array
     *
     *  query joinModel table:
     *      contractor_category4menu_entities,
     *      menu_entities
     *      menu_entity4shops
     *      menu_units
     */
    public function queryJoinModel(){

        $joins = [
            [
                'table' => 'contractor_category4menu_entities',
                'alias' => 'ContractorCategory4MenuEntity',
                'type' => 'inner',
                'conditions' => [
                    'ContractorCategory.id = ContractorCategory4MenuEntity.contractor_categories_id'
                ]
            ],
            [
                'table' => 'menu_entities',
                'alias' => 'MenuEntity',
                'type' => 'inner',
                'conditions' => [
                    'ContractorCategory4MenuEntity.menu_entities_id = MenuEntity.id'
                ]
            ],
            [
                'table' => 'menu_entity4shops',
                'alias' => 'MenuEntity4shop',
                'type' => 'inner',
                'conditions' => [
                    'MenuEntity.id = MenuEntity4shop.menu_entities_id'
                ]
            ],
            [
                'table' => 'menu_units',
                'alias' => 'MenuUnit',
                'type' => 'inner',
                'conditions' => [
                    'MenuEntity.menu_units_id = MenuUnit.id'
                ]
            ]
        ];

        return $joins;
    }

    /**
     * @param $data
     * @return array
     *
     *      Build response
     */
    public function buildResponses($data){

        $result =[];

        foreach ($data as $category){

            $categoryId = $category['ContractorCategory']['id'];

            if (!isset($result[$categoryId])) {
                $result[$categoryId] = [
                    'category_id' => $category['ContractorCategory']['id'],
                    'category_name' => $category['ContractorCategory']['name'],
                    'menu_entities' => []
                ];
            }

            if (!empty($category['MenuEntity4shop'])) {
                $result[$categoryId]['menu_entities'][] = [
                    'id' => $category['MenuEntity4shop']['id'],
                    'name' => !empty($category['MenuEntity4shop']['name']) ? $category['MenuEntity4shop']['name'] : "",
                    'type_cd' =>  $category['MenuEntity4shop']['menu_cd'],
                    'fixed_price' => isset($category['MenuUnit']['fixed_price']) ? $category['MenuUnit']['fixed_price'] : 0,
                    'price' => $category['MenuEntity4shop']['price'],

                ];
            }

        }

        return $result;
    }

}