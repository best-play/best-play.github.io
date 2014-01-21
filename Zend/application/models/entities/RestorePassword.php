<?php

class Model_Entity_RestorePassword extends Model_Entity_Abstract
{
    protected $_data = array(
        'restore_password_id' => null,
        'whom' => null,
        'code' => null,
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
     * Expired period of password restore request, days
     *
     * @var int
     */
    protected static $_expiredPeriod = 3;

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

    /**
     * Regenerate period of password restore request, hours
     *
     * @var int
     */
    protected static $_regeneratePeriod = 12;

    public function getNextGenerateTime()
    {
        $created = new DateTime($this->created);

        return $created->modify("+ " . self::$_regeneratePeriod . " hour");
    }

    public function isItPossibleToRegenerate()
    {
        $now = new DateTime();
        $expired = $this->getNextGenerateTime();

        return $now >= $expired;
    }
}
