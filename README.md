# PHP Session Handler
This library is a drop in replacement for PHP's native session handling. It exposes an interface for alternate storage repositories using the SessionHandler class introduced in 5.4.

##Usage
Install the library using composer
```
composer install syntactical/session
```

##Using a PDO MySQL session store

Create the table:
```sql
CREATE TABLE `sessions` (
  `id` varchar(40) NOT NULL DEFAULT '0',
  `ip` int(10) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Bootstrap the library:
```php
$db = new PDO('mysql:host=your.db.host;dbname=db','username','password');
$table = 'sessions';
$storage = new MySQLStorage($db, $table);
$handler = new Session($storage);

session_set_save_handler($handler, true);
session_start();
```

You can now use the $\_SESSION superglobal and session_* functions as you would natively, but sessions will be stored in MySQL rather than the filesystem.
