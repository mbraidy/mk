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
    public function save($user_id)
    {
        return false;
    }
    public function find()
    {
        return true;
    }
}
