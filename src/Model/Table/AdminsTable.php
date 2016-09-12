<?php

namespace App\Model\Table;

use App\Model\Entity\Admin;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * Admins Model
 *
 */
class AdminsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('admins');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
//This below line is use for Acl behavior
        $this->addBehavior('Acl.Acl', ['type' => 'requester']);
        //End Acl code
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {


        $validator
                ->email('email')
                ->requirePresence('email', 'create')
                ->notEmpty('email');

        $validator
                ->allowEmpty('username');


        $validator
                ->requirePresence('password', 'create')
                ->notEmpty('password');

        // $validator
        //     ->integer('created_by')
        //     ->requirePresence('created_by', 'create')
        //     ->notEmpty('created_by');
        // $validator
        //     ->integer('modified_by')
        //     ->allowEmpty('modified_by');
        // $validator
        //     ->dateTime('created_on')
        //     ->requirePresence('created_on', 'create')
        //     ->notEmpty('created_on');
        // $validator
        //     ->dateTime('modified_on')
        //     ->allowEmpty('modified_on');

        return $validator;
    }

    /* Make a custome validation */

    public function validationforgotPasssword($validator) {
        $validator
                ->email('email')
                ->requirePresence('email', 'create')
                ->notEmpty('email');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }

    public function findOwnedBy(Query $query, array $options) {
        $user = $options['email'];
        return $query->where(['email' => $user]);
    }

    //Acl code start here
    //This below fuction is used for getting all controllers name from acos table
    public function getParentId() {
        $conn = ConnectionManager::get('default');
        $alias = "select id from acos where alias='controllers'";
        $alias_execute = $conn->execute($alias);
        $alias_result = $alias_execute->fetchAll('assoc');
        foreach ($alias_execute as $row) {
            $alias_id = $row['id'];
        }
        //get Id of Coache Inside Admin
        $parent_coach = $this->getParentIdForGroup('Coaches',$alias_id);
        $parent_coach_execute = $conn->execute($parent_coach);
        $data['parent_coach_result'] = $parent_coach_execute->fetchAll('assoc');
        //End Coach Query
        //Get Id of Athlete Inside Admin
        $parent_athlete = $this->getParentIdForGroup('Athletes',$alias_id);
        $parent_athlete_execute = $conn->execute($parent_athlete);
        $data['parent_athlete_result'] = $parent_athlete_execute->fetchAll('assoc');
        //End Athlete Query
        //Get Id of Sports Inside Admin
        $parent_sport =$this->getParentIdForGroup('Sports',$alias_id);
        $parent_sport_execute = $conn->execute($parent_sport);
        $data['parent_sport_result'] = $parent_sport_execute->fetchAll('assoc');
        //End Sport Query
        //Get Id of Payments Inside Admin
        $parent_payment = $this->getParentIdForGroup('Payments',$alias_id);
        $parent_payment_execute = $conn->execute($parent_payment);
        $data['parent_payment_result'] = $parent_payment_execute->fetchAll('assoc');
        //End Payments Query
        //Get Id of Doctors Inside Admin
        $parent_doctor =$this->getParentIdForGroup('Doctors',$alias_id);
        $parent_doctor_execute = $conn->execute($parent_doctor);
        $data['parent_doctor_result'] = $parent_doctor_execute->fetchAll('assoc');
        //End Doctors Query
        //Get Id of CmsPages Inside Admin
        $parent_cmspage =$this->getParentIdForGroup('CmsPages',$alias_id);
        $parent_cmspage_execute = $conn->execute($parent_cmspage);
        $data['parent_cmspage_result'] = $parent_cmspage_execute->fetchAll('assoc');
        //End CmsPages Query
        //Get Id of Setting(MasterValues) Inside Admin
        $parent_mastervalue = $this->getParentIdForGroup('MasterValues',$alias_id);
        $parent_mastervalue_execute = $conn->execute($parent_mastervalue);
        $data['parent_mastervalue_result'] = $parent_mastervalue_execute->fetchAll('assoc');
        //End Setting Query
        //Get Id of Divisions Inside Admin
        $parent_division = $this->getParentIdForGroup('Divisions',$alias_id);
        $parent_division_execute = $conn->execute($parent_division);
        $data['parent_division_result'] = $parent_division_execute->fetchAll('assoc');
        //End Divisions Query
        //Get Id of Teams Inside Admin
        $parent_team =$this->getParentIdForGroup('Teams',$alias_id);
        $parent_team_execute = $conn->execute($parent_team);
        $data['parent_team_result'] = $parent_team_execute->fetchAll('assoc');
        //End Teams Query
        //Get Id of Leagues Inside Admin
        $parent_league =$this->getParentIdForGroup('Leagues',$alias_id);
        $parent_league_execute = $conn->execute($parent_league);
        $data['parent_league_result'] = $parent_league_execute->fetchAll('assoc');
        //End Leagues Query
        //Get Id of Events Inside Admin
        $parent_event =$this->getParentIdForGroup('Events',$alias_id);
        $parent_event_execute = $conn->execute($parent_event);
        $data['parent_event_result'] = $parent_event_execute->fetchAll('assoc');
        //End Events Query
        //Get Id of Cities Inside Admin
        $parent_city =$this->getParentIdForGroup('Cities',$alias_id);
        $parent_city_execute = $conn->execute($parent_city);
        $data['parent_city_result'] = $parent_city_execute->fetchAll('assoc');
        //End Cities Query
        //Get Id of SubscriptionPackages Inside Admin
        $parent_subpack =$this->getParentIdForGroup('SubscriptionPackages',$alias_id);
        $parent_subpack_execute = $conn->execute($parent_subpack);
        $data['parent_subpack_result'] = $parent_subpack_execute->fetchAll('assoc');
        //End SubscriptionPackages Query
        //Get Id of Countries Inside Admin
        $parent_country = $this->getParentIdForGroup('Countries',$alias_id);
        $parent_country_execute = $conn->execute($parent_country);
        $data['parent_country_result'] = $parent_country_execute->fetchAll('assoc');
        //End Countries Query
        //Get Id of States Inside Admin
        $parent_state = $this->getParentIdForGroup('States',$alias_id);
        $parent_state_execute = $conn->execute($parent_state);
        $data['parent_state_result'] = $parent_state_execute->fetchAll('assoc');
        //End States Query
        //Get Id of Fees Inside Admin
        $parent_fees = $this->getParentIdForGroup('Fees',$alias_id);
        $parent_fees_execute = $conn->execute($parent_fees);
        $data['parent_fees_result'] = $parent_fees_execute->fetchAll('assoc');
        //End Fees Query
        //Get Id of GameRecordFrees Inside Admin
        $parent_gamerecord =$this->getParentIdForGroup('GameRecordFrees',$alias_id);
        $parent_gamerecord_execute = $conn->execute($parent_gamerecord);
        $data['parent_gamerecord_result'] = $parent_gamerecord_execute->fetchAll('assoc');
        //End GameRecordFrees Query

        return $data;
    }

    //get permissions on applied alias for all controllers inside Admins
    public function getPermissionOnAlias($group_id, $parentId) {
        $conn = ConnectionManager::get('default');
        $coach_parentId = $parentId['coach_parentId'];
        $sport_parentId = $parentId['sport_parentId'];
        $payment_parentId = $parentId['payment_parentId'];
        $doctor_parentId = $parentId['doctor_parentId'];
        $athlete_parentId = $parentId['athlete_parentId'];
        $cmspage_parentId = $parentId['cmspage_parentId'];
        $mastervalue_parentId = $parentId['mastervalue_parentId'];
        $division_parentId = $parentId['division_parentId'];
        $team_parentId = $parentId['team_parentId'];
        $league_parentId = $parentId['league_parentId'];
        $event_parentId = $parentId['event_parentId'];
        $city_parentId = $parentId['city_parentId'];
        $subpack_parentId = $parentId['subpack_parentId'];
        $country_parentId = $parentId['country_parentId'];
        $state_parentId = $parentId['state_parentId'];
        $fees_parentId = $parentId['fees_parentId'];
        $gamerecord_parentId = $parentId['gamerecord_parentId'];
        //Query for getting values of permission and alias name for coaches
       $coach_query = $this->getAliasForGroup('view','edit','invitecoach','delete', $coach_parentId, $group_id);
        $coach_execute = $conn->execute($coach_query);
        $dataresult['coach_result'] = $coach_execute->fetchAll('assoc');

        //End Coaches Query
        //Query for getting values of permission and alias name for doctors
        $doctor_query = $this->getAliasForGroup('view','edit','add','delete',$doctor_parentId, $group_id);
        $doctor_execute = $conn->execute($doctor_query);
        $dataresult['doctor_result'] = $doctor_execute->fetchAll('assoc');
        //End Doctors Query
        //Query for getting values of permission and alias name for sports
        $sport_query =$this->getAliasForGroup('view','edit','add','delete',$sport_parentId, $group_id);
        $sport_execute = $conn->execute($sport_query);
        $dataresult['sport_result'] = $sport_execute->fetchAll('assoc');
        //End Sports Query
        //Query for getting values of permission and alias name for Athletes
        $athlete_query =$this->getAliasForGroup('view','edit','add','delete',$athlete_parentId, $group_id); 
        $athlete_execute = $conn->execute($athlete_query);
        $dataresult['athlete_result'] = $athlete_execute->fetchAll('assoc');
        //End Athletes Query
        //Query for getting values of permission and alias name for Payments
        $payment_query =$this->getAliasForGroup('view','edit','add','delete',$payment_parentId, $group_id);
        $payment_execute = $conn->execute($payment_query);
        $dataresult['payment_result'] = $payment_execute->fetchAll('assoc');
        //End Payments Query
        //Query for getting values of permission and alias name for CmsPages
        $cmspage_query =$this->getAliasForGroup('view','edit','add','delete',$cmspage_parentId, $group_id);
        $cmspage_execute = $conn->execute($cmspage_query);
        $dataresult['cmspage_result'] = $cmspage_execute->fetchAll('assoc');
        //End CmsPages Query
        //Query for getting values of permission and alias name for Leagues
        $league_query =$this->getAliasForGroup('view','edit','add','delete',$league_parentId, $group_id);
        $league_execute = $conn->execute($league_query);
        $dataresult['league_result'] = $league_execute->fetchAll('assoc');
        //End Leagues Query
        //Query for getting values of permission and alias name for SubscriptionPackages
        $subpack_query =$this->getAliasForGroup('view','edit','add','delete',$subpack_parentId, $group_id);
        $subpack_execute = $conn->execute($subpack_query);
        $dataresult['subpack_result'] = $subpack_execute->fetchAll('assoc');
        //End SubscriptionPackages Query
        //Query for getting values of permission and alias name for Divisions
        $div_query =$this->getAliasForGroup('view','edit','add','delete',$division_parentId, $group_id);
        $div_execute = $conn->execute($div_query);
        $dataresult['div_result'] = $div_execute->fetchAll('assoc');
        //End Divisions Query
        //Query for getting values of permission and alias name for Teams
        $team_query = $this->getAliasForGroup('view','edit','add','delete',$team_parentId, $group_id);
        $team_execute = $conn->execute($team_query);
        $dataresult['team_result'] = $team_execute->fetchAll('assoc');
        //End Teams Query
        //Query for getting values of permission and alias name for States
        $state_query = $this->getAliasForGroup('view','edit','add','delete',$state_parentId, $group_id);
        $state_execute = $conn->execute($state_query);
        $dataresult['state_result'] = $state_execute->fetchAll('assoc');
        //End States Query
        //Query for getting values of permission and alias name for Country
        $country_query =$this->getAliasForGroup('view','edit','add','delete',$country_parentId, $group_id);
        $country_execute = $conn->execute($country_query);
        $dataresult['country_result'] = $country_execute->fetchAll('assoc');
        //End Country Query
        //Query for getting values of permission and alias name for Cities
        $city_query =$this->getAliasForGroup('view','edit','add','delete',$city_parentId, $group_id);
        $city_execute = $conn->execute($city_query);
        $dataresult['city_result'] = $city_execute->fetchAll('assoc');
        //End Cities Query
        //Query for getting values of permission and alias name for MasterValues
        $master_query =$this->getAliasForGroup('view','edit','add','delete',$mastervalue_parentId, $group_id);
        $master_execute = $conn->execute($master_query);
        $dataresult['master_result'] = $master_execute->fetchAll('assoc');
        //End MasterValues Query
        //Query for getting values of permission and alias name for Event
        $event_query = $this->getAliasForGroup('view','edit','add','delete',$event_parentId, $group_id);
        $event_execute = $conn->execute($event_query);
        $dataresult['event_result'] = $event_execute->fetchAll('assoc');
        //End Event Query
        //Query for getting values of permission and alias name for Fees
        $fees_query =$this->getAliasForGroup('view','edit','add','delete',$fees_parentId, $group_id);
        $fees_execute = $conn->execute($fees_query);
        $dataresult['fees_result'] = $fees_execute->fetchAll('assoc');
        //End Fees Query
        //Query for getting values of permission and alias name for GameRecordFrees
        $gamerecord_query = $this->getAliasForGroup('view','edit','add','delete',$gamerecord_parentId, $group_id);
        $gamerecord_execute = $conn->execute($gamerecord_query);
        $dataresult['gamerecord_result'] = $gamerecord_execute->fetchAll('assoc');
        //End Fees Query  

        return $dataresult;
    }

    //get permissions on applied alias for all controllers inside Admins for Specific User
    public function getPermissionOnAliasForUser($user_id, $parentId) {
        $conn = ConnectionManager::get('default');
        $coach_parentId = $parentId['coach_parentId'];
        $sport_parentId = $parentId['sport_parentId'];
        $payment_parentId = $parentId['payment_parentId'];
        $doctor_parentId = $parentId['doctor_parentId'];
        $athlete_parentId = $parentId['athlete_parentId'];
        $cmspage_parentId = $parentId['cmspage_parentId'];
        $mastervalue_parentId = $parentId['mastervalue_parentId'];
        $division_parentId = $parentId['division_parentId'];
        $team_parentId = $parentId['team_parentId'];
        $league_parentId = $parentId['league_parentId'];
        $event_parentId = $parentId['event_parentId'];
        $city_parentId = $parentId['city_parentId'];
        $subpack_parentId = $parentId['subpack_parentId'];
        $country_parentId = $parentId['country_parentId'];
        $state_parentId = $parentId['state_parentId'];
        $fees_parentId = $parentId['fees_parentId'];
        $gamerecord_parentId = $parentId['gamerecord_parentId'];
        
         //Query for getting values of permission and alias name for coaches
                $coach_query = $this->getAliasForUser('view','edit','invitecoach','delete',$user_id,$coach_parentId);
                $coach_execute = $conn->execute($coach_query);
                $dataresult['coach_result'] = $coach_execute->fetchAll('assoc');

                //End Coaches Query
                //Query for getting values of permission and alias name for doctors
                $doctor_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$doctor_parentId);
                $doctor_execute = $conn->execute($doctor_query);
                $dataresult['doctor_result'] = $doctor_execute->fetchAll('assoc');

                //End Doctors Query
                //Query for getting values of permission and alias name for sports
                $sport_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$sport_parentId);
                $sport_execute = $conn->execute($sport_query);
                $dataresult['sport_result'] = $sport_execute->fetchAll('assoc');
                //End Sports Query
                //Query for getting values of permission and alias name for Athletes
                $athlete_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$athlete_parentId);
                $athlete_execute = $conn->execute($athlete_query);
                $dataresult['athlete_result'] = $athlete_execute->fetchAll('assoc');

                //End Athletes Query
                //Query for getting values of permission and alias name for Payments
                $payment_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$payment_parentId);
                $payment_execute = $conn->execute($payment_query);
                $dataresult['payment_result'] = $payment_execute->fetchAll('assoc');
                //End Payments Query
                //Query for getting values of permission and alias name for CmsPages
                $cmspage_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$cmspage_parentId);
                $cmspage_execute = $conn->execute($cmspage_query);
                $dataresult['cmspage_result'] = $cmspage_execute->fetchAll('assoc');
                //End CmsPages Query
                //Query for getting values of permission and alias name for Leagues
                $league_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$league_parentId);
                $league_execute = $conn->execute($league_query);
                $dataresult['league_result'] = $league_execute->fetchAll('assoc');
                //End Leagues Query
                //Query for getting values of permission and alias name for SubscriptionPackages
                $subpack_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$subpack_parentId);
                $subpack_execute = $conn->execute($subpack_query);
                $dataresult['subpack_result'] = $subpack_execute->fetchAll('assoc');
                //End SubscriptionPackages Query
                //Query for getting values of permission and alias name for Divisions
                $div_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$division_parentId);
                $div_execute = $conn->execute($div_query);
                $dataresult['div_result'] = $div_execute->fetchAll('assoc');
                //End Divisions Query
                //Query for getting values of permission and alias name for Teams
                $team_query =$this->getAliasForUser('view','edit','add','delete',$user_id,$team_parentId);
                $team_execute = $conn->execute($team_query);
                $dataresult['team_result'] = $team_execute->fetchAll('assoc');
                //End Teams Query
                //Query for getting values of permission and alias name for States
                $state_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$state_parentId);
                $state_execute = $conn->execute($state_query);
                $dataresult['state_result'] = $state_execute->fetchAll('assoc');
                //End States Query
                //Query for getting values of permission and alias name for Country
                $country_query =$this->getAliasForUser('view','edit','add','delete',$user_id,$country_parentId);
                $country_execute = $conn->execute($country_query);
                $dataresult['country_result'] = $country_execute->fetchAll('assoc');
                //End Country Query
                //Query for getting values of permission and alias name for Cities
                $city_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$city_parentId);
                $city_execute = $conn->execute($city_query);
                $dataresult['city_result'] = $city_execute->fetchAll('assoc');
                //End Cities Query
                //Query for getting values of permission and alias name for MasterValues
                $master_query =$this->getAliasForUser('view','edit','add','delete',$user_id,$mastervalue_parentId);
                $master_execute = $conn->execute($master_query);
                $dataresult['master_result'] = $master_execute->fetchAll('assoc');
                //End MasterValues Query
                //Query for getting values of permission and alias name for Event
                $event_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$event_parentId);
                $event_execute = $conn->execute($event_query);
                $dataresult['event_result'] = $event_execute->fetchAll('assoc');
                //End Event Query
                
        //Query for getting values of permission and alias name for Fees
                $fees_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$fees_parentId);
                $fees_execute = $conn->execute($fees_query);
                $dataresult['fees_result'] = $fees_execute->fetchAll('assoc');
                //End fees Query
                   //Query for getting values of permission and alias name for GameRecordFrees
                $gamerecord_query = $this->getAliasForUser('view','edit','add','delete',$user_id,$gamerecord_parentId);
                $gamerecord_execute = $conn->execute($gamerecord_query);
                $dataresult['gamerecord_result'] = $gamerecord_execute->fetchAll('assoc');
                //End fees Query
                
                return $dataresult;
        
        
    }
    public function getAliasForGroup($view,$edit,$add,$delete,$parent_id,$group_id)
    {
        $query="SELECT if(GROUP_CONCAT(if(alias ='$view', ar._create, NULL)),GROUP_CONCAT(if(alias ='$view', ar._create, NULL)),'1') as '$view',if(GROUP_CONCAT(if(alias = '$edit', ar._create, NULL)),GROUP_CONCAT(if(alias = '$edit', ar._create, NULL)),'1') as '$edit',if(GROUP_CONCAT(if(alias = '$add', ar._create, NULL)),GROUP_CONCAT(if(alias = '$add', ar._create, NULL)),'1') as '$add',if(GROUP_CONCAT(if(alias = '$delete' , ar._create, NULL)),GROUP_CONCAT(if(alias = '$delete' , ar._create, NULL)),'1') as '$delete' from acos a inner join aros_acos ar on a.id=ar.aco_id where a.parent_id=$parent_id and ar.aro_id=$group_id and a.alias in('$view','$edit','$add','$delete')";
        return $query;
    }
    public function getParentIdForGroup($controller,$alias_id)
    {
      $query="SELECT * FROM (SELECT  a.parent_id as 'controller' FROM acos as a INNER JOIN acos as b ON (a.parent_id=b.id) where b.alias='$controller' and b.parent_id=$alias_id and a.alias not in ('controllers','$controller')) as d";  
      return $query;
      }
      public function getAliasForUser($view,$edit,$add,$delete,$user_id,$parent_id)
      {
          $query="SELECT if(GROUP_CONCAT(if(ac.alias = '$view', ar._create, NULL)),GROUP_CONCAT(if(ac.alias = '$view', ar._create, NULL)),'1') as '$view',if(GROUP_CONCAT(if(ac.alias = '$edit', ar._create, NULL)),GROUP_CONCAT(if(ac.alias = '$edit', ar._create, NULL)),'1') as '$edit',if(GROUP_CONCAT(if(ac.alias = '$add', ar._create, NULL)),GROUP_CONCAT(if(ac.alias = '$add', ar._create, NULL)),'1') as '$add',if(GROUP_CONCAT(if(ac.alias = '$delete' , ar._create, NULL)),GROUP_CONCAT(if(ac.alias = '$delete' , ar._create, NULL)),'1') as '$delete' from acos ac inner join aros_acos ar on ac.id=ar.aco_id inner join aros aa on ar.aro_id=aa.id where aa.foreign_key='$user_id'  and ac.parent_id='$parent_id'";
         return $query;
      }
    //Acl code End Here
}
