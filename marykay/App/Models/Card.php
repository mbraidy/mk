<?php

namespace App\Models;

use PDO;

/**
 * This is the model class for table "user_card".
 *
 * @property int $id
 * @property int $userID
 * @property int $typeID
 * @property string $nameOnCard
 * @property string $code
 * @property int $lastMonth
 * @property int $lastYear
 *
 */
class Card extends \Core\Model
{
    public $id;
    public $userID;
    public $typeID;
    public $nameOnCard;
    public $code;
    public $lastMonth;
    public $lastYear;
    public $ccv;

    public $cardType;

    private function rules()
    {
        return [
            [['userID', 'typeID', 'nameOnCard', 'code', 'lastMonth', 'lastYear'], 'required'],
            [['userID', 'typeID', 'lastMonth', 'lastYear', 'ccv'], 'integer'],
            [['nameOnCard'], 'string', 'max' => 50],
            [['code'], 'string', 'len' => 16]
        ];
    }
    private function validate() {
        foreach ($this->rules() as $rule) {
            switch ($rule[1]) {
                case 'required':
                    foreach ($rule[0] as $attr) {
                        if (!isset($this->$attr)) return "{$attr} is not defined";
                    }
                    break;
                case 'integer':
                    foreach ($rule[0] as $attr) {
                        if (!is_numeric($this->$attr)) return "{$attr} is not a number";
                    }
                    break;
                case 'email':
                    foreach ($rule[0] as $attr) {
                        $valid = true;
                        $this->$attr = strtolower($this->$attr);
                        if ( strpos($this->$attr, '@') ) {
                            $split = explode('@', $this->$attr);
                            $valid = (strpos($split['1'], '.') );
                        }
                        else {
                            $valid = false;
                        }
                        if (!$valid) return "{$attr} is not a valid email";
                    }

                    break;
                case 'string':
                    foreach ($rule[0] as $attr) {
                        $this->$attr = (string)$this->$attr;
                        if (isset($rule['max'])) {
                            if (strlen( $this->$attr) > $rule['max']) return "{$attr} should be maximum {$rule['max']} characters long";
                        }
                        if (isset($rule['min'])) {
                            if (strlen( $this->$attr) < $rule['min']) return "{$attr} should be minimum {$rule['min']} characters long";
                        }
                        if (isset($rule['len'])) {
                            if (strlen( $this->$attr) < $rule['len']) return "{$attr} should be exactly {$rule['len']} characters long";
                        }
                    }
                    break;
            }
        }
        return true;
    }
    public function save($user_id) {
        if ($this->validate()!==true) return false;

        $db = static::getDB();
        $card = $db->prepare("INSERT INTO user_card
                                         (userID, typeID, nameOnCard, code, lastMonth, lastYear)
                                  VALUES (:userID, :typeID, :nameOnCard, :code, :lastMonth, :lastYear)");
        $card->execute([
            'userID' => $user_id,
            'typeID' => $this->typeID,
            'nameOnCard' => $this->nameOnCard,
            'code' => $this->code,
            'lastMonth' => $this->lastMonth,
            'lastYear' => $this->lastYear,
        ]);
    }
    public static function encodeCard($code)
    {
        return substr($code, 0, 3). '* **** **** ' . substr($code, 12, 4);
    }
    public static function getCards($userID)
    {
        $db = static::getDB();
        $card = $db->prepare("SELECT user_card.*, card_type.name AS cardType  FROM user_card
                                LEFT JOIN card_type ON card_type.id = user_card.typeID
                               WHERE userID = :userID");
        $card->execute( ['userID' => $userID]);
        return $card->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCard($userID)
    {
        $db = static::getDB();
        $card = $db->prepare("SELECT * FROM user_card
                                LEFT JOIN card_type ON card_type.id = user_card.typeID
                               WHERE userID = :userID");
        $card->execute( ['userID' => $userID]);

        return $card->fetch(PDO::FETCH_ASSOC);
    }
}
