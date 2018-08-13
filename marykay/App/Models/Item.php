<?php

namespace App\Models;

use Core\View;
use PDO;

/**
 * This is the model class for table "purchase_item".
 *
 * @property int $id
 * @property string $name
 * @property string $alt
 * @property string $title
 * @property string $picture
 * @property int $price
 * @property int $currency
 * @property int $description
 * @property int $available
 * @property int $active
 *
 */
class Item extends \Core\Model
{
    public $id;
    public $name;
    public $alt;
    public $title;
    public $picture;
    public $price;
    public $currency;
    public $description;
    public $available;
    public $active;

    private function rules()
    {
        return [
            [['name', 'title', 'picture', 'price', 'currency', 'available', 'active'], 'required'],
            [['price', 'available', 'active'], 'integer'],
            [['alt', 'description'], 'safe'],
            [['currency'], 'string', 'len' => 4],
            [['name'], 'string', 'max' => 30],
            [['alt'], 'string', 'max' => 20],
            [['title'], 'string', 'max' =>100],
        ];
    }
    private function validate() {
        $message = "";
        foreach ($this->rules() as $rule) {
            switch ($rule[1]) {
                case 'required':
                    foreach ($rule[0] as $attr) {
                        if (!isset($this->$attr)) $message .= "{$attr} is not defined";
                    }
                    break;
                case 'integer':
                    foreach ($rule[0] as $attr) {
                        if (!is_numeric($this->$attr)) $message .= "{$attr} is not a number";
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
                        if (!$valid) $message .= "{$attr} is not a valid email";
                    }

                    break;
                case 'string':
                    foreach ($rule[0] as $attr) {
                        $this->$attr = (string)$this->$attr;
                        if (isset($rule['max'])) {
                            if (strlen( $this->$attr) > $rule['max']) $message .= "{$attr} should be maximum {$rule['max']} characters long";
                        }
                        if (isset($rule['min'])) {
                            if (strlen( $this->$attr) < $rule['min']) $message .= "{$attr} should be minimum {$rule['min']} characters long";
                        }
                        if (isset($rule['len'])) {
                            if (strlen( $this->$attr) == $rule['len']) $message .= "{$attr} should be excactly {$rule['len']} characters long";
                        }
                    }
                    break;
                case 'safe':
                    break;
                default:
            }
        }
        return ($message=="")?'Success':$message;
    }
    public function load() {
        $this->name = $_POST['name'];
        $this->alt = $_POST['alt'];
        $this->title = $_POST['title'];
        $this->picture = "/assets/images/products/{$_POST['picture']}";
        $this->price = $_POST['price'];
        $this->currency = (empty($_POST['currency'])) ? 'SEK' : $_POST['currency'];
        $this->description = $_POST['description'];
        $this->available = $_POST['available'];
        $this->active = ($_POST['active']=='on')?1:0;
        return;
    }
    private function validateOne($field, $value) : string {
        $message = "";
        foreach ($this->rules() as $rule) {
            switch ($rule[1]) {
                case 'required':
                    if (in_array($field, $rule[0])) {
                        if (!isset($value)) $message .= "{$field} is not defined";
                    }
                    break;
                case 'integer':
                    if (in_array($field, $rule[0])) {
                        if (!is_numeric($value)) $message .= "{$field} is not a number.";
                    }
                    break;
                case 'email':
                    if (in_array($field, $rule[0])) {
                        $valid = true;
                        $field= strtolower($value);
                        if ( strpos($field, '@') ) {
                            $split = explode('@', $value);
                            $valid = (strpos($split['1'], '.') );
                        }
                        else {
                            $valid = false;
                        }
                        if (!$valid) $message .= "{$field} is not a valid email";
                    }

                    break;
                case 'string':
                    if (in_array($field, $rule[0])) {
                        $value = (string)$value;
                        if (isset($rule['max'])) {
                            if (strlen( $value) > $rule['max']) $message .= "{$field} should be maximum {$rule['max']} characters long";
                        }
                        if (isset($rule['min'])) {
                            if (strlen( $value) < $rule['min']) $message .= "{$field} should be minimum {$rule['min']} characters long";
                        }
                    }
                    break;
            }
        }
        return ($message=="")?'Success':$message;
    }

    public function update($product_id)
    {
        if (!isset($product_id) || !is_numeric($product_id) || $this->validate($_POST)!==true) return false;

        $db = static::getDB();
        $productQuery = $db->prepare("SELECT id FROM purchase_item WHERE id = ?");
        $productQuery->execute([$product_id]);
        $productFound = $productQuery->fetchColumn();

        if ($productFound) {
            $product = $db->prepare("UPDATE purchase_item
                                      SET name=:name, surname=:surname, email=:email, telephone=:telephone, dob=:dob
                                    WHERE id = :id");
             $product->execute([
                    'id' => $product_id,
                    'name' => $this->name,
                    'alt' => $this->alt,
                    'title' => $this->title,
                    'picture' => $this->picture,
                    'price' => $this->price,
                    'currency' => $this->currency,
                    'description' => $this->description,
                    'available' => $this->available
                 ]);
             return $product_id;
        }
        return false;
    }
    public function edit($product_id, $field, $value)
    {
        if (!isset($product_id) || !is_numeric($product_id)) return false;
        if (empty($field) || !isset($value)) return false;
        $message = self::validateOne($field, $value);
        if ($message!="Success") return $message;

        $db = static::getDB();
        $productQuery = $db->prepare("SELECT id FROM purchase_item WHERE id = ?");
        $productQuery->execute([$product_id]);
        $productFound = $productQuery->fetchColumn();

        if ($productFound) {
            $product = $db->prepare("UPDATE purchase_item
                                      SET {$field} = :value
                                    WHERE id = :id");
            $product->execute([
                'id' => $product_id,
                'value' => $value,
            ]);
            return "Success";
        }
        return "Saving error";
    }
    public function insert()
    {
        $message = $this->validate($_POST);
        if ($message!=="Success") return message;

        $db = static::getDB();
        $product = $db->prepare("INSERT INTO purchase_item
                                      (name, alt, title, picture, price, currency, description, available, active)
                               VALUES (:name, :alt, :title, :picture, :price, :currency, :description, :available, :active)");

        $product->execute([
                'name' => (string)$this->name,
                'alt' => (string)$this->alt,
                'title' => (string)$this->title,
                'picture' => (string)$this->picture,
                'price' => (float)$this->price,
                'currency' => (string)$this->currency,
                'description' => (string)$this->description,
                'available' => (int)$this->available,
                'active' => (int)$this->active
        ]);
        return $db->lastInsertId();
    }
    public function find($product)
    {
        $db = static::getDB();
        $product = $db->prepare("SELECT * FROM purchase_item  WHERE id = :id");
        $product->execute(['id' => $product]);
        $result = $product->fetch(PDO::FETCH_ASSOC);

        $this->id = $result['id'];
        $this->name = $result['name'];
        $this->alt = $result['alt'];
        $this->title = $result['title'];
        $this->picture = $result['picture'];
        $this->price = $result['price'];
        $this->currency = $result['currency'];
        $this->description = $result['description'];
        $this->available = $result['available'];
        $this->active = $result['active'];
    }
    public static function getProduct($product_id)
    {
        $db = static::getDB();
        $product = $db->prepare("SELECT * FROM purchase_item WHERE id = :id");
        $product->execute(['id' => $product_id]);
        $result = $product->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
    public static function findAll($active=null)
    {
        $db = static::getDB();
        if (is_null($active)) {
            $products = $db->prepare("SELECT * FROM purchase_item");
            $products->execute();
        } else {
            $products = $db->prepare("SELECT * FROM purchase_item WHERE active = :active");
            $products->execute(['active' => $active]);
        }
        return $products->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function delete($product_id)
    {
        $db = static::getDB();
        $productQuery = $db->prepare("SELECT id FROM purchase_item WHERE id = ?");
        $productQuery->execute([$product_id]);
        $productFound = $productQuery->fetchColumn();

        if ($productFound) {
            $product = $db->prepare("DELETE FROM purchase_item WHERE id = :id");
            $product->execute(['id' => $product_id]);

            return "Success";
        } else {
            return "No record found to delete";
        }
    }
    public static function asTableRow($id)
    {
        $item = self::getProduct($id);

        if (count($item) > 0) {
           $result = ($item['active']) ? "<tr>" : "<tr class='danger'>";
           $result .= "<td><input disabled class='editable' id'=name__{$item['id']}' value='{$item['name']}'/></td>";
           $result .= "<td><input disabled class='editable' id='alt__{$item['id']}' value='{$item['alt']}'/></td>";
           $result .= "<td><input disabled class='editable' id='title__{$item['id']}' value='{$item['title']}'/></td>";
           $result .= "<td><input disabled class='editable' id='picture__{$item['id']}' value='{$item['picture']}'/></td>";
           $result .= "<td>{$item['currency']}<input disabled class='editable' id='price__'{$item['id']}' value='{$item['price']}'/></td>";
           $result .= "<td><input disabled class='editable' id='description__{$item['id']}' value='{$item['description']}'/></td>";
           $result .= "<td><input disabled class='editable' id='available__{$item['id']}' value='{$item['available']}'/></td>";

           $result .= "<td><label class='switch'><input type='checkbox' class='checkboxed' id='active__{$item['id']}'";
           $result .= ($item['active']) ? "checked />" : "/>";
           $result .= "<span class='slider round'></span></label></td>";

 		   $result .= "<td><i class='fa fa-trash-o fa-2x delete_row' id='delete__{$item['id']}'></i></td>";
 		   $result .= "</tr>";
        } else {
            $result= "<tr></tr>";
        }
        return $result;
    }
    public static function getItemsFromCookie()
    {
        if (empty($_SESSION['CART'])) {
            View::redirect('/', [
                'id' => $_SESSION['ID']
            ]);
        }

        $db = static::getDB();

        $check = $db->prepare("SELECT id FROM purchase_item WHERE id = ?)");
        foreach ($_SESSION['CART'] as $key => $item) {
            $id = $check->fetchColumn($item);
            if (!isset($id)) {
                unset($_SESSION['CART'][$key]);
            }
        }
        if (empty($_SESSION['CART'])) {
                View::redirect('/', [
                    'id' => $_SESSION['ID']
                ]);
        }

        $in  = str_repeat('?,', count($_SESSION['CART']) - 1) . '?';
        $products = $db->prepare("SELECT * FROM purchase_item WHERE id IN ($in)");
        $products->execute($_SESSION['CART']);

        return $products->fetchAll(PDO::FETCH_ASSOC);
    }
}
