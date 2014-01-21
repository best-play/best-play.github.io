
<?php

class Model_Entity_Invite extends Model_Entity_Abstract
{
    protected $_data = array(
        'invite_id' => null,
        'from' => null,
        'to' => null,
        'code' => null,
        'role' => null,
        'created' => null,
        'status' => null,
    );

    const STATUS_NEW = 'NEW';
    const STATUS_USED = 'USED';

    public function generateCode()
    {
        $base = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length = strlen($base);

        $code = array();

        for ($i = 0; $i < 12; $i++) {
            $index = rand(0, $length - 1);
            $code[] = $base[$index];
        }

        return $this->code = implode('', $code);
    }

    /**
     * Expired period of invite, days
     *
     * @var int 
     */
    protected static $_expiredPeriod = 7;

    public function getExpiredDate()
    {
        $created = new DateTime($this->created);

        return $created->modify("+ " . self::$_expiredPeriod . " day");
    }

    public function isExpired()
    {
        $now = new DateTime();
        $expired = $this->getExpiredDate();

        return $now >= $expired;
    }
}
