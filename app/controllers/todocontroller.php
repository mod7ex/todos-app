<?php


namespace TODOS\CONTROLLERS;


use TODOS\LIB\Filter;
use TODOS\MODELS\TodoModel;

class TodoController extends AbstractController
{
    use Filter;

    public function defaultAction()
    {
        if ($this->_session->isValidFingerPrint() && isset($this->_session->user_id)){
            $this->_view();
        }
    }

    public function listAction()
    {
        if ($this->_session->isValidFingerPrint() && isset($this->_session->user_id)){
            $todos = TodoModel::getAll($this->_session->user_id);
            if ($todos){
                header("Content-Type: application/json");
                echo json_encode($todos);
            }
        }
    }

    public function saveAction()
    {
        if ($this->_session->isValidFingerPrint() && isset($this->_session->user_id)){
            $todoStObj = json_decode(file_get_contents('PHP://input'));
            $todo = new TodoModel($this->_session->user_id, $this->filter_str($todoStObj->td_title), $this->filter_str($todoStObj->td_content));

            if ($todoStObj->id && $this->filter_int($todoStObj->id)){
                $todo->id = $todoStObj->id;
            }

            if ($todo->save()){
                /* echo json_encode($todo) # this alone will return {} because properties are private so we use jsonSerialize */
                echo json_encode($todo);
            }else{
                echo json_encode(new \stdClass());
            }
        }
    }


    public function deleteAction()
    {
        if ($this->_session->isValidFingerPrint() && isset($this->_session->user_id)){
            if ($this->filter_int($this->_params[0])){
                $id = $this->_params[0];
                if ($id>0){
                    $todo = TodoModel::getByPk($id);
                    if ($todo){
                        if ($todo->user_id === $this->_session->user_id){
                            if ($todo->delete()){
                                echo 'deleted';
                            }
                        }
                    }
                }
            }
        }
    }
}