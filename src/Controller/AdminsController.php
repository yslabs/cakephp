<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Cake\Controller\Component\CookieComponent;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Datasource\ConnectionManager;

//use  Cake\Controller\Component;

/**
 * Admins Controller
 *
 * @property \App\Model\Table\AdminsTable $Admins
 */
class AdminsController extends AppController {
    // public function __Construct()
    // {
    //     //$this->isAuthorized('1');
    // }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */

    /**
     * initialize method
     *
     * with the help of this function we initialize controller, action, from, database table
     */
    public function initialize() {
        parent::initialize();
        //Acl code start here for login authorization
        $this->Auth->config('authenticate', [
            'Form' => [
                'userModel' => 'Admins',
                'fields' => ['username' => 'email', 'password' => 'password']
            ],
        ]);

        $this->Auth->config('loginAction', [
            'controller' => 'Admins',
            'action' => 'login'
        ]);
        $this->Auth->config('loginRedirect', [
            'controller' => 'Admins',
            'action' => 'dashboard'
        ]);

        if ($this->request->session()->read('Auth.User.group_id') != 1) {

            if (!$this->request->is('ajax')) {
                $this->Auth->logout();
            }
        }
         $this->Auth->allow(['login','logout','forgotPassword']);

        //End Here Acl code

        $this->loadComponent('Cookie', ['expiry' => '1 day']);
    }

    public function index() {
        $this->set('title', 'Kepish | Admin');
        $admins = $this->paginate($this->Admins);
        $this->set(compact('admins'));
        $this->set('_serialize', ['admins']);
    }

    /**
     * View method
     *
     * @param string|null $id Admin id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {

        $admin = $this->Admins->get($id, [
            'contain' => []
        ]);

        $this->set('admin', $admin);
        $this->set('_serialize', ['admin']);
    }

    /**
     * dashboard  method
     *
     * select data and pass data on dashboard view page
     */
    public function dashboard() {
        $this->set('title', 'Kepish | Admin | Dashboard');
        //get count of every section
        $data['events'] = $this->loadModel('Events')->count();
        $data['doctors'] = $this->loadModel('Doctors')->count();
        $data['athletes'] = $this->loadModel('Athletes')->count();
        $data['leagues'] = $this->loadModel('Leagues')->count();
        $data['teams'] = $this->loadModel('Teams')->count();
        $data['subscriptionPackages'] = $this->loadModel('subscriptionPackages')->count();
        $this->set($data);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->set('title', 'Kepish | Admin Panel | Add ');
        $admin = $this->Admins->newEntity();
        if ($this->request->is('post')) {
            $admin = $this->Admins->patchEntity($admin, $this->request->data);
            if ($this->Admins->save($admin)) {
                $this->Flash->success('The admin has been saved.', ['key' => 'flash_message']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The admin could not be saved. Please, try again.', ['key' => 'flash_message']);
            }
        }
        $this->set(compact('admin'));
        $this->set('_serialize', ['admin']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Admin id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $this->set('title', 'Kepish | Admin Panel | Admin | Edit Profile');
        $admin = $this->Admins->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $admin = $this->Admins->patchEntity($admin, $this->request->data);
            if ($this->Admins->save($admin)) {
                $this->Flash->success('Your profile has been updated.', ['key' => 'flash_message',]);
                return $this->redirect(['action' => 'profile', $id]);
            } else {
                $this->Flash->error('Your profile could not be update.Please try again.', ['key' => 'flash_message']);
            }
        }
        $this->set(compact('admin'));
        $this->set('_serialize', ['admin']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Admin id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function profile($id = null) {
        $this->set('title', 'Kepish | Admin Panel | Profile');
        $query = $this->Admins->find()
                ->where(['Admins.id ' => $id]);
        $this->set('admin', $query);
    }

    /**
     * Delete method
     *
     * @param string|null $id Admin id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $admin = $this->Admins->get($id);
        if ($this->Admins->delete($admin)) {
            $this->Flash->success('The admin has been deleted.', ['key' => 'flash_message']);
        } else {
            $this->Flash->error('The admin could not be deleted. Please, try again.', ['key' => 'flash_message']);
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Authorized method
     *
     * @param string|null $user.
     * make function for authorize ( if login then access this function whis is define in array like index , add, edit)
     */
    public function isAuthorized($user) {

        $action = $this->request->params['action'];
        // The add and index actions are always allowed.
        if (in_array($action, ['index', 'add', 'edit'])) {

            return true;
        }
        // All other actions require an id.
        if (empty($this->request->params['pass'][0])) {
            return false;
        }
        $id = $this->request->params['pass'][0];
        $admin = $this->Admins->get($id);
        if ($admin->user_id == $user['id']) {
            return true;
        }
        return parent::isAuthorized($user);
    }

    public function under() {
        $this->render('/underconstruction/cunst');
    }

    /**
     * Login method
     *
     *
     * make function for admin login
     */
    public function login() {
        //print_r($this->Cookie->read('remember_me'));die;

        $this->set('title', 'Kepish | Administrator Login');
        if ($this->request->session()->read('Auth.User.id')) {
            return $this->redirect($this->Auth->redirectUrl());
        }
        // get user type.
        $user_type = $this->request->session()->read('Auth.User.group_id');
        // check if login or not.
        if ($user_type == 1) {
            return $this->redirect(['controller' => 'Admins', 'action' => 'dashboard']);
        }

        if ($this->request->is('post')) {
            // if remember me check by user

            $user = $this->Auth->identify();

            if ($user) {
                $this->Auth->setUser($user);

                return $this->redirect(['action' => 'dashboard']);
            }
            // $this->Flash->error('Your username or password is incorrect.');
            $this->Flash->error('Invalid email or password. Please try again.', ['key' => 'flash_message',]);
        }



        //$user = $this->Auth->identify();
        // print_r($this->Cookie->read('remember_me'));die;
        /* if ($user) {
          $this->Auth->setUser($user);


          if ($this->request->data['remember_me'] == "1") {

          $cookie = array();
          $hasher = new DefaultPasswordHasher();
          $cookie['username'] = $this->request->data['email'];
          $cookie['password'] = $this->request->data['password'];
          $this->Cookie->write('remember_me', $cookie, true, "1 week");
          unset($this->request->data['remember_me']);
          }

          if (empty($this->data)) {
          $cookie = $this->Cookie->read('remember_me');
          if (!is_null($cookie)) {
          $user = $this->Auth->identify();
          if ($user) {
          $this->redirect($this->Auth->redirectUrl());
          } else {
          $this->Cookie->destroy('remember_me'); # delete invalid cookie

          $this->Session->setFlash('Invalid cookie');
          $this->redirect('login');
          }
          }
          }
          return $this->redirect($this->Auth->redirectUrl());
          } */
        //$this->Flash->set('Invalid username or password, try again', ['element' => 'error']);
    }

    public function hello() {
        echo "Hello";
    }

    /**
     * forgotPassword method
     *
     *
     * make function for forgotPassword
     */
    // make verifylink function for forgot password varify link
    public function changePassword($id = null) {   // get all entity of admin
        $this->set('title', 'Kepish | Admin Panel | Admin | ChangePassword');
        $this->updatePassword('Admins');
    }

    public function forgotPassword() {
        $this->set('title', 'Kepish | Admin Forgot Password');

        if ($this->request->data()) {    // get email id of admins table
            $email_id = $this->request->data['email'];

            $query = $this->Admins->find()
                    ->where(['Admins.email ' => $email_id]);
            foreach ($query as $row) {
                $admin_id = $row->id;
            }
            //count number of row.
            $number = $query->count();
            if ($number == '1') {
                /* generate random for change password */

                $code = rtrim(md5($email_id), "=");

                $forgotPasswordAuthTable = TableRegistry::get('Admins');

                $query = $forgotPasswordAuthTable->query();
                $reset_code = $code;
                $time = date('Y-m-d H:i:s');
                // update code on database
                $query->update()->set(['reset_code' => $code, 'reset_time' => $time])
                        ->where(['email' => $email_id])->execute();
                if ($query) {
                    // gmail Configuration
                    $status = $this->sendMail($email_id, $email_id, 'Kepish Account | Password Reset Request', "nothing", $code, 'Admins', 'veryfy', 'Admin', 'forgot_password');
                    if ($status == true) {
                        $this->Flash->success('Please visit your registered email address to reset password.', ['key' => 'flash_message',]);
                        $this->redirect(['controller' => 'Admins', 'action' => 'forgotPassword']);
                    } else {
                        $this->Flash->error('Email could not be send. Please try again later.', ['key' => 'flash_message',]);
                        $this->redirect(['controller' => 'Admins', 'action' => 'forgotPassword']);
                    }
                }
            } else {
                $this->Flash->error('Email address does not exist. Please try again.', ['key' => 'flash_message',]);
                $this->redirect(['controller' => 'Admins', 'action' => 'forgotPassword']);
            }
        }
    }

    public function veryfy($code = null) {
        $this->set('title', 'Kepish | Admins Reset Password');

        $query = $this->Admins->find()
                ->where(['Admins.reset_code ' => $code]);
        foreach ($query as $row) {
            $admin_id = $row->id;
            $reset_time = $row->reset_time;
            $now_date = date('Y-m-d H:i:s');
            // echo $difference = $now_date->diff($reset_time);die;
            //echo $interval = date_diff($now_date, $reset_time);die;
            $seconds = (strtotime($now_date) - strtotime($reset_time));
            if ($seconds > 60 * 60 * 24) {
                $this->Flash->error('This link has been expired. Please try again.', ['key' => 'flash_message',]);
                $this->redirect(['controller' => 'Admins', 'action' => 'forgotPassword']);
            }
        }
        //count number of row.
        $number = $query->count();
        $this->set('code', $code);
        if ($number < 1) {
            $this->Flash->error('Bad Request', ['key' => 'flash_message',]);
            $this->redirect(['controller' => 'Admins', 'action' => 'forgotPassword']);
        }
    }

    public function reSetPassword() {
        $password = $this->request->data['password'];
        $con_password = $this->request->data['con_password'];
        $code = $this->request->data['code'];

        $forgotPasswordAuthTable = TableRegistry::get('Admins');
        $query = $forgotPasswordAuthTable->query();

        $result = $query->update()->set(['password' => (new DefaultPasswordHasher)->hash($password), 'reset_code' => '', 'reset_time' => ''])
                        ->where(['reset_code' => $code])->execute();

        if ($result) {
            $this->Flash->success('Your password has been updated', ['key' => 'flash_message',]);
            $this->redirect(['controller' => 'Admins', 'action' => 'login']);
        }
    }

    public function acl() {
        $admins = $this->Groups->find('All');
        $this->set('admins', $admins);
    }

    //This below function is used for getting All controllers name exist in Admins group and aco table
    public function getControllers() {
        if ($this->request->is('ajax')) {
            $group_id = $this->request->data['controller_data'];
            $conn = ConnectionManager::get('default');
            if ($group_id == 1) {
                //get All Controllers Id inside in Admins
                $data = $this->loadModel('Admins')->getParentId();
                //get Coach Id
                foreach ($data['parent_coach_result'] as $row) {
                    $parentId['coach_parentId'] = $row['controller'];
                }
                //get Sport Id
                foreach ($data['parent_sport_result'] as $row) {
                    $parentId['sport_parentId'] = $row['controller'];
                }
                //get Payment Id
                foreach ($data['parent_payment_result'] as $row) {
                    $parentId['payment_parentId'] = $row['controller'];
                }
                //get Doctor Id
                foreach ($data['parent_doctor_result'] as $row) {
                    $parentId['doctor_parentId'] = $row['controller'];
                }
                //get Athlete Id
                foreach ($data['parent_athlete_result'] as $row) {
                    $parentId['athlete_parentId'] = $row['controller'];
                }

                //get CmsPage Id
                foreach ($data['parent_cmspage_result'] as $row) {
                    $parentId['cmspage_parentId'] = $row['controller'];
                }
                //get MasterValues (Settings) Id
                foreach ($data['parent_mastervalue_result'] as $row) {
                    $parentId['mastervalue_parentId'] = $row['controller'];
                }
                //get Division Id
                foreach ($data['parent_division_result'] as $row) {
                    $parentId['division_parentId'] = $row['controller'];
                }
                //get Team Id
                foreach ($data['parent_team_result'] as $row) {
                    $parentId['team_parentId'] = $row['controller'];
                }
                //get League Id
                foreach ($data['parent_league_result'] as $row) {
                    $parentId['league_parentId'] = $row['controller'];
                }

                //get Event Id
                foreach ($data['parent_event_result'] as $row) {
                    $parentId['event_parentId'] = $row['controller'];
                }
                //get City Id
                foreach ($data['parent_city_result'] as $row) {
                    $parentId['city_parentId'] = $row['controller'];
                }
                //get SubscriptionPackages Id
                foreach ($data['parent_subpack_result'] as $row) {
                    $parentId['subpack_parentId'] = $row['controller'];
                }

                //get Country Id
                foreach ($data['parent_country_result'] as $row) {
                    $parentId['country_parentId'] = $row['controller'];
                }
                //get State Id
                foreach ($data['parent_state_result'] as $row) {
                    $parentId['state_parentId'] = $row['controller'];
                }
                //get Fees Id
                foreach ($data['parent_fees_result'] as $row) {
                    $parentId['fees_parentId'] = $row['controller'];
                }
                //get GameRecordFrees Id
                foreach ($data['parent_gamerecord_result'] as $row) {
                    $parentId['gamerecord_parentId'] = $row['controller'];
                }
                //get all permissions applied on controllers inside Admin
                $dataresult = $this->loadModel('Admins')->getPermissionOnAlias($group_id, $parentId);
                $coach_result = $dataresult['coach_result'];

                $doctor_result = $dataresult['doctor_result'];
                $sport_result = $dataresult['sport_result'];
                $athlete_result = $dataresult['athlete_result'];
                $payment_result = $dataresult['payment_result'];
                $cmspage_result = $dataresult['cmspage_result'];
                $league_result = $dataresult['league_result'];
                $subpack_result = $dataresult['subpack_result'];
                $div_result = $dataresult['div_result'];
                $team_result = $dataresult['team_result'];
                $state_result = $dataresult['state_result'];
                $country_result = $dataresult['country_result'];
                $city_result = $dataresult['city_result'];
                $master_result = $dataresult['master_result'];
                $event_result = $dataresult['event_result'];
                $fees_result = $dataresult['fees_result'];
                $gamerecord_result = $dataresult['gamerecord_result'];

                $this->set(compact('coach_result'));
                $this->set(compact('doctor_result'));
                $this->set(compact('sport_result'));
                $this->set(compact('athlete_result'));
                $this->set(compact('payment_result'));
                $this->set(compact('cmspage_result'));
                $this->set(compact('league_result'));
                $this->set(compact('subpack_result'));
                $this->set(compact('div_result'));
                $this->set(compact('team_result'));
                $this->set(compact('state_result'));
                $this->set(compact('country_result'));
                $this->set(compact('city_result'));
                $this->set(compact('master_result'));
                $this->set(compact('event_result'));
                $this->set(compact('fees_result'));
                $this->set(compact('gamerecord_result'));
                $admin_groupid = 1;
                $this->set(compact("admin_groupid"));
            } elseif ($group_id == 2) {
                //getting Parent_Id for Athletes Prefix
                $parent_result = $this->loadModel('Athletes')->getParentId();
                foreach ($parent_result as $row) {
                    $parent_id = $row['controller'];
                }

                //get permission applied on alias for Athletes/Athletes
                $dataresult = $this->loadModel('Athletes')->getPermissionOnAlias($group_id, $parent_id);
                $athlete_result = $dataresult['athlete_result'];
                $event_result = $dataresult['event_result'];
                $injury_result = $dataresult['injury_result'];
                $test_result = $dataresult['test_result'];

                $athlete_groupid = 2;
                $this->set(compact('athlete_result'));
                $this->set(compact('event_result'));
                $this->set(compact('injury_result'));
                $this->set(compact('test_result'));
                $this->set(compact('athlete_groupid'));
            } elseif ($group_id == 3) {

                //getting Parent_Id for Leagues Prefix
                $parent_result = $this->loadModel('Leagues')->getParentIdLeague();
                foreach ($parent_result as $row) {
                    $parent_id = $row['controller'];
                }

                //get permission applied on alias for Leagues/Leagues
                $dataresult = $this->loadModel('Leagues')->getPermissionOnAlias($group_id, $parent_id);
                $athlete_result = $dataresult['athlete_result'];
                $event_result = $dataresult['event_result'];
                $league_result = $dataresult['league_result'];
                $team_result = $dataresult['team_result'];
                $div_result = $dataresult['div_result'];
                $injury_result = $dataresult['injury_result'];
                $coach_result = $dataresult['coach_result'];
                 $baseline_result = $dataresult['baseline_result'];

                $league_groupid = 3;
                $this->set(compact('league_result'));
                $this->set(compact('team_result'));
                $this->set(compact('coach_result'));
                $this->set(compact('div_result'));
                $this->set(compact('event_result'));
                $this->set(compact('injury_result'));
                $this->set(compact('athlete_result'));
                $this->set(compact('baseline_result'));
                $this->set(compact('league_groupid'));
            } elseif ($group_id == 4) {
                //getting Parent_Id for Doctors Prefix
                $parent_result = $this->loadModel('Doctors')->getParentIdDoctor();
                foreach ($parent_result as $row) {
                    $parent_id = $row['controller'];
                }

                //get permission applied on alias for Doctors/Doctors
                $dataresult = $this->loadModel('Doctors')->getPermissionOnAlias($group_id, $parent_id);
                $injury_result = $dataresult['injury_result'];
                $doctor_result = $dataresult['doctor_result'];

                $doctor_groupid = 4;
                $this->set(compact('doctor_result'));
                $this->set(compact('injury_result'));
                $this->set(compact('doctor_groupid'));
            } elseif ($group_id == 5) {
                //get Parent_id of Prefix Controllers from Leagues for Coach
                $parent_result = $this->loadModel('Leagues')->getParentId();
                foreach ($parent_result as $row) {
                    $parent_id = $row['controller'];
                }
                 // get permission for applied alias for Coach
                $dataresult = $this->loadModel('Coaches')->getPermissionOnAlias($group_id, $parent_id);
                $injury_result = $dataresult['injury_result'];
                $event_result = $dataresult['event_result'];
                $coach_result = $dataresult['coach_result'];
                $athlete_result = $dataresult['athlete_result'];


                $coach_groupid = 5;
                $this->set(compact('event_result'));
                $this->set(compact('injury_result'));
                $this->set(compact('athlete_result'));
                $this->set(compact('coach_result'));
                $this->set(compact('coach_groupid'));
            } else {

                $result = 'null';
                $this->set(compact('result'));
            }
        }
    }

    public function userControllers() {
        if ($this->request->is('ajax')) {
            $user_id = $this->request->data['user_data'];
            $group_id = $this->request->data['group_id'];
            $conn = ConnectionManager::get('default');
            if ($user_id != 0 && $group_id == 1) {

//get All Controllers Id inside in Admins for specific User
                $data = $this->loadModel('Admins')->getParentId();
                //get Coach Id
                foreach ($data['parent_coach_result'] as $row) {
                    $parentId['coach_parentId'] = $row['controller'];
                }
                //get Sport Id
                foreach ($data['parent_sport_result'] as $row) {
                    $parentId['sport_parentId'] = $row['controller'];
                }
                //get Payment Id
                foreach ($data['parent_payment_result'] as $row) {
                    $parentId['payment_parentId'] = $row['controller'];
                }
                //get Doctor Id
                foreach ($data['parent_doctor_result'] as $row) {
                    $parentId['doctor_parentId'] = $row['controller'];
                }
                //get Athlete Id
                foreach ($data['parent_athlete_result'] as $row) {
                    $parentId['athlete_parentId'] = $row['controller'];
                }

                //get CmsPage Id
                foreach ($data['parent_cmspage_result'] as $row) {
                    $parentId['cmspage_parentId'] = $row['controller'];
                }
                //get MasterValues (Settings) Id
                foreach ($data['parent_mastervalue_result'] as $row) {
                    $parentId['mastervalue_parentId'] = $row['controller'];
                }
                //get Division Id
                foreach ($data['parent_division_result'] as $row) {
                    $parentId['division_parentId'] = $row['controller'];
                }
                //get Team Id
                foreach ($data['parent_team_result'] as $row) {
                    $parentId['team_parentId'] = $row['controller'];
                }
                //get League Id
                foreach ($data['parent_league_result'] as $row) {
                    $parentId['league_parentId'] = $row['controller'];
                }

                //get Event Id
                foreach ($data['parent_event_result'] as $row) {
                    $parentId['event_parentId'] = $row['controller'];
                }
                //get City Id
                foreach ($data['parent_city_result'] as $row) {
                    $parentId['city_parentId'] = $row['controller'];
                }
                //get SubscriptionPackages Id
                foreach ($data['parent_subpack_result'] as $row) {
                    $parentId['subpack_parentId'] = $row['controller'];
                }

                //get Country Id
                foreach ($data['parent_country_result'] as $row) {
                    $parentId['country_parentId'] = $row['controller'];
                }
                //get State Id
                foreach ($data['parent_state_result'] as $row) {
                    $parentId['state_parentId'] = $row['controller'];
                }
                //get Fees Id
                foreach ($data['parent_fees_result'] as $row) {
                    $parentId['fees_parentId'] = $row['controller'];
                }
                //get GameRecordFrees Id
                foreach ($data['parent_gamerecord_result'] as $row) {
                    $parentId['gamerecord_parentId'] = $row['controller'];
                }
                //get all permissions applied on controllers inside Admin
                $dataresult = $this->loadModel('Admins')->getPermissionOnAliasForUser($user_id, $parentId);
                $coach_result = $dataresult['coach_result'];
                $doctor_result = $dataresult['doctor_result'];
                $sport_result = $dataresult['sport_result'];
                $athlete_result = $dataresult['athlete_result'];
                $payment_result = $dataresult['payment_result'];
                $cmspage_result = $dataresult['cmspage_result'];
                $league_result = $dataresult['league_result'];
                $subpack_result = $dataresult['subpack_result'];
                $div_result = $dataresult['div_result'];
                $team_result = $dataresult['team_result'];
                $state_result = $dataresult['state_result'];
                $country_result = $dataresult['country_result'];
                $city_result = $dataresult['city_result'];
                $master_result = $dataresult['master_result'];
                $event_result = $dataresult['event_result'];
                $fees_result = $dataresult['fees_result'];
                $gamerecord_result = $dataresult['gamerecord_result'];





                $this->set(compact('coach_result'));
                $this->set(compact('doctor_result'));
                $this->set(compact('sport_result'));
                $this->set(compact('athlete_result'));
                $this->set(compact('payment_result'));
                $this->set(compact('cmspage_result'));
                $this->set(compact('league_result'));
                $this->set(compact('subpack_result'));
                $this->set(compact('div_result'));
                $this->set(compact('team_result'));
                $this->set(compact('state_result'));
                $this->set(compact('country_result'));
                $this->set(compact('city_result'));
                $this->set(compact('master_result'));
                $this->set(compact('event_result'));
                $this->set(compact('fees_result'));
                $this->set(compact('gamerecord_result'));
                $admingroup_id = 1;
                $this->set(compact('admingroup_id'));
            } elseif ($user_id != 0 && $group_id == 2) {
                //getting Parent_Id for Athletes Prefix
                $parent_result = $this->loadModel('Athletes')->getParentId();
                foreach ($parent_result as $row) {
                   $parent_id = $row['controller'];
                }

                //get permission applied on alias for Athletes/Athletes for specific user
                $dataresult = $this->loadModel('Athletes')->getPermissionOnAliasForUser($user_id, $parent_id);
                $athlete_result = $dataresult['athlete_result'];
                $event_result = $dataresult['event_result'];
                $injury_result = $dataresult['injury_result'];
                $test_result = $dataresult['test_result'];


                $atheletegroup_id = 2;
                $this->set(compact('athlete_result'));
                $this->set(compact('event_result'));
                $this->set(compact('injury_result'));
                $this->set(compact('test_result'));
                $this->set(compact('atheletegroup_id'));
            } elseif ($user_id != 0 && $group_id == 3) {
                $leaguegroup_id = 3;
                //getting Parent_Id for Leagues Prefix
                $parent_result = $this->loadModel('Leagues')->getParentIdLeague();
                foreach ($parent_result as $row) {
                    $parent_id = $row['controller'];
                }
                //get permission applied on alias for Leagues/Leagues for specific user
                $dataresult = $this->loadModel('Leagues')->getPermissionOnAliasForUser($user_id, $parent_id);
                $athlete_result = $dataresult['athlete_result'];
                $event_result = $dataresult['event_result'];
                $league_result = $dataresult['league_result'];
                $team_result = $dataresult['team_result'];
                $div_result = $dataresult['div_result'];
                $injury_result = $dataresult['injury_result'];
                $coach_result = $dataresult['coach_result'];
                 $baseline_result = $dataresult['baseline_result'];


                $this->set(compact('leaguegroup_id'));
                $this->set(compact('athlete_result'));
                $this->set(compact('league_result'));
                $this->set(compact('team_result'));
                $this->set(compact('coach_result'));
                $this->set(compact('div_result'));
                $this->set(compact('event_result'));
                 $this->set(compact('baseline_result'));
                $this->set(compact('injury_result'));
            } elseif ($user_id != 0 && $group_id == 4) {
                //getting Parent_Id for Doctors Prefix
                $parent_result = $this->loadModel('Doctors')->getParentIdDoctor();
                foreach ($parent_result as $row) {
                    $parent_id = $row['controller'];
                }
                $doctorgroup_id = 4;
                //get permission applied on alias for Doctors/Doctors for specific user
                $dataresult = $this->loadModel('Doctors')->getPermissionOnAliasForUser($user_id, $parent_id);
                $injury_result = $dataresult['injury_result'];
                $doctor_result = $dataresult['doctor_result'];

                $this->set(compact('doctorgroup_id'));
                $this->set(compact('doctor_result'));
                 $this->set(compact('injury_result'));
            } elseif ($user_id != 0 && $group_id == 5) {
                //get Parent_id of Prefix Controllers from Leagues for Coach
                $parent_result = $this->loadModel('Leagues')->getParentId();
                foreach ($parent_result as $row) {
                    $parent_id = $row['controller'];
                }
                     //get permission applied on alias for Coaches/Coaches for specific user
                 $dataresult = $this->loadModel('Coaches')->getPermissionOnAliasForUser($user_id, $parent_id);
                $injury_result = $dataresult['injury_result'];
                $event_result = $dataresult['event_result'];
                $coach_result = $dataresult['coach_result'];
                $athlete_result = $dataresult['athlete_result'];


                $coachgroup_id = 5;
                $this->set(compact('coachgroup_id'));

                $this->set(compact('athlete_result'));
                $this->set(compact('coach_result'));

                $this->set(compact('event_result'));
                $this->set(compact('injury_result'));
            } else {
                $result = 'null';
                $this->set(compact('result'));
            }
        }
    }

}
