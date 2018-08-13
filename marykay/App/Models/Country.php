<?php

namespace App\Models;

use PDO;

/**
 * This is the model class for table "address_country".
 *
 * @property int $code
 * @property int $name
 *
 */
class Country extends \Core\Model
{
    public $code;
    public $name;

    private function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['code'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 45],
        ];
    }

    public function save()
    {
        if ($message=parent::validate($_POST, $this->rules())!=="Success") return $message;

        $db = static::getDB();

        $address = $db->prepare("INSERT INTO address_country
                                         (name)
                                  VALUES (:name)");
        $address->execute([
            'name' => $this->name,
        ]);
    }
    public static function getCountry($code)
    {
        if (isset($code) && strlen($code)==2) {
            $db = static::getDB();
            $country = $db->prepare("SELECT name FROM address_country
                                     WHERE code = :code");
            $country->execute(['code' => $code]);

            $result = $country->fetch(PDO::FETCH_COLUMN);
            return ['code' =>$code, 'country' => $result];
        }
        return false;
    }
    public function find()
    {
        $db = static::getDB();
        $countries = $db->prepare("SELECT * FROM address_country");
        $countries->execute();
        $result = $countries->fetch(PDO::FETCH_ASSOC);

        $this->code = $result['code'];
        $this->name = $result['name'];
    }
    public static function findAll()
    {
        $db = static::getDB();
        $countries = $db->prepare("SELECT * FROM address_country");
        $countries->execute();
        return $countries->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function asOptions() {
        $countries = self::findAll();
        if (count($countries) > 0) {
            $countrySelect= "";
            if (count($countries) == 1) {
                $countrySelect.= "<option value='{$countries[0]['code']}' selected>{$countries[0]['name']}</option>";
            } else {
                foreach ($countries as $country) {
                    $countrySelect.= "<option value='{$country['code']}'>{$country['name']}</option>";
                }
            }
        } else {
            $countrySelect= "<option value='0' disabled>No country found</option>";
        }
        return $countrySelect;
    }

}
