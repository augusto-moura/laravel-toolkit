# Laravel Toolkit
Adds macros to various Laravel classes and provides helper classes, as well as supercharged base classes.

## Requirements
* PHP 7.3+
* Laravel 8.0+

## Available tools

### Collection Macros
- `mapToInteger`
```php
collect(['1', '5', '-8'])->mapToInteger(); //[1, 5, -8]
```