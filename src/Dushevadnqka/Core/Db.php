<?php

namespace Core;

class Db
{
    public function connect()
    {
        $dbName = getenv('DB_DATABASE');
        $dbHost = getenv('DB_HOST');
        /* Connect to a MySQL database using driver invocation */
        $dsn      = 'mysql:dbname='.$dbName.';host='.$dbHost;
        $username     = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');

        try {
            $dbh = new \PDO($dsn, $username, $password);
        } catch (\PDOException $e) {
            echo 'Connection failed: '.$e->getMessage();
        }

        return $dbh;
    }
}
