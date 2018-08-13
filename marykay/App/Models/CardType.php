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
