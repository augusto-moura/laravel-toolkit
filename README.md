# Laravel Toolkit
Adds macros to various Laravel classes and provides helper classes, as well as supercharged base classes.

## Requirements
* PHP 7.3+
* Laravel 8.0+

## Installation
```
composer require augusto-moura/laravel-toolkit
```

## Available tools

### Collection Macros

- `mapToInteger`
```php
collect(['1', '5', '-8'])->mapToInteger();
//[1, 5, -8]
```

- `removeStringFromKeys`
```php
$collection = collect([
	'id' => 1,
	'##name##' => '#keep#',
	'#before' => 'value',
	'after#' => 'other',
]);
$collection->removeStringFromKeys('#');
/*
[
	'id' => 1,
	'name' => '#keep#',
	'before' => 'value',
	'after' => 'other',
],
*/
```

- `trimStrings`
```php
collect([' a ', ' a', 'a ', 'a', ' abc def ', 100, [1,2,3]])
	->trimStrings();
//['a', 'a', 'a', 'a', 'abc def', 100, [1,2,3]]
```

- `mapAlternatingKeyAndValue`
```php
$collection = collect([
	'a' => 'b',
	'c' => 'd',
]);
$collection->mapAlternatingKeyAndValue();
//['a', 'b', 'c', 'd']
```

- `containsAll`
```php
$collection = collect([1, 2, 3, 4]);

$collection->containsAll([1, 2, 3]); //true
$collection->containsAll([1, 2, 3, 4]); //true
$collection->containsAll([1, -1, 2, 3]); //false
$collection->containsAll([1, 2, 3, 4, 5]); //false
```

- `prependKeys`
```php
$collection = collect([
	'aaa' => 1,
	'bbb' => 2,
	
]);
$collection->prependKeys('##');
/*
[
	'##aaa' => 1,
	'##bbb' => 2, 
]
*/
```

- `insertAfter`
```php
collect(['a', 'b', 'c'])
	->insertAfter('b', 'pause');
//['a', 'b', 'pause', 'c']
```

- `recursive`
```php
$collection = collect([
	0 => 'a', 
	1 => 'b', 
	2 => [1,2,3]
]);
$collection->recursive();
/* 
Collection([
	0 => 'a', 
	1 => 'b', 
	2 => Collection([1,2,3])
])
*/
```

- `emptyStringsToNull`
```php
collect(['foo', '', 'bar', ''])
	->emptyStringsToNull();
//['foo', null, 'bar', null]
```

- `firstWhereHasMin`
```php
$collection = collect([
	['name' => 'John', 'age' => 25],
	['name' => 'Jane', 'age' => 30],
	['name' => 'Bob', 'age' => 25],
	['name' => 'Joseph', 'age' => 29],
]);
$collection->firstWhereHasMin('age');
//['name' => 'John', 'age' => 25];
```

- `implodeWithDiffLastSeparator`
```php
$collection = collect([
	['name' => 'John', 'age' => 25],
	['name' => 'Jane', 'age' => 30],
	['name' => 'Bob', 'age' => 25],
	['name' => 'Joseph', 'age' => 29],
]);
$collection->implodeWithDiffLastSeparator('name', [', ', ' and ']);
//John, Jane, Bob and Joseph

collect(['John', 'Jane', 'Bob', 'Joseph'])
	->implodeWithDiffLastSeparator([', ', ' and ']);
//John, Jane, Bob and Joseph
```