<?php


namespace TODOS\CONTROLLERS;

use TODOS\LIB\Filter;
use TODOS\LIB\UploadHandler;
use TODOS\MODELS\UserModel;

class UserController extends AbstractController
{
    use Filter;

    public function defaultAction()
    {
        if ($this->_session->isValidFingerPrint() && isset($this->_session->user_id)){
            $this->_view();
        }
    }

    public function authAction()
    {
        $this->_view();
    }

    public function loginAction()
    {
        if (isset($this->_params[0])){
            if ($this->_params[0] === 'template'){
                $this->_view();
            }elseif ($this->_params[0] === 'check'){
                $data =  json_decode(file_get_contents('PHP://input'));
                # Filtering Data.
                $feedback = new \stdClass();
                if ($this->filter_email($data->email) && $this->filter_passwd($data->password)){
                    $user = UserModel::checkLogIn($data->email, $data->password);
                    if ($user){
                        $this->_session->generateFingerPrint();
                        $this->_session->user_id = $user->id;
                        $feedback->loggedIn = true;
                    }else{
                        $feedback->loggedIn = false;
                        $feedback->error = 'Incorrect Credentials';
                    }
                }else{
                    $feedback->loggedIn = false;
                    $feedback->error = 'Invalid Credentials';
                }
                echo json_encode($feedback);
            }
        }
    }

    public function logoutAction()
    {
        $this->_session->kill();
        \Header('Location: /user/auth');
    }

    public function signupAction()
    {
        if (isset($this->_params[0])){
            if ($this->_params[0] === 'template'){
                $this->_view();
            }elseif ($this->_params[0] === 'check'){
                $data =  json_decode(file_get_contents('PHP://input'));
                # filter data.
                $feedback = new \stdClass();
                if ($this->filter_fullName($data->full_name) && $this->filter_email($data->email) && $this->filter_passwd($data->password)){
                    if ($data->password === $data->password_again){
                        $user = new UserModel($data->full_name, $data->email, hash('sha256', $data->password, false));
                        if ($user->save()){
                            $this->_session->generateFingerPrint();
                            $this->_session->user_id = $user->id;
                            $feedback->loggedIn = true;
                        }else{
                            $feedback->loggedIn = false;
                            $feedback->error = 'Some Thing Went Wrong';
                        }
                    }else{
                        $feedback->loggedIn = false;
                        $feedback->error = 'Renter The Same Password';
                    }
                }else{
                    $feedback->loggedIn = false;
                    $feedback->error = 'Use Valid Informations';
                }
                echo json_encode($feedback);
            }
        }
    }

    public function editAction()
    {
        if ($this->_session->isValidFingerPrint() && isset($this->_session->user_id)){
            $user = UserModel::getByPk($this->_session->user_id);
            if (isset($this->_params[0])){
                if ($this->_params[0] === 'profileImg'){
                    $feedback = new \stdClass();
                    # Edit The Profile Image.
                    if (isset($_FILES['profileImg'])){
                        if (UploadHandler::check_file_ext($_FILES['profileImg'])){
                            if (UploadHandler::check_type($_FILES['profileImg'])){
                                if (UploadHandler::check_size($_FILES['profileImg'])){
                                    /*if (UploadHandler::check_if_file_exists($target_file)){if (unlink($target_file)){}}*/
                                    /*$target_file = USERS_PROFILES_IMAGES . basename($_FILES["profileImg"]["name"]);
                                    $target_file = pathinfo($target_file, PATHINFO_DIRNAME) . DS . time() . '_' . $this->_session->user_id . '.' . pathinfo($target_file, PATHINFO_EXTENSION);*/
                                    $target_file = DS . 'usersProfiles' . DS . preg_replace('/.*[.]/', time() . '_' . $this->_session->user_id . '.', basename($_FILES["profileImg"]["name"]));
                                    $user->profile_img_url = $target_file;
                                    if ($user->save()){
                                        if (UploadHandler::save_file($_FILES['profileImg'], PUBLIC_PATH . $target_file)){
                                            $feedback->profileUrlchanged = true;
                                            $feedback->profileUrl = $target_file;
                                        }else{
                                            $feedback->profileUrlchanged = false;
                                            $feedback->error = 'Some Thing Went Wrong here';
                                        }
                                    }else{
                                        $feedback->profileUrlchanged = false;
                                        $feedback->error = 'Some Thing Went Wrong';
                                    }
                                }else{
                                    $feedback->profileUrlchanged = false;
                                    $feedback->error = 'The Image Size Is So Big';
                                }
                            }else{
                                $feedback->profileUrlchanged = false;
                                $feedback->error = 'The Uploaded File Is Not An Image';
                            }
                        }else{
                            $feedback->profileUrlchanged = false;
                            $feedback->error = 'The Uploaded File Is Not An Image';
                        }
                    }
                    echo json_encode($feedback);
                }elseif ($this->_params[0] === 'txtData'){
                    $feedback = new \stdClass();
                    $data =  json_decode(file_get_contents('PHP://input'));

                    # Edit The full Name.
                    if ($this->filter_fullName($data->full_name) && $user->full_name != $data->full_name){
                        $user->full_name = $data->full_name;
                        $feedback->full_name = 'Your Full Name Has Been Changed';
                    }

                    # Edit The Password.
                    if ($this->filter_passwd($data->oldpassword) && $user->passwd_hash === hash('sha256', $data->oldpassword, false)){
                        # here you should filter data
                        if ($this->filter_passwd($data->newpassword) && $data->newpassword === $data->repnewpassword){
                            $user->passwd_hash = hash('sha256', $data->newpassword, false);
                            $feedback->password = 'Your password has been updated';
                        }else{
                            $feedback->password = 'password should contain capital and small letters and numbers';
                        }
                    }

                    if ($user->save()){
                        $feedback->ok = true;
                    }else{
                        $feedback->ok = false;
                    }
                    echo json_encode($feedback);
                }
            }
        }
    }

    public function deleteAction()
    {
        if ($this->_session->isValidFingerPrint() && isset($this->_session->user_id)){
            $user = UserModel::getByPk($this->_session->user_id);
            $feedBack = new \stdClass();
            if ($user->delete()){
                $this->_session->kill();
                $feedBack->accDel = true;
            }else{
                $feedBack->accDel = false;
            }
            echo json_encode($feedBack);
        }
    }
}