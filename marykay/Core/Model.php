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
    protected static function validate($data, $rules)
    {
        $message = "";
        foreach ($rules as $rule) {
            switch ($rule[1]) {
                case 'required':
                    foreach ($rule[0] as $attr) {
                        if (!isset($data[$attr])) $message .= "{$attr} is not defined";
                    }
                    break;
                case 'integer':
                    foreach ($rule[0] as $attr) {
                        if (!is_numeric($data[$attr])) $message .= "{$attr} is not a number";
                    }
                    break;
                case 'email':
                    foreach ($rule[0] as $attr) {
                        $valid = true;
                        $data[$attr] = strtolower($data[$attr]);
                        if ( strpos($data[$attr], '@') ) {
                            $split = explode('@', $data[$attr]);
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
                        $data[$attr] = (string)$data[$attr];
                        if (isset($rule['max'])) {
                            if (strlen( $data[$attr]) > $rule['max']) $message .= "{$attr} should be maximum {$rule['max']} characters long";
                        }
                        if (isset($rule['min'])) {
                            if (strlen( $data[$attr]) < $rule['min']) $message .= "{$attr} should be minimum {$rule['min']} characters long";
                        }
                        if (isset($rule['len'])) {
                            if (strlen( $data[$attr]) == $rule['len']) $message .= "{$attr} should be excactly {$rule['len']} characters long";
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
}
