# Storage [![Build Status](https://travis-ci.org/fat-code/storage.svg?branch=master)](https://travis-ci.org/fat-code/storage) [![Maintainability](https://api.codeclimate.com/v1/badges/537840eb6a24da002d3b/maintainability)](https://codeclimate.com/github/fat-code/storage/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/537840eb6a24da002d3b/test_coverage)](https://codeclimate.com/github/fat-code/storage/test_coverage)

## Installation
`composer install fatcode/storage`

## MongoDriver Quick Reference
### Connecting to database
```php
<?php

use FatCode\Storage\Driver\MongoDb\MongoConnection;
use FatCode\Storage\Driver\MongoDb\MongoConnectionOptions;

$connection = new MongoConnection('localhost', new MongoConnectionOptions('dbName'));
```

#### Connection options

##### Connecting with user and password

```php
<?php

use FatCode\Storage\Driver\MongoDb\MongoConnectionOptions;

$options = new MongoConnectionOptions('dbName', 'username', 'secret');
```

##### Setting replica

```php
<?php

use FatCode\Storage\Driver\MongoDb\MongoConnection;
use FatCode\Storage\Driver\MongoDb\MongoConnectionOptions;

$options = new MongoConnectionOptions('dbName', 'username', 'secret');
$options->setReplicaSet('replicaSetName');
$connection = new MongoConnection(['localhost:27017', 'localhost:27018'], $options);
```

### Operating on collections
 > Storage recommends using `MongoDB\BSON\ObjectId` as id for all of your documents, but it is not enforced, 
 > so any value can be passed to methods that accepts `$id` property. 
 > It is also recommended to implement `__toString` interface if you are planning passing any objects as an id.

#### Creating new collection
```php
<?php

$connection->createCollection('myCollection');
// or simply access the collection and it will be created on runtime
$connection->myCollection;
```
Collection can be created in two ways:
- Executing `CreateCollection` command which accepts collection name as first argument and collocation as second one
- Directly accessing the collection and inserting new document

#### Dropping collection

```php
<?php
$connection->dropCollection('myCollection');
```

#### Listing database collections
The following code will return array of collection names.
```php
<?php
$connection->listCollecitons();
```

#### Creating new document

Following example creates new document containing `names` field. Once operation is executed boolean flag is returned
to indicate either success (`true`) of failure (`false`)
```php
<?php
use MongoDB\BSON\ObjectId;

$myFavouritesColors = [
    '_id' => new ObjectId(),// its always recommended to generate id manually,
                            // so the state is final before document is persisted 
    'names' => ['black', 'black', 'black']
];
$success = $connection->myCollection->insert($myFavouritesColors);
```

#### Updating existing document
Document can only be updated if it contains `_id` field, please check the following example:
```php
<?php
use MongoDB\BSON\ObjectId;

$myFavouritesColors = [
    '_id' => new ObjectId('5c929b31cb406a2cd4106bb2'),
    'names' => ['black', 'black', 'black', 'white']
];
$connection->myCollection->update($myFavouritesColors);
```
Once document is updated the boolean flag is returned indicating whether operation was successful.

#### Removing document from the collection
Document can be removed, by simply passing its id to `$collection->delete`:
```php
<?php
use MongoDB\BSON\ObjectId;

$connection->myCollection->delete(new ObjectId('5c929b31cb406a2cd4106bb2'));
```

#### Upserting document
Upsert should be used in case when document presence in collection is unknown (we dont know if document exists in database already or not).
Upsert either creates document if it does not exists in collection or updates existing one, like in update operation `_id`
field must be defined during upsert operation.
```php
<?php
use MongoDB\BSON\ObjectId;

$myFavouritesColors = [
    '_id' => new ObjectId('5c929b31cb406a2cd4106bb2'),
    'names' => ['black', 'black', 'black', 'white']
];
$connection->myCollection->upsert($myFavouritesColors);
```
