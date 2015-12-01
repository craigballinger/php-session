<?php

    use \Syntactical\Session\Session;
    use \Syntactical\Session\Storage\MySQLStorage;

    class MySQLStorageTest extends PHPUnit_Framework_TestCase
    {

        protected function setUp()
        {
            $db = include(realpath(__DIR__.'../../').'db.php'); //returns a PDO instance

            $this->storage = new MySQLStorage($db, 'sessions');
            $this->handler = new Session($this->storage);

            $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
            $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';

            session_set_save_handler($this->handler, true);
            session_start();
        }

        public function testSessionHasId()
        {
            $this->assertNotEmpty(session_id());
        }

        public function testSessionStorageIsOpen()
        {
            $this->assertTrue($this->storage->open());
        }

        public function testSessionSetsData()
        {
            $_SESSION['key'] = 'value';
            $this->assertEquals($_SESSION['key'], 'value');
        }

        public function testSessionRetrievesValue()
        {
            $this->assertEquals($_SESSION['key'], 'value');
        }

        public function testSessionMatchesStorage()
        {
            $this->assertEquals(session_encode(), $this->storage->read(session_id()));
        }

        public function testDestroyDeletesSession()
        {
            $id = session_id();
            session_destroy();
            $this->assertNull($this->storage->read($id));

        }

        public function tearDown()
        {
            session_write_close();
        }


    }