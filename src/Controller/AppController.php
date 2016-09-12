<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;
use \Crud\Controller\ControllerTrait;
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\Console\Shell;
use Acl\Shell\AclExtrasShell;
use Cake\Console\ShellDispatcher;
use Imagick;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public $helpers = ['AkkaCKEditor.CKEditor'];


    /* public function beforeFilter(Event $event)
      {
      Configure::write('debug', false);
      } */
    public $components = [
              //acl code
        'Acl' => [
            'className' => 'Acl.Acl'
        ],
            //end acl code
        'RequestHandler',
        'Crud.Crud' => [
            'actions' => [
                'Crud.Index',
                'Crud.View',
                'Crud.Add',
                'Crud.Edit',
                'Crud.Delete'
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                'Crud.ApiQueryLog'
            ]
        ]
    ];

    public function initialize() {
        parent::initialize();



        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        //following code is used for Acl mutliple user authorization

        $this->loadComponent('Auth', [
            'authorize' => [
                'Acl.Actions' => ['actionPath' => 'controllers/', 'userModel' => $this->getModel()]//Here Dynamically Call the Model name using getModdel method
            ],
           //'authError' => 'You are not authorized to access that location ,wrong.',
           /* 'flash' => [
                'element' => 'acl',
                 'message'=>'You are not authorized to access that location.',
                'key'=>'acl_message'

            ]*/
        ]);

        //End here Acl Code
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event) {
        if (!array_key_exists('_serialize', $this->viewVars) &&
                in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    public function fileUpload($folder_name) {
        $baseUrl = $this->Url->build('/img/league_logo/', true);

        $target_dir = $baseUrl . $folder_name . '/';
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if ($imageFileType == "jpg" && $imageFileType == "png" && $imageFileType == "jpeg" && $imageFileType == "gif") {

            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        }
    }

    /* public function createPdf()
      {
      $pdf=new FPDF();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',16);
      $pdf->Cell(40,10,'Hello World!');
      $pdf->Output();
      } */

    // send email funcion.

    public function sendMail($to = null, $name = null, $subject = null, $message = null, $code = null, $controller = null, $action = null, $name = null, $template = null) {
        $email = new Email('gmail');
        $email->transport('gmail');
        // make object for email send
        $email->viewVars(['code' => $code]);
        $email->viewVars(['controller' => $controller]);
        $email->viewVars(['action' => $action]);
        $email->viewVars(['name' => $name]);
        $email->template($template, $template)
                ->emailFormat('html')
                ->to($to)
                ->subject($subject)
                ->from('ankit.yugasa@gmail.com');

        if ($email->send()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Login method
     * with the help of this function user can login.
     */
    public function Login() {
        $this->viewBuilder()->layout('frontend');

        $user_type = $this->request->session()->read('Auth.User.group_id');
        if ($user_type == 3) {
            return $this->redirect(['controller' => 'Leagues', 'action' => 'dashboard']);
        }

        $this->set('title', 'Kepish | Login');
        if ($this->request->is('post')) {

            $user = $this->Auth->identify();

            if ($user) {
                $this->Auth->setUser($user);

                return $this->redirect(['action' => 'dashboard']);
            }
            $this->Flash->error('Invalid email or password. Please try again.', ['key' => 'custom_error_key',]);
        }
    }

    /**
     * logout method
     *
     * with the help of this function admin can logout
     */
    public function logout() {

        return $this->redirect($this->Auth->logout());
    }

    public function dateFormate($date) {
        return date('d-M-Y', strtotime($date));
    }

    public function updatePassword($model) {

        if ($this->request->is('post')) {   // get form request and convert hashing.
            $currentPassword = (new DefaultPasswordHasher)->hash($this->request->data['current_password']);
            $newPassword = $this->request->data['new_password'];
            $confirmPassword = $this->request->data['confirm_password'];

            //get data from the database
            $data = $this->{$model}->get($this->request->session()->read('Auth.User.id'));

            $passwordDb = $data->password; //display password of user 5 from database
            // verify both password is same or not.
            $verify = (new DefaultPasswordHasher)->check($this->request->data['current_password'], $passwordDb);

            // if request password and database password match.
            if ($verify) {
                // if new password and confirm password match
                if ($newPassword == $confirmPassword) {
                    $hasNewPassword = (new DefaultPasswordHasher)->hash($confirmPassword);

                    $query = $this->{$model}->query();

                    // write inser query

                    if($model == 'Coaches')
                    {
                        $fields = ['password' => $hasNewPassword,'last_password_change'=>date('Y-m-d')];
                    }else{
                         $fields = ['password' => $hasNewPassword];
                    }
                    $query = $query->update()->set($fields)
                            ->where(['id' => $this->request->session()->read('Auth.User.id')])
                            ->execute();
                    $result = $this->loadMOdel($model)->get($this->request->session()->read('Auth.User.id'));
                    if ($model == 'Doctors') {
                        $name = $result['first_name'];
                    }
                    if ($model == 'Leagues') {
                        $name = $result['league_name'];
                    }
                    if ($model == 'Athletes') {
                        $name = $result['first_name'];
                    }
                    if ($model == 'Admins') {
                        $name = $result['first_name'];
                    }
                    if ($model == 'Coaches') {
                        $name = $result['first_name'];
                    }
                    if ($query) {

                        $status = $this->sendMail($result['email'], $name, 'Kepish Account | Password Change Request', "nothing", " ", $model, 'login', $name, 'change_password');
                        if($model == 'Coaches')
                        {   $this->Flash->success('Your password has been changed. Please login', ['key' => 'flash_message']);
                            $this->redirect(['action' => 'logout']);
                        }else{
                            $this->Flash->success('Your password has been changed ', ['key' => 'flash_message']);
                            $this->redirect(['action' => 'changePassword']);
                        }

                    }
                } else {
                    $this->Flash->error('New password and confirm password do not match.', ['key' => 'flash_message']);
                    return $this->redirect(['action' => 'changePassword']);
                }
            } else {
                $this->Flash->error('Current password is incorrect.', ['key' => 'flash_message']);
                return $this->redirect(['action' => 'changePassword']);
            }
        }
    }

    //Acl code start here
//This function give the model name of mutliple user
    public function getModel() {
        if ($this->request->session()->read('Auth.User.group_id')) {
            $result = $this->loadModel('Groups')->getUserType($this->request->session()->read('Auth.User.group_id'));
            return $result->group_name;
        } else {
            return 'Admins';
        }
    }

    public function uploadImage($data) {
        if($data['image'] != NULL)
        {
        $target_dir = 'uploads/' . $data['dir_name'];
        //Change file name with underscore if file name have space between two or more words.
        if (strpos($data['image'], ' ')) {
            $data['image'] = str_replace(' ', '_', $data['image']);
        }

        if (!is_dir($data['dir_name']))
        {
            mkdir($target_dir, 0777);
        }
        //upload file name with team id
         $array = explode('.', $data['image']);
        $fileName = $array[0];
        $fileExt = $array[1];
        if(isset($data['id'])>0)
        {
            $image_path = $fileName.'_'.$data['id'].'.'.$fileExt;
         //end file name with id
        $target_file = $target_dir . basename($image_path);
        }else
        {
            $target_file = $target_dir . basename($data['image']);
        }


        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);



        if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {

            // create list of image
            list($width, $height) = getimagesize($data['tmpfile_name']);


            // resize if necessary
            if ($width >= $data['w'] && $height >= $data['h']) {
                $image = new Imagick($data['tmpfile_name']);

                $image->thumbnailImage($data['w'], $data['h']);
                $image->writeImage($target_file);
            } else {
                move_uploaded_file($data['tmpfile_name'], $target_file);
                return true;
            }
        } else {
            $this->Flash->error('Only jpg,gif,png,jpeg formate supported.', ['key' => 'flash_message']);
            return false;
        }
    }else
    {
       return NULL;
    }
    }

    // this function for remove image.
    public function imageRemove($data) {
        $model = $data['model'];
        $id = $data['id'];
        $filed = $data['field'];
        $dataImg["$filed"] = "";
        $result = $this->loadModel($model)->uploadImage($id, $dataImg);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}
