<?php

namespace barephrame\src\lib\connection;

use barephrame\src\lib\components\Utils;
use Error;
use pdo;
use stdClass;


class DBResult {
    protected $stmt;
    public stdClass $params;
    public function __construct($stmt) {
        $this->stmt = $stmt;
        $this->params = new stdClass();
    }

    public function Debug():void {
        $this->stmt->debugDumpParams();
    }

    public function Execute():array {
        $result = $this->stmt->execute((array)$this->params);
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function close() {
        $this->stmt = null;
    }
}

/**
 *   @example:
 *   $db = new Connection();
 *   $sql = $db->newQuery('select * from test where id = :id');
 *   $sql->params->id = 1;
 *   $data = $sql->Execute();
 *   $sql->close();
 * 
 */

class Mysql {
    protected $connection;
    protected $prepared;
    protected DBResult|null $result;
    public function __construct(string $path) {
        $ini = Utils::INI($path);
        if($ini === false) throw new Error("Could not find or parse .ini");
        $db_info = $ini['mysql_database'];
        $qString = sprintf('mysql:host=%s;dbname=%s', $db_info['host'], $db_info['database']);
        $this->connection = new pdo($qString, $db_info['user'], $db_info['password']);
    }

    public function newQuery(string $query):DBResult {
        return new DBResult($this->connection->prepare($query));

    }

    public function __destruct() {
        $this->connection = null;
    }
}