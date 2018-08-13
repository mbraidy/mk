<?php

namespace App\Models;

use PDO;

/**
 * This is the model class for table "purchase_basket" and via purchase_basket_item.
 *
 * @property int $id
 * @property int $userID
 * @property string $date
 * @property string $statusID
 * @property string $total
 * @property [int] $itemIDs
 * @property [int] $quantities
 *
 */
class Purchase extends \Core\Model
{
    public $id;
    public $userID;
    public $date;
    public $statusID;
    public $total;
    public $itemID;
    public $quantities;

    public $status;

    private function rules()
    {
        return [
            [['userID', 'date', 'statusID'], 'required'],
            [['itemID', 'quantities'], 'safe'],
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
    public function save($user_id)
    {
        return false;
    }
    public function find()
    {
        return true;
    }
}
