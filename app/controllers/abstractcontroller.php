<?php

namespace TODOS\CONTROLLERS;

use TODOS\MODELS\UserModel;

class AbstractController
{
    protected $_session;

    protected $_controller;
    protected $_action;
    protected $_params;

    protected $_data = array();

    protected function notfoundAction()
    {
        $this->_view();
    }

    protected function _view()
    {
        if (file_exists(VIEWS_PATH . $this->_controller . DS . $this->_action . '.view.php')){
            $user = UserModel::getByPk($this->_session->user_id);
            $this->_data['user'] = $user;
            extract($this->_data);
            require_once VIEWS_PATH . $this->_controller . DS . $this->_action . '.view.php';
        }else{
            require_once VIEWS_PATH . 'notfound' . DS . 'no_view.view.php';
        }
    }
}