<?php
App::uses('ExceptionRenderer', 'Error');

/**
 * Class AppExceptionRenderer
 */
class AppExceptionRenderer extends ExceptionRenderer {

    /**
     * @param Exception $exception
     * @return Controller
     */
    protected function _getController($exception) {
        $controller = parent::_getController($exception);
        $controller->layout = 'error';

        return $controller;
    }
}