
<?php

class Model_Entity_User extends Model_Entity_Abstract
{
    protected $_data = array(
        'user_id' => null,
        'email' => null,
        'password' => null,
        'phone' => null,
        'first_name' => null,
        'last_name' => null,
        'gender' => null,
        'status' => null,
        'created' => null,
        'role' => null,
    );



    const STATUS_ENABLED = 'ENABLED';
    const STATUS_DISABLED = 'DISABLED';

    public function isDisabled()
    {
        return $this->_data['status'] == self::STATUS_DISABLED;
    }

    protected $_plainPassword = null;

    public function setPassword($plainPassword)
    {
        $this->_plainPassword = $plainPassword;

        $this->password = sha1($plainPassword);
    }

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_STAFF = 'STAFF';

    protected static $_roles = array(
            self::ROLE_STAFF => 'Персонал',
            self::ROLE_ADMIN => 'Администратор',
    );

    public static function getRoles()
    {
        return self::$_roles;
    }

    public function getRoleName()
    {
        return self::$_roles[ $this->_data['role'] ];
    }

    public function isAdmin()
    {
        return $this->role == self::ROLE_ADMIN;
    }

    const GENDER_MALE = 'MALE';
    const GENDER_FEMALE = 'FEMALE';

    protected static $_genders = array(
            self::GENDER_MALE => 'Мужской',
            self::GENDER_FEMALE => 'Женский',
    );

    public static function getGenders()
    {
        return self::$_genders;
    }

    public function getGenderName()
    {
        return self::$_genders[ $this->_data['gender'] ];
    }

    public function getRussianExt()
    {
        if ($this->gender == self::GENDER_FEMALE) {
            return 'а';
        } else {
            return '';
        }
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


}
