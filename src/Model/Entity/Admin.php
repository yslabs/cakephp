<?php
namespace App\Model\Entity;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Admin Entity.
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $address_street
 * @property string $address_city
 * @property string $address_state
 * @property string $address_zip
 * @property string $address_country
 * @property string $phone
 * @property string $email
 * @property string $password
 * @property int $created_by
 * @property int $modified_by
 * @property \Cake\I18n\Time $created_on
 * @property \Cake\I18n\Time $modified_on
 */
class Admin extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
    
    protected function _setPassword($password){
        return (new DefaultPasswordHasher)->hash($password);
    }    
        //This following code is used for acl 
    public function parentNode()
{
    if (!$this->id) {
        return null;
    }
    if (isset($this->group_id)) {
        $groupId = $this->group_id;
    } else {
        $Admins = TableRegistry::get('Admins');
        $user = $Admins->find('all', ['fields' => ['group_id']])->where(['id' => $this->id])->first();
        $groupId = $user->group_id;
    }
    if (!$groupId) {
        return null;
    }
    return ['Groups' => ['id' => $groupId]];
}
//acl code ends here
}
