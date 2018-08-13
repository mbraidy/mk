<?php

namespace App\Controllers;

use App\Models\User;
use Core\View;

/**
 * Document controller
 *
 * PHP version 7.2
 */
class Document extends \Core\Controller
{
    public function joinAction()
    {
        View::render('Document/member.php', [
            'logged' => User::getIsLogged()
        ]);
    }
    public function payAction()
    {
        View::render('Document/purchase.php', [
            'logged' => User::getIsLogged()
        ]);
    }
}
