# Storage

## Installation
`composer install fatcode/storage`

## Connecting to database
```php
<?php declare(strict_types=1);

use FatCode\Storage\Driver\MongoDb\MongoConnection;
use FatCode\Storage\Driver\MongoDb\MongoConnectionOptions;

$connection = new MongoConnection('localhost', new MongoConnectionOptions('dbName'));
```

### Connection options

#### Connecting with user and password

```php
<?php declare(strict_types=1);

use FatCode\Storage\Driver\MongoDb\MongoConnectionOptions;

$options = new MongoConnectionOptions('dbName', 'username', 'secret');
```

#### Setting replica

```php
<?php declare(strict_types=1);

use FatCode\Storage\Driver\MongoDb\MongoConnectionOptions;

$options = new MongoConnectionOptions('dbName', 'username', 'secret');
$options->setReplicaSet('replicaSetName');
```
