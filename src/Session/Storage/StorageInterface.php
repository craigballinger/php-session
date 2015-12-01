<?php

namespace Syntactical\Session\Storage;

interface StorageInterface
{
    /**
     * Prepare the storage implementation for use
     *
     * @return bool
     */
    public function open();

    /**
     * Cleanup/close the storage implementation after use
     *
     * @return bool
     */
    public function close();

    /**
     * Read the session data out of the storage implementation
     *
     * @param int $id
     * @return string
     */
    public function read($id);

    /**
     * Persist the session data to the storage implementation
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function write($id, $data);

    /**
     * Delete the session data from the storage implementation
     *
     * @param int $id
     * @return bool
     */
    public function delete($id);

    /**
     * Delete expired sessions from the storage implementation
     *
     * @param int $id
     * @return bool
     */
    public function clean($age);
}
