<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property int $userID
 * @property string $name
 * @property string $surname
 * @property string $dob
 * @property int $email
 * @property int $telephone
 *
 */
class Profile extends \Core\Model
{
    public $id;
    public $userID;
    public $name;
    public $surname;
    public $dob;
    public $email;
    public $telephone;

    public function rules()
    {
        return [
            [['userID', 'name', 'surname', 'dob'], 'required'],
            [['userID'], 'integer'],
            [['email'], 'email'],
            [['dob'], 'safe'],
            [['name', 'surname'], 'string', 'max' => 20],
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
                    }
                    break;
            }
        }
        return true;
    }
    public function save() {
        if ($this->validate()!==true) return false;

        $db = static::getDB();
        $data = ['userID' => $_SESSION['ID']];
        $userQuery = $db->prepare("SELECT id FROM user_profile WHERE userID = :userID");
        $userQuery->execute($data);
        $userFound = $userQuery->fetchColumn();

        if ($userFound) {
            $profile = $db->prepare("UPDATE user_profile
                                      SET name=:name, surname=:surname, email=:email, telephone=:telephone, dob=:dob
                                    WHERE userID = :userID");
        } else {
            $profile = $db->prepare("INSERT INTO user_profile
                                          (userID, name, surname, email, telephone, dob)
                                   VALUES (:userID, :name, :surname, :email, :telephone, :dob)");
        }
        $addressQuery = $db->prepare("SELECT id FROM user_address WHERE userID = :userID");
        $addressQuery->execute($data);
        $addressFound = $addressQuery->fetchColumn();

        if ($addressFound) {
            $address = $db->prepare("UPDATE user_address
                                      SET address1=:address1, address2=:address2, c_o=:c_o, postCode=:postCode, cityID=:cityID
                                    WHERE userID = :userID");
        } else {
            $address = $db->prepare("INSERT INTO user_address
                                         (userID, address1, address2, c_o, postCode, cityID)
                                  VALUES (:userID, :address1, :address2, :c_o, :postCode, :cityID)");
        }
        $cardQuery = $db->prepare("SELECT id FROM user_card WHERE userID = :userID");
        $cardQuery->execute($data);
        $cardFound = $cardQuery->fetchColumn();
        if ($cardFound) {
            $card = $db->prepare("UPDATE user_card
                                      SET typeID=:typeID, nameOnCard=:nameOnCard, code=:code, lastMonth=:lastMonth, lastYear=:lastYear
                                    WHERE userID = :userID");
        } else {
            $card = $db->prepare("INSERT INTO user_card
                                         (userID, typeID, nameOnCard, code, lastMonth, lastYear)
                                  VALUES (:userID, :typeID, :nameOnCard, :code, :lastMonth, :lastYear)");
        }

        $db->beginTransaction();
        try {
            $profile->execute([
                'userID' => $this->userID,
                'name' => $this->name,
                'surname' => $this->surname,
                'email' => $this->email,
                'telephone' => $this->telephone,
                'dob' => $this->dob
            ]);
            $user_id = ($userFound)?$_SESSION['ID']:$db->lastInsertId();

            $address->execute([
                'userID' =>  $user_id,
                'address1' =>  $_POST['address1'],
                'address2' =>  $_POST['address2'],
                'c_o' =>  $_POST['c_o'],
                'postCode' =>  $_POST['postCode'],
                'cityID' =>  $_POST['cityID'],
            ]);

            $card->execute([
                'userID' => $user_id,
                'typeID' =>  $_POST['typeID'],
                'nameOnCard' =>  $_POST['nameOnCard'],
                'code' =>  $_POST['code'],
                'lastMonth' =>  $_POST['lastMonth'],
                'lastYear' =>  $_POST['lastYear'],
            ]);
           $db->commit();
           return $user_id;
       } catch(PDOException $e) {
           $db->rollBack();
           throw $e;
        }
    }
    public function load() {
        $this->name = $_POST['name'];
        $this->surname = $_POST['surname'];
        $this->dob = $_POST['dob'];
        $this->email = $_POST['email'];
        $this->telephone = $_POST['telephone'];
        return;
    }
    public function find() {
        $db = static::getDB();
        $profile = $db->prepare("SELECT *
                                   FROM user_profile
                                  WHERE id = :id");
        $profile->execute(['id' => $_SESSION['ID']]);
        $result = $profile->fetch(PDO::FETCH_ASSOC);

        $this->id = $result['id'];
        $this->userID = $_SESSION['ID'];
        $this->name = $result['name'];
        $this->surname = $result['surname'];
        $this->dob = $result['dob'];
        $this->email = $result['email'];
        $this->telephone = $result['telephone'];
    }
    public static function getFullName($id)
    {
        $db = static::getDB();
        $profile = $db->prepare("SELECT *
                                   FROM user_profile
                                  WHERE id = :id");
        $profile->execute(['id' => $id]);
        $result = $profile->fetch(PDO::FETCH_ASSOC);
        return ($result)?  "{$result['name']} {$result['surname']}" : "Guest";
    }
}
