# Storage [![Build Status](https://travis-ci.org/fat-code/storage.svg?branch=master)](https://travis-ci.org/fat-code/storage)

## Installation
`composer install fatcode/storage`

## Quick Reference
### Connecting to database
```php
<?php declare(strict_types=1);

use FatCode\Storage\Driver\MongoDb\MongoConnection;
use FatCode\Storage\Driver\MongoDb\MongoConnectionOptions;

$connection = new MongoConnection('localhost', new MongoConnectionOptions('dbName'));
```

#### Connection options

##### Connecting with user and password

```php
<?php declare(strict_types=1);

use FatCode\Storage\Driver\MongoDb\MongoConnectionOptions;

$options = new MongoConnectionOptions('dbName', 'username', 'secret');
```

##### Setting replica

```php
<?php declare(strict_types=1);

use FatCode\Storage\Driver\MongoDb\MongoConnection;
use FatCode\Storage\Driver\MongoDb\MongoConnectionOptions;

$options = new MongoConnectionOptions('dbName', 'username', 'secret');
$options->setReplicaSet('replicaSetName');
$connection = new MongoConnection(['localhost:27017', 'localhost:27018'], $options);
```

### Creating new collection
```php
<?php
use FatCode\Storage\Driver\MongoDb\Command\CreateCollection;

$connection->execute(new CreateCollection('myCollection'));
// or simply access the collection and it will be created on runtime
$connection->myCollection;
```

### Dropping collection
```php
<?php
use FatCode\Storage\Driver\MongoDb\Command\DropCollection;

$connection->execute(new DropCollection('myCollection'));
```

### Creating new document
```php
<?php
use MongoDB\BSON\ObjectId;

$myFavouritesColors = [
    '_id' => new ObjectId(),// its always recommended to generate id manually,
                            // so the state is final before document is persisted 
    'names' => ['black', 'black', 'black']
];
$connection->myCollection->insert($myFavouritesColors);
```
