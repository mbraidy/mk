<?php

namespace App\Controllers;

use App\Models\Item;
use App\Models\User;
use Core\View;

/**
 * Item controller
 *
 * PHP version 7.2
 */
class ItemController extends \Core\Controller
{

    public function viewAction()
    {
        if (!User::getIsLogged() && $_SESSION['ROLE']!="superman") return false;

        View::render('item/view.php', [
            'items' => Item::findAll(),
            'logged' => true,
            'role' => 'superman'
        ]);
    }
    public function updateAction() {
        if (!User::getIsLogged() || !User::getIsAdmin()) return false;
        if (empty($_POST)) {
             View::render('Item/update.php', [
                 'items' => Item::findAll(),
                 'username' => $_SESSION['USER'],
                 'logged' => true,
             ]);
         } else {
             $item = new Item();
             $item->load();
             $message = $item->insert();
             if (is_numeric($message) && $message > 0 ) {
                 echo json_encode(['operation' => 'Success', 'newArray' => Item::asTableRow($message)]);
             } else {
                 echo json_encode(['operation' => $message]);
             }
         }
     }
    public function handleAction() {
         if (!$_SERVER['REQUEST_METHOD'] == 'PUT') return false;
         parse_str(file_get_contents("php://input"), $post_vars);
         $id = $post_vars['id'];
         if (!isset($_SESSION['WISH'])) $_SESSION['WISH'] = [];
         if (!isset($_SESSION['CART'])) $_SESSION['CART'] = [];

         $_SESSION['WISH'] = array_diff($_SESSION['WISH'], [$id]);
         $_SESSION['CART'] = array_diff($_SESSION['CART'], [$id]);
         if ($post_vars['todo']==='add')
            $_SESSION[$post_vars['place']][] = $post_vars['id'];
         return true;
     }
     public function editAction() {
         if (!$_SERVER['REQUEST_METHOD'] == 'PUT') return false;
         parse_str(file_get_contents("php://input"), $post_vars);

         $item = new Item();

         echo $item->edit($post_vars['id'],$post_vars['field'],$post_vars['value']);
     }
     public function deleteAction() {
         if (!$_SERVER['REQUEST_METHOD'] == 'DELETE') return false;
         parse_str(file_get_contents("php://input"), $post_vars);

         if (isset($post_vars['id']) ) {
             $id = $post_vars['id'];
             if ($id > 0) {
                 $message = Item::delete($post_vars['id']);
                 if ($message == "Success") {
                     echo 'Success';
                 } else {
                     echo $message;
                 }
             } else {
                 echo "Key cannot be zero!";
             }
         } else {
             echo "Missing key";
         }
     }
}