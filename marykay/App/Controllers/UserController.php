<?php

namespace App\Controllers;

use App\Models\CardType;
use App\Models\City;
use App\Models\Country;
use App\Models\Item;
use App\Models\User;
use App\Models\Profile;
use App\Models\Address;
use App\Models\Card;
use \Core\View;

/**
 * User controller
 *
 * PHP version 7.2
 */
class UserController extends \Core\Controller
{
    /**
     * Show the index page
     *
     * @return void
     */
    public function loginAction()
    {
        if (empty($_POST)) {
            View::render('User/login.php', [
                'logged' => false,
            ]);
            return true;
        }
        switch ($message=User::getlogin()) {
            case 'SUCCESS':
                View::render('Home/index.php', [
                    'logged' => true,
                    'items' => Item::findAll(1)
                ]);
                break;
            case 'WAIT':
                View::render('User/login.php', [
                    'logged' => false,
                    'waittime' => $_SESSION['SINCE'] + 1800 - time()
                ]);
                break;
            case 'WRONG':
                View::render('User/login.php', [
                    'logged' => false,
                ]);
                break;
            case 'SIGNUP':
                View::render('Home/signup.php', [
                    'logged' => false,
                ]);
                break;
            default:
                return $message;
        }
    }
    public function signupAction()
    {
        if (empty($_POST)) {
            View::render('User/signup.php', [
                'logged' => false,
            ]);
            return true;
        }
        switch ($message=User::getSignup()) {
            case 'SUCCESS':
                View::render('User/login.php', [
                    'logged' => false,
                ]);
                break;
            default:
                return $message;
        }
    }
    public function userFoundAction()
    {
        echo User::getNewUser();
    }
    public function logoutAction()
    {
        switch ($message=User::getLogout()) {
            case 'LOGOUT':
                View::render('Home/index.php', [
                    'logged' => false,
                    'items' => Item::findAll(1)
                ]);
                break;
            default:
                return $message;
        }
    }
    public function viewAction()
    {
        if (!User::getIsLogged()) return false;
        $profile = new Profile();
        $profile->find();

        $address = Address::getAddress( $_SESSION['ID']);

        $cards = Card::getCards($_SESSION['ID']);

        View::render('User/view.php', [
            'profile' => $profile,
            'address' => $address,
            'cards' => $cards,
            'username' => $_SESSION['USER'],
            'logged' => true,
        ]);
    }
    public function updateAction() {
         if (!User::getIsLogged()) return false;
         $profile = new Profile();
         $profile->find();
         if (empty($_POST)) {
             $address = Address::getAddress($profile->userID);

             $cards = Card::getCards($profile->userID);

             $cardTypes = CardType::asOptions();

             View::render('User/update.php', [
                 'profile' => $profile,
                 'address' => $address,
                 'cards' => $cards,
                 'cardTypes' => $cardTypes,
                 'cities' => City::asOptions(),
                 'countries' => Country::asOptions(),
                 'username' => $_SESSION['USER'],
                 'logged' => true,
             ]);
         } else {
             $profile->load();
             if ($profile->save() ) {
                 View::redirect('/user/profile', [
                     'id' => $_SESSION['ID']
                 ]);
             } else {
                 View::render('User/update.php', [
                     'profile' => $profile
                 ]);
             }
         }
     }
     public function cityList()
     {
         if (empty($_GET) || empty($_GET['countryCD'])) return false;
         echo City::asOptions($_GET['countryCD']);
     }
}