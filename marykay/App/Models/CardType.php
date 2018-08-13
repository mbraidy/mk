<?php

namespace App\Models;

use PDO;

/**
 * This is the model class for table "card_type".
 *
 * @property int $id
 * @property int $name
 *
 */
class CardType extends \Core\Model
{
    public $code;
    public $name;

    private function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    public function save()
    {

        $db = static::getDB();

        $card = $db->prepare("INSERT INTO card_type
                                         (name)
                                  VALUES (:name)");
        $card->execute([
            'name' => $this->name,
        ]);
    }
    public static function getCardType($id)
    {
        if (isset($id) && $id > 0) {
            $db = static::getDB();
            $card = $db->prepare("SELECT name FROM card_type
                                     WHERE code = :id");
            $card->execute(['id' => $id]);

            $result = $card->fetch(PDO::FETCH_COLUMN);
            return ['id' => $id, 'card' => $result];
        }
        return false;
    }
    public function find()
    {
        $db = static::getDB();
        $cards = $db->prepare("SELECT * FROM card_type");
        $cards->execute();
        $result = $cards->fetch(PDO::FETCH_ASSOC);

        $this->id = $result['id'];
        $this->name = $result['name'];
    }
    public static function findAll()
    {
        $db = static::getDB();
        $cards = $db->prepare("SELECT * FROM card_type");
        $cards->execute();
        return $cards->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function asOptions() {
        $types = self::findAll();

        if (count($types) > 0) {
            $typeSelect= "";
            if (count($types) == 1) {
                $typeSelect.= "<option value='{$types[0]['id']}' selected>{$types[0]['name']}</option>";
            } else {
                foreach ($types as $i => $type) {
                    $typeSelect.= "<option value='{$type['id']}'>{$type['name']}</option>";
                }
            }
        } else {
            $typeSelect= "<option value='0' disabled>No type found</option>";
        }
        return $typeSelect;
    }
 }
