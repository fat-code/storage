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
use FatCode\Storage\Driver\MongoDb\MongoConnection;

/** @var MongoConnection $connection */
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
use FatCode\Storage\Driver\MongoDb\MongoConnection;

/** @var MongoConnection $connection */
$connection->dropCollection('myCollection');
```

#### Listing collections
The following code will return array of collection names.
```php
<?php
use FatCode\Storage\Driver\MongoDb\MongoConnection;

/** @var MongoConnection $connection */
$connection->listCollections();
```

#### Creating new document
Following example creates new document containing `names` field. Once operation is executed boolean flag is returned
to indicate either success (`true`) of failure (`false`)
```php
<?php
use MongoDB\BSON\ObjectId;
use FatCode\Storage\Driver\MongoDb\MongoConnection;

$myFavouritesColors = [
    '_id' => new ObjectId(),// its always recommended to generate id manually,
                            // so the state is final before document is persisted 
    'names' => ['black', 'black', 'black']
];
/** @var MongoConnection $connection */
$success = $connection->myCollection->insert($myFavouritesColors);
```

#### Updating existing document
Document can only be updated if it contains `_id` field, please check the following example:
```php
<?php
use MongoDB\BSON\ObjectId;
use FatCode\Storage\Driver\MongoDb\MongoConnection;

$myFavouritesColors = [
    '_id' => new ObjectId('5c929b31cb406a2cd4106bb2'),
    'names' => ['black', 'black', 'black', 'white']
];
/** @var MongoConnection $connection */
$connection->myCollection->update($myFavouritesColors);
```
Once document is updated the boolean flag is returned indicating whether operation was successful.

#### Removing document from the collection
Document can be removed, by simply passing its id to `$collection->delete`:
```php
<?php
use MongoDB\BSON\ObjectId;
use FatCode\Storage\Driver\MongoDb\MongoConnection;

/** @var MongoConnection $connection */
$connection->myCollection->delete(new ObjectId('5c929b31cb406a2cd4106bb2'));
```

#### Removing documents matching given filter
```php
<?php
use FatCode\Storage\Driver\MongoDb\MongoConnection;

/** @var MongoConnection $connection */
$connection->myCollecion->findAndDelete(['name' => 'Bob']);
```
Above example deletes all documents that contains property `name` with assigned string value `Bob`.

#### Upserting document
Upsert should be used in case when document presence in collection is unknown (we dont know if document exists in database already or not).
Upsert either creates document if it does not exists in collection or updates existing one, like in update operation `_id`
field must be defined during upsert operation.
```php
<?php
use MongoDB\BSON\ObjectId;
use FatCode\Storage\Driver\MongoDb\MongoConnection;

$myFavouritesColors = [
    '_id' => new ObjectId('5c929b31cb406a2cd4106bb2'),
    'names' => ['black', 'black', 'black', 'white']
];
/** @var MongoConnection $connection */
$connection->myCollection->upsert($myFavouritesColors);
```

#### Working with commands
All above examples are just sugar syntax to simplify daily tasks, when you deal with complex queries is always better
to use mongo commands (commands live in `FatCode\Storage\Driver\MongoDb\Command` namespace).
The following list contains all built-in supported commands:
- `FatCode\Storage\Driver\MongoDb\Command\Aggregate`
- `FatCode\Storage\Driver\MongoDb\Command\CreateCollection`
- `FatCode\Storage\Driver\MongoDb\Command\DropCollection`
- `FatCode\Storage\Driver\MongoDb\Command\ListCollections`
- `FatCode\Storage\Driver\MongoDb\Command\Find`
- `FatCode\Storage\Driver\MongoDb\Command\Insert`
- `FatCode\Storage\Driver\MongoDb\Command\Remove`
- `FatCode\Storage\Driver\MongoDb\Command\RemoveById`
- `FatCode\Storage\Driver\MongoDb\Command\Update`
 
 List will grow with time, but there is no limitation if comes to using user defined commands.
 
##### Executing command
Each command can be executed simply by passing its instance to `execute` method. The method always returns a cursor.
```php
<?php
use MongoDB\BSON\ObjectId;
use FatCode\Storage\Driver\MongoDb\MongoConnection;
use FatCode\Storage\Driver\MongoDb\Command\Find;

/** @var MongoConnection $connection */
$cursor = $connection->execute(new Find('myCollection', ['_id' => new ObjectId('5c929b31cb406a2cd4106bb2')]));
var_dump($cursor->current()); // Will dump document retrieved by its id
```

#### Working with cursor
`\FatCode\Storage\Driver\MongoDb\MongoCursor` is returned by `execute` method each time it performs successful command execution. 
The cursor fetches results from the handle, and can be iterated through like any `iterable` object. Cursor does not
caches the results so current's iteration result lives till next iteration, to prevent this scenario you can 
for example assign each iteration result in an array like in the following example:
```php
<?php
use FatCode\Storage\Driver\MongoDb\MongoConnection;
use FatCode\Storage\Driver\MongoDb\Command\Find;

/** @var MongoConnection $connection */
$cursor = $connection->execute(new Find('users', ['name' => 'Tom']));

$myUserList = [];
/** @var array $user */
foreach ($cursor as $user) {
    $myUserList[] = $user;
}
```
