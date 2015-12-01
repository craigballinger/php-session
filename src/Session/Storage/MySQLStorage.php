<?php

namespace Syntactical\Session\Storage;

class MySQLStorage implements StorageInterface
{
    /** @var PDO */
    protected $db;

    /** @var string */
    protected $table;

    /**
     * Create a MySQLStorage object
     *
     * @param PDO $pdo
     * @param string $table
     */
    public function __construct(\PDO $db, $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    /**
     * Forge a MySQLStorage object with just connection info
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $db
     */
    static public function createWithDSN($host, $db, $username, $password, $table)
    {
        $db = new \PDO("mysql:host=$host,dbname=$db", $username, $password);
        $instance = new self($db);
        $instance->setTable($table);
        return $instance;
    }

    /**
     * Confirm the DB connection is made
     *
     * @return bool
     */
    public function open()
    {
        if($this->db){
            return true;
        }

        return false;
    }

    /**
     * No implementation in this storage type
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Read the session data out of MySQL
     *
     * @param int $id
     * @return string
     */
    public function read($id)
    {
        $stmt = $this->db->prepare("SELECT data FROM {$this->getTable()} WHERE id = :id");
        $stmt->execute(array(':id' => $id));
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['data'];
    }

    /**
     * Persist the session data to MySQL
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function write($id, $data)
    {

        $sql = "REPLACE INTO {$this->getTable()} (id, ip, last_activity, user_agent, data)
                VALUES (:id, :ip, :last_activity, :user_agent, :data)";

        $stmt = $this->db->prepare($sql);

        $ip = ip2long($_SERVER['REMOTE_ADDR']);

        return $stmt->execute(array(
                ':id' => $id,
                ':last_activity' => time(),
                ':user_agent' => substr($_SERVER['HTTP_USER_AGENT'], 0, 255),
                ':ip' => $ip ? $ip : 0,
                ':data' => $data
                ));
    }

    /**
     * Delete the session data from MySQL
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->getTable()} WHERE id = :id");
        return $stmt->execute(array(':id' => $id));
    }

    /**
     * Delete expired sessions from MySQL
     *
     * @param int $id
     * @return bool
     */
    public function clean($age)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->getTable()} WHERE last_activity < :time");
        return $stmt->execute(array(':time' => $this->getExpired($age)));
    }

    /**
     * Set the MySQL table name that stores the session data
     *
     * @param string $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }
    /**
     * Get the MySQL table name that stores the session data
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Get a timestamp of the oldest valid session
     *
     * @return int
     */
    public function getExpired($age)
    {
        return time() - $age;
    }
}
