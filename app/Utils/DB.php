<?php

namespace App\Utils;

use PDO;

class DB {

    const DUPLICATE_ENTRY_CODE = 1062;
    const PDO_CODE_INDEX = 1;

    public static $host = DB_HOST;
    public static $dbName = DB_NAME;
    public static $username = DB_USERNAME;
    public static $password = DB_PASSWORD;

    private static function connect() {
        $pdo = new PDO('mysql:host=' . self::$host . ';dbname=' . self::$dbName . ';charset=utf8', self::$username, self::$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function query($query, $params = []) {
        $statement = self::connect()->prepare($query);
        try {
            $statement->execute($params);
        } catch (\PDOException $PDOException) {
            if(!empty($PDOException->errorInfo[self::PDO_CODE_INDEX]) && $PDOException->errorInfo[self::PDO_CODE_INDEX] == self::DUPLICATE_ENTRY_CODE) {
                return ['error' => true, 'data' => self::DUPLICATE_ENTRY_CODE];
            }
        }
        if (explode(' ', $query)[0] == 'SELECT') {
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            return ['error' => false, 'data' => $data];
        } else if (explode(' ', $query)[0] == 'INSERT') {
            return ['error' => false, 'data' => []];
        }
    }

    public static function getFirst($query, $params = []) {
        $query .= ' LIMIT 1';

        $statement = self::connect()->prepare($query);
        try {
            $statement->execute($params);
        } catch (\PDOException $PDOException) {
            return false;
        }
        if (explode(' ', $query)[0] == 'SELECT') {
            $data = $statement->fetch(PDO::FETCH_ASSOC);

            return $data;
        }
    }

    public static function get($query, $params = []) {

        $statement = self::connect()->prepare($query);
        try {
            $statement->execute($params);
        } catch (\PDOException $PDOException) {
            return false;
        }
        if (explode(' ', $query)[0] == 'SELECT') {
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        }
    }
}
