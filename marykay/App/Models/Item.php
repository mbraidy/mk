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
    public function update($product_id)
    {
        if (!isset($product_id) || !is_numeric($product_id) || $this->validate($_POST, $this->rules())!==true) return false;

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
        $message = parent::validate([$field => $value], $this->rules());
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
        $message = $this->validate($_POST, $this->rules());
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
