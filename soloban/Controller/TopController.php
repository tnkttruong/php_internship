<?php
App::uses('PSController', 'Controller');

/**
 * Class TopController
 *
 * @since v1.0
 * @author Duy Ton <duytt@nal.vn>
 */
class TopController extends PSController {

    /**
     * Before filter
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(['index']);
    }

    /**
     * Ping pong response
     */
    public function index() {
        echo 'PONG';
    }

}
