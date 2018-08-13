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
    private function validate()
    {
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
    public function save()
    {
        if ($this->validate()!==true) return false;

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
