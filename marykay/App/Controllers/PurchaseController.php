<?php

namespace App\Controllers;

use App\Models\Item;
use App\Models\User;
use Core\View;
use App\Models\Profile;
use App\Models\Address;
use App\Models\Card;

/**
 * Purchase controller
 *
 * PHP version 7.2
 */
class PurchaseController extends \Core\Controller
{

    public function wishAction()
    {
        View::render('Purchase/wish.php', [
            'logged' => true,
        ]);
    }
    public function cartAction()
    {
        if (!User::getIsLogged())
            View::redirect('/User/login', [
                'id' => $_SESSION['ID']
            ]);
        else
            $profile = new Profile();
            $profile->find();

            View::render('Purchase/cart.php', [
                'logged' => true,
                'profile' => $profile,
                'address' => Address::getAddress($_SESSION['ID']),
                'cards' => Card::getCards($_SESSION['ID']),
                'items' => Item::getItemsFromCookie()
            ]);
    }
    public function executeAction() {
        //
        // TODO: Save into purchase_basket
        // TODO: Save into urchase_basket_item
        // TODO: Update available quantities
        //
        View::render('Purchase/thankyou.php', [
            'logged' => true,
        ]);

    }
    public function towishAction() {
        if (!empty($_POST) && isset($_POST['id']) ) {
            $item = $_POST['id'];
            if (in_array($item, $_SESSION['CART'])) {
                $_SESSION['CART'] = array_diff( $_SESSION['CART'], [$item] );
                $_SESSION['WISH'][] = $item;
                echo 'Success';
            } else {
                echo "Item was not originally in Cart";
            }
        } else {
            echo "No value was sent";
        }
    }
    public function disposeofAction() {
        if (!empty($_POST) && isset($_POST['id']) ) {
            $item = $_POST['id'];
            if (in_array($item, $_SESSION['CART'])) {
                $_SESSION['CART'] = array_diff( $_SESSION['CART'], [$item] );
                echo 'Success';
            } else {
                echo "Item was not originally in Cart";
            }
        } else {
            echo "No value was sent";
        }
    }
}

