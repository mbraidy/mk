<?php

namespace App\Models;

use PDO;

/**
 * This is the model class for table "user_address".
 *
 * @property int $id
 * @property int $userID
 * @property string $address1
 * @property string $surname
 * @property string $c_o
 * @property int $postCode
 * @property int $city
 *
 */
class Address extends \Core\Model
{
    public $id;
    public $userID;
    public $address1;
    public $address2;
    public $c_o;
    public $postCode;
    public $cityID;

    public $city;
    public $country;

    private function rules()
    {
        return [
            [['userID', 'postCode', 'cityID'], 'required'],
            [['userID', 'cityID'], 'integer'],
            [['city', 'country'], 'safe'],
            [['address1', 'postCode', 'address2', 'c_o'], 'string', 'max' => 20],
        ];
    }
    public function save($user_id)
    {
        if ($message=parent::validate($_POST, $this->rules())!=="Success") return $message;

        $db = static::getDB();

        $address = $db->prepare("INSERT INTO user_address
                                         (userID, address1, address2, c_o, postCode, cityID)
                                  VALUES (:userID, :address1, :address2, :c_o, :postCode, :cityID)");
        $address->execute([
            'userID' => $user_id,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'c_o' => $this->c_o,
            'postCode' => $this->postCode,
            'cityID' => $this->cityID,
        ]);
        return true;
    }
    public function find()
    {
        $db = static::getDB();
        $address = $db->prepare("SELECT *
                                   FROM user_address
                                  WHERE userID = :userID");
        $address->execute(['userID' => $_SESSION['ID']]);
        $result = $address->fetch(PDO::FETCH_ASSOC);
        $location = City::getCity($result['cityID']);

        $this->id = $result['id'];
        $this->userID = $result['userID'];
        $this->address1 = $result['address1'];
        $this->address2 = $result['address2'];
        $this->c_o = $result['c_o'];
        $this->postCode = $result['postCode'];
        $this->cityID = $result['cityID'];
        $this->city = $location['city'];
        $this->country = $location['country'];
    }
    public static function getAddress($userID)
    {
        $db = static::getDB();
        $address = $db->prepare("SELECT * FROM user_address
                               WHERE userID = :userID");
        $address->execute(['userID' => $userID]);
        $result = $address->fetch(PDO::FETCH_ASSOC);

        $location = City::getCity($result['cityID']);
        $result['city'] = $location['city'];
        $result['country'] = $location['country'];

        return $result;
    }
}
