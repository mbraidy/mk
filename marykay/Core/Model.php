<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base model
 *
 * PHP version 7.2
 */
abstract class Model
{
//     protected static function rules()
//     {
//     }

    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
//     protected static function validate() {
//         foreach ($this->rules() as $rule) {
//             switch ($rule[1]) {
//                 case 'required':
//                     foreach ($rule[0] as $attr) {
//                         if (!isset($this->$attr)) return "{$attr} is not defined";
//                     }
//                     break;
//                 case 'integer':
//                     foreach ($rule[0] as $attr) {
//                         if (!is_numeric($this->$attr)) return "{$attr} is not a number";
//                     }
//                     break;
//                 case 'email':
//                     foreach ($rule[0] as $attr) {
//                         $valid = true;
//                         $this->$attr = strtolower($this->$attr);
//                         if ( strpos($this->$attr, '@') ) {
//                             $split = explode('@', $this->$attr);
//                             $valid = (strpos($split['1'], '.') );
//                         }
//                         else {
//                             $valid = false;
//                         }
//                         if (!$valid) return "{$attr} is not a valid email";
//                     }

//                     break;
//                 case 'string':
//                     foreach ($rule[0] as $attr) {
//                         $this->$attr = (string)$this->$attr;
//                         if (isset($rule['max'])) {
//                             if (strlen( $this->$attr) > $rule['max']) return "{$attr} should be maximum {$rule['max']} characters long";
//                         }
//                         if (isset($rule['min'])) {
//                             if (strlen( $this->$attr) < $rule['min']) return "{$attr} should be minimum {$rule['min']} characters long";
//                         }
//                     }
//                     break;
//             }
//         }
//         return true;
//     }

}
