<?php

namespace Core;

class BaseModel implements SimpleRepositoryInterface
{
    protected $connection;
    protected $table;
    protected $timestamp = true;

    public function __construct()
    {
        /*
         * ?
         */
        if ($this->connection === null) {
            $db               = new Db();
            $this->connection = $db->connect();
        }
    }

    public function findAll()
    {
        $sql = 'SELECT * FROM '.$this->table;
        $sth = $this->connection->prepare($sql);

        if ($sth->execute()) {
            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        }
        throw new \PDOException('the query has not been executed.');
    }

    public function find($id)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE '.$this->table.'.id = :id';
        $sth = $this->connection->prepare($sql);
        $sth->bindParam(':id', $id, \PDO::PARAM_INT);
        
        if ($sth->execute()) {
            return $sth->fetch(\PDO::FETCH_ASSOC);
        }
        throw new \PDOException('the query has not been executed.');
    }

    public function create(array $data)
    {

        $keys   = array_keys($data);
        $values = array_values($data);

        $sql = 'INSERT INTO '.$this->table.' ('.implode(',', $keys).') VALUES (:'.implode(', :',
                $keys).')';
        $sth = $this->connection->prepare($sql);
        foreach ($data as $k => $v) {
            $sth->bindValue(':'.$k, $v);
        }

        if ($sth->execute()) {
            return $this->connection->lastInsertId();
        }
        throw new \PDOException('the query has not been executed.');
    }

    public function update($id, array $data)
    {
        $pairs = [];

        foreach ($data as $key => $val) {
            $pairs[] = $key.' = :'.$key;
        }

        $sql = 'UPDATE '.$this->table.' SET '.implode(',', $pairs).' WHERE id = :id';
        $sth = $this->connection->prepare($sql);
        $sth->bindParam(':id', $id, \PDO::PARAM_INT);
        foreach ($data as $k => $v) {
            $sth->bindValue(':'.$k, $v);
        }
        $sth->bindValue(':'.$k, $v);

        if ($sth->execute()) {
            return true;
        }
        throw new \PDOException('the query has not been executed.');
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM '.$this->table.' WHERE id = :id';
        $sth = $this->connection->prepare($sql);
        $sth->bindParam(':id', $id, \PDO::PARAM_INT);

        if ($sth->execute()) {
            return true;
        }
        throw new \PDOException('the query has not been executed.');
    }

    public function raw($sql)
    {
        $sth = $this->connection->prepare($sql);
        if ($sth->execute()) {
            return true;
        }
        throw new \PDOException('the query has not been executed.');
    }
}
