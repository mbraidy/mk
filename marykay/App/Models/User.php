<?php

namespace App\Models;

use PDO;

/**
 * User model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, name FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getLogin()
    {
        if (empty($_POST) || empty($_POST['username']) || empty($_POST['password']))
            return false;

        if ( $_SESSION['ATTEMPT'] >=3 ) {
            if ( time() - $_SESSION['SINCE'] < 1800 ) {
               return "WAIT";
            } else {
               $_SESSION['ATTEMPT'] = 0;
            }
        }
        $db = static::getDB();
        $username = $_POST['username'];
        $found = $db->prepare("SELECT id
                                 FROM user
                                WHERE username = :username");
        $found->execute(['username' => $username]);
        $iid = $found->fetchColumn();
        if (!$iid) {
            ++$_SESSION['ATTEMPT'];
            $_SESSION['SINCE'] = time();
            echo "SIGNUP";
            return;
        }

        $password = $_POST['password'];
        $role = $db->prepare("SELECT user.*, CONCAT(user_profile.name, ' ', user_profile.surname) AS Fullname
                                 FROM user
                                LEFT JOIN user_profile on user_profile.userID = user.id
                                WHERE username = :username
                                  AND password = :password");
        $role->execute(['username' => $username, 'password' => $password]);
        $cursor = $role->fetch(PDO::FETCH_ASSOC);
        if (!isset($cursor) || $cursor===false) {
            ++$_SESSION['ATTEMPT'];
            $_SESSION['SINCE'] = time();
            echo "WRONG";
            return;
        }

        $_SESSION['USER'] = $username;
        $_SESSION['NAME'] = $cursor['Fullname'];
        $_SESSION['ID'] = $cursor['id'];
        $_SESSION['LOGGED'] = true;
        $_SESSION['SINCE'] = time();
        $_SESSION['ATTEMPT'] = 0;
        $_SESSION['ROLE'] = $cursor['role'];

        return 'SUCCESS';
    }

    public static function getSignup()
    {
        if (empty($_POST) || empty($_POST['username']) || empty($_POST['password']))
            return false;

            $db = static::getDB();
            $username = $_POST['username'];
            $found = $db->prepare("SELECT id
                                     FROM user
                                    WHERE username = :username");
            $found->execute(['username' => $username]);
            if ($found->fetchColumn()) {
                echo "FOUND";
                return false;
            }

            $password = $_POST['password'];
            $user = $db->prepare("INSERT INTO
                                     user (username, password, role)
                                   VALUES (:username, :password, 'customer')");
            $user->execute(['username' => $username, 'password' => $password]);

            $_SESSION['USER'] = $username;
            $_SESSION['NAME'] = "Guest";
            $_SESSION['ID'] = $user->lastInsertId();
            $_SESSION['LOGGED'] = false;
            $_SESSION['SINCE'] = 0;
            $_SESSION['ATTEMPT'] = 0;
            $_SESSION['ROLE'] = '';
            return 'SUCCESS';
    }
    public static function getLogout()
    {
            $_SESSION['USER'] = '__GUEST__';
            $_SESSION['NAME'] = "Guest";
            $_SESSION['ID'] = null;
            $_SESSION['LOGGED'] = false;
            $_SESSION['SINCE'] = 0;
            $_SESSION['ATTEMPT'] = 0;
            $_SESSION['ROLE'] = '';
            return 'LOGOUT';
    }

    public static function getNewUser()
    {
        if (empty($_GET) || empty($_GET['username']))
            return "empty";

            $db = static::getDB();
            $username = $_GET['username'];
            $found = $db->prepare("SELECT id
                                     FROM user
                                    WHERE username = :username");
            $found->execute(['username' => $username]);
            return $found->fetchColumn();
    }

    public static function getIsLogged()
    {
        return ( !empty($_SESSION) && $_SESSION['LOGGED'] );
    }
    public static function getIsAdmin()
    {
        return ( !empty($_SESSION) && $_SESSION['ROLE']==='superman' );
    }
}
