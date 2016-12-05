<?php

App::uses('ConnectionManager', 'Model');

class TransactionComponent extends Component
{
    public $database;

    public function startup(Controller $controller)
    {
        parent::startup($controller);
        $this->database = ConnectionManager::getDataSource('default');
    }

    public function begin()
    {
        $this->database->begin();
    }

    public function commit()
    {
        $this->database->commit();
    }

    public function rollback()
    {
        $this->database->rollback();
    }
}
