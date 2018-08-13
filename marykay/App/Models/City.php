<?php

namespace App\Models;

use PDO;

/**
 * This is the model class for table "address_city".
 *
 * @property int $id
 * @property int $name
 * @property int $countryCD
 *
 * @property string $country
 *
 */
class City extends \Core\Model
{
    public $id;
    public $name;
    public $countryCDy;

    public $country;

    private function rules()
    {
        return [
            [['name', 'countryCD'], 'required'],
            [['countryCD'],  'string', 'max' => 2],
            [['country'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    public function save()
    {
        if ($message=parent::validate($_POST, $this->rules())!=="Success") return $message;

        $db = static::getDB();

        $address = $db->prepare("INSERT INTO address_city
                                         (name, countryCD)
                                  VALUES (:name, :countryCD)");
        $address->execute([
            'name' => $this->name,
            'countryCD' => $this->countryCD,
        ]);
    }
    public static function getCity($id)
    {
        if (isset($id) && $id > 0) {
            $db = static::getDB();
            $city = $db->prepare("SELECT * FROM address_city
                                     WHERE id = :id");
            $city->execute(['id' => $id]);

            $result = $city->fetch(PDO::FETCH_ASSOC);
            $country = Country::getCountry($result['countryCD']);
            return ['id' =>$id, 'city' => $result['name'], 'country' => $country['country']];
        }
        return false;
    }
    public function find()
    {
        $db = static::getDB();
        $cities = $db->prepare("SELECT * FROM address_city");
        $cities->execute();
        $result = $cities->fetch(PDO::FETCH_ASSOC);

        $this->id = $result['id'];
        $this->city = $result['name'];
    }
    public static function findAll($country)
    {
        $db = static::getDB();
        if (is_null($country)) {
            $cities = $db->prepare("SELECT * FROM address_city");
            $cities->execute();
        } else {
            $cities = $db->prepare("SELECT * FROM address_city WHERE countryCD = :countryCD");
            $cities->execute(['countryCD' => $country]);
        }
        return $cities->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function asOptions($country = null) {
        $cities = self::findAll($country);
        if (count($cities) > 0) {
            $citySelect= "";
            if (count($cities) == 1) {
                $citySelect.= "<option value='{$cities[0]['id']}' selected>{$cities[0]['name']}</option>";
            } else {
                foreach ($cities as $city) {
                    $citySelect.= "<option value='{$city['id']}'>{$city['name']}</option>";
                }
            }
        } else {
            $citySelect= "<option value='0' disabled>No city found</option>";
        }
        return $citySelect;
    }

}
