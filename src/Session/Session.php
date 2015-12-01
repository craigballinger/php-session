<?php

namespace Syntactical\Session;

use \Syntactical\Session\Storage\StorageInterface;

class Session implements \SessionHandlerInterface
{
    /** @var StorageInterface */
    protected $storage;

    /**
     * Create a Session object
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Close the storage handler
     *
     * @return bool
     */
    public function close()
    {
        return $this->storage->close();
    }

    /**
     * Close the storage handler
     *
     * @return bool
     */
    public function destroy($session_id)
    {
        return $this->storage->delete($session_id);
    }

    /**
     * Run the garbage collector to clean expired sessions
     *
     * @return bool
     */
    public function gc($maxlifetime)
    {
        return $this->storage->clean($maxlifetime);
    }

    /**
     * Open the storage handler
     *
     * @return bool
     */
    public function open($save_path, $session_name)
    {
        return $this->storage->open($save_path, $session_name);
    }

    /**
     * Read the session data out of the storage
     *
     * @return string
     */
    public function read($session_id)
    {
        return $this->storage->read($session_id);
    }

    /**
     * Write session data to storage
     *
     * @return bool
     */
    public function write($session_id, $session_data)
    {
        return $this->storage->write($session_id, $session_data);
    }
}
