LikePDO
=======
Similar database library to PDO

The LikePDO library is an alternative for those who need to use a database library similar to PDO without the need for native extension. 
Is fully developed in PHP, using standard features such as MSSQL library and the like, without use the native PDO.

=======

## Features

- Well organized and structured files.
- Same methods, constants and syntax of the native PDO.
- Use the PDO::* constants.
- Defines the constant PDO::* if the PDO extension is not installed.
- 
=======

## Installing

Here's a very simple way to install:

1. Use [Composer](http://getcomposer.org) to install Whoops into your project:

    ```bash
    composer require erickmcarvalho/likepdo
    ```

1. Simple example

    ```php
    $dbh = new \LikePDO\LikePDO("mssql:host=127.0.0.1;dbname=Project", "sa", "123456");
    $stmt = $dbh->prepare("SELECT * FROM User WHERE CodUser = ?");
    $stmt->bindValue(1, 1, PDO::PARAM_INT);
    $stmt->execute();

    $fetch = $stmt->fetch(PDO::FETCH_OBJ);
    ```

All documentation can be based on the [PHP manual](http://php.net/manual/en/class.pdo.php) on the PDO.

=======
## Available Drivers

LikePDO currently supports the following drivers:

- Microsoft SQL Server (mssql): Requires the mssql extension

## Requirements

- PHP 5.3 (>=)
- Mssql Library (for Microsoft SQL Server driver)
