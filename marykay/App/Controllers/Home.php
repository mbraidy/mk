<?php

namespace App\Controllers;

use App\Models\Item;
use App\Models\User;
use Core\View;

/**
 * Home controller
 *
 * PHP version 7.2
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::render('Home/index.php', [
            'logged' => User::getIsLogged(),
            'items' => Item::findAll(1)
        ]);
    }
}
