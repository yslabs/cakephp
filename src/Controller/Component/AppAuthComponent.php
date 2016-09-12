<?php 
// import  Component  for access  parent file  function.
App::import('Component', 'AuthComponent');
class AppAuthComponent extends AuthComponent {
    // this function help to  login with diffrent user 
    function identify($user = null, $conditions = null) {
        $models = array('User', 'Admin');
        foreach ($models as $model) {
            $this->userModel = $model; // switch model
            $result = parent::identify($user, $conditions); // let cake do it's thing
            if ($result) {
                // login success
                return $result; 
            }
        }
        // login failure
        return null; 
    }
}