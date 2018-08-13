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

    public function save($user_id)
    {
        if ($message=parent::validate($_POST, $this->rules())!=="Success") return $message;

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
