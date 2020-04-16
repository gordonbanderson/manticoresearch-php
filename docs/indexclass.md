# Index Class

It's wrapper on top of the Client that simplifies working with an Index.

Index provides all the operations which can be executed on an index.


```php
$config = ['host' => '127.0.0.1', 'port' => '9308'];
$client = new Client($config);
$index = new Index($client,'myindex');
```
Second argument is not required, the index name can be also set with setName().



### setName()

Allows setting the index name. 

```php
$index->setName('myindex');
```

### create()

Creates the index, accepts:

- fields - array with columns
- settings - optional list of index settings
- silent - default is false, if true, no error is returned if an index with same name already exists

Example:

```php
 $index->create(['title' => ['type' => 'text'], 'gid' => ['type' => 'int'], 'label' => ['type' => 'string'], 'tags' => ['type' => 'multi'], 'props' => ['type' => 'json']], []);
```

### addDocument()

Inserts a new document in the index.
Expects:
- array of values
- document id
Example:

```php
$index->addDocument([
            'title' => 'find me',
            'gid' => 1,
            'label' => 'not used',
            'tags' => [1, 2, 3],
            'props' => [
                'color' => 'blue',
                'rule' => ['one', 'two']
            ]
        ], 1);
```

### replaceDocument()

Replace an existing document in the index.
Expects:
- array of values
- document id

Example:

```php
$index->addDocument([
            'title' => 'find me',
            'gid' => 1,
            'label' => 'not used',
            'tags' => [1, 2, 3],
            'props' => [
                'color' => 'blue',
                'rule' => ['one', 'two']
            ]
        ], 1);
```

### updateDocument()

Update attributes for a given document by Id.

Expects:
-  array with key pairs of attribute names and values
-  document id

```php
$index->addDocument([
            'title' => 'find me',
            'gid' => 1,
            'label' => 'not used',
            'tags' => [1, 2, 3],
            'props' => [
                'color' => 'blue',
                'rule' => ['one', 'two']
            ]
        ], 1);
```


### updateDocuments()

It can update multiple documents that match a condition.

Expects:
-  array with key pairs of attribute names and values
-  query expression

Example:

```php
$index->updateDocuments(['price'=>100],['match'=>['*'=>'apple']]);
```

### deleteDocument()

Deletes a document. Expects one argument as the document id.

Example:

```php
$index->deleteDocument(100);
```

### deleteDocuments()

Deletes documents using a query expression.

Example:

```php
$index->deleteDocuments(['match'=>['*'=>'apple']]);
```

### search()

It's a wrapper to a Search::search(). It return a [Search](searchclass.md) object instance.
It accept either a full-text query string or a [BoolQuery](query.md#boolquery) class.
It returns a [ResultSet](searchresults.md#resultset-object) object.

```php
 $result = $index->search('find')->get();
```
Note that on every call a new instance of Search class is created, therefor search conditions are not carried over multiple calls.
 

### drop()

Drop the index.
If `silent` is true, no error will be returned if the index doesn't exists.

```php
$index->drop($silent=false);
```
### describe()

Returns schema of the index

```php
$index->describe();
```

### status()

Provides information about the index.

```php
$index->status();
```

### truncate()

Empty the index of data.

```php
$index->truncate();
```

### optimize()

Performs optimization on index (not available for distributed type).

If `sync` is set to true, the command will wait for optimize to finish, otherwise the engine will sent the optimize in background and return success message back.

```php
$index->optimize($sync=false);
```

### flush()

Performs Real-Time index flushing to disk.

```php
$index->flush();
```

### flushramchunk()

Performs Real-Time index flushing of the RAM chunk to disk. In general this operation is run before doing an optimize.

```php
$index->flushramchunk();
```

### alter()

Alter index schema. Please note that currently `text` type is not supported by this command.

Parameters:

- operation type -  `add` or `drop`
- name of the attribute 
- type of the attribute (only for `add`)


```php
$index->alter($operation,$name,$type);
```


### keywords()

Returns tokenization for an input string.

Parameters:

- input string
- options. For more information about the available options check https://docs.manticoresearch.com/latest/html/sphinxql_reference/call_keywords_syntax.html


```php
$index->keywords($query, $options);
```

```php
$index->alter($operation,$name,$type);
```


### suggest()

Returns suggestions for a give keyword.

Parameters:

- the keyword
- options. For more information about the available options check https://docs.manticoresearch.com/latest/html/sphinxql_reference/call_qsuggest_syntax.html

```php
$index->keywords($query, $options);
```