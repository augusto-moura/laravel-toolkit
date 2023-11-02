# Laravel Toolkit
Adds macros to various Laravel classes and provides helper classes, as well as supercharged base classes.

## Requirements
* PHP 7.3+
* Laravel 8.0+

## Installation
In the `composer.json` file in the root of your Laravel project, add the following section:
```
"repositories": [
	{
		"type": "vcs",
		"url": "https://github.com/augusto-moura/laravel-toolkit"
	}
],
```

Now run the command:
```
composer require augusto-moura/laravel-toolkit
```

## Available tools

<details>
<summary>Collection Macros</summary>
  
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
</details>

<details>
<summary>String and Stringable Macros</summary>

- `titleWithSpaces`
```php
Str::titleWithSpaces('three fine words');
Str::of('three fine words')->titleWithSpaces();
//'Three Fine Words'

Str::titleWithSpaces('threeWordsTogether');
Str::of('threeWordsTogether')->titleWithSpaces();
//'Three Words Together'
```

- `superTrim`
```php
$string = "\t\n\r \0\x0B\xC2\xA0text 123\t\n \r\0\x0B\xC2\xA0";

Str::superTrim($string)
Str::of($string)->superTrim();
//'text 123'
```

- `wordWrapWithoutBreakingWords`
```php
Str::wordWrapWithoutBreakingWords('1234 12345', 5);
Str::of('1234 12345')->wordWrapWithoutBreakingWords(5);
//['1234', '12345']

Str::wordWrapWithoutBreakingWords('1 2 12345', 5);
//['1 2', '12345']

Str::wordWrapWithoutBreakingWords('123456 12345', 5);
// \LengthException thrown
```

- `removeExcessWhitespaces`
```php
Str::removeExcessWhitespaces('     abc     def    ');
Str::of("   abc  \n\t  def  ")->removeExcessWhitespaces();
//' abc def '
```
</details>

<details>
<summary>Query Builder Macros</summary>

- `whereAny`
```php
User::query()
	->whereAny([
		function($query){
			$query->hasRole('Administrator');
		},
		function($query){
			$query->where('is_owner', true);
		},
	])
	->get();
/*
select * from users where (
	*condition in local scope*
	or
	is_owner = true
)
*/
``` 

- `whereNot`
```php
User::query()
	->whereNot(function($query){
		$query->hasRole('Administrator');
	})
	->get();
//select * from users where not ( *condition in local scope* )
``` 
</details>

<details>
<summary>Traits for test classes</summary>

- `MakesAssertionsForValidationRules`
```php
$rule = new Cpf; //implements Illuminate\Contracts\Validation\Rule interface

$this->assertValidationRule(
	$rule,
	'40101887078',
	true
);

$this->assertValidationRuleForMultipleValues(
	$rule, 
	[
		'40101887078' => true,
		'401.018.870-78' => true,
		'00000000000' => false,
		'000.000.000-00' => false,
		'4010.018.870-78' => false,
		'401.1018.870-78' => false,
		'401.018.2870-78' => false,
		'401.018.870-878' => false,
	]
);
```

- `MakesAssertionsForObjects`
```php
$this->assertObjectHasAttributes($personObject, [
	'name' => 'John Doe',
	'region_id' => 2,
]);
```

- `MakesAssertionsForStrings`
```php
$this->assertEqualsNormalizingSpaces(
	'abcd     
efgh ijkl',
	'abcd efgh ijkl'
);
```
</details>

<details>
<summary>TestResponse Macros</summary>

- `assertContentHtmlMatchesSelector`
```php
//in test class
$response = $this->get('/login');

//assert that selector has at least one match in HTML
$response->assertContentHtmlMatchesSelector('input[name=login]') 
	->assertContentHtmlMatchesSelector('input[name=password]');

//assert that selector has no match in HTML
$response->assertContentHtmlMatchesSelector('button.logout-button', false);
``` 
</details>

<details>
<summary>Validation rules</summary>

- `Cpf`
```php
//in controller
use AugustoMoura\LaravelToolkit\Rules\Cpf;

request()->validate([
	'cpf' => [new Cpf],
]);

//"40101887078" -> passes
//"401.018.870-78" -> passes
//"401.018.870-789" -> fails
```

- `Cep`
```php
//in controller
use AugustoMoura\LaravelToolkit\Rules\Cep;

request()->validate([
	'cep' => [new Cep],
]);

//"12345-123" -> passes
//"1234H-123" -> fails
//"12345123" -> fails
```

- `HtmlNotEmpty`
```php
//in controller
use AugustoMoura\LaravelToolkit\Rules\HtmlNotEmpty;

request()->validate([
	'message' => [new HtmlNotEmpty],
]);

//" " -> fails
//" <br> " -> fails
//"<p> <br> </p>" -> fails
//"<p> First line<br>Second line </p>" -> passes
```

- `MaxCharactersinHtml`
```php
//in controller
use AugustoMoura\LaravelToolkit\Rules\MaxCharactersinHtml;

request()->validate([
	'message' => [new MaxCharactersinHtml(3)],
]);

//"123" -> passes
//"<p>123</p>" -> passes
//"1234" -> fails
//"<p>1234</p>" -> fails
```

- `MaxWordsinHtml`
```php
//in controller
use AugustoMoura\LaravelToolkit\Rules\MaxWordsinHtml;

request()->validate([
	'message' => [new MaxWordsinHtml(2)],
]);

//" abc def " -> passes
//"<p> abc def </p>" -> passes
//"abc def ghi" -> fails
//"<p> abc def ghi </p>" -> fails
```

- `HourAndMinute`
```php
//in controller
use AugustoMoura\LaravelToolkit\Rules\HourAndMinute;

request()->validate([
	'message' => [new HourAndMinute],
]);

//"01:01" -> passes
//"23:59" -> passes
//"24:00" -> fails
//"4:0" -> fails
//"1200" -> fails
```
</details>

<details>
<summary>Mailables</summary>

- `SimpleEmail`
```php
use AugustoMoura\LaravelToolkit\Mail\SimpleEmail;

Mail::to('example@email.com')->queue(new SimpleEmail(
	'E-mail title', 
	'Body of the e-mail.', 
	['path/to/attachment', 'path/to/attachment2']
));
```
</details>

<details>
<summary>Blade directives</summary>

- `@can` (supercharges `@can` with policy info)
```blade
@can('edit', $post) 
	Success: {{ $policyMessage }}
@endcan
<br>
@can('edit', $post) 
	You can edit the post: {{ $policyResponse->message() }}
@endcannot
```

- `@cannot` (supercharges `@cannot` with policy info)
```blade
@cannot('edit', $post) 
	Error: {{ $policyMessage }}
@endcan
<br>
@cannot('edit', $post) 
	You cannot edit the post: {{ $policyResponse->message() }}
@endcannot
```
</details>

<details>
<summary>Helper classes</summary>

- `ViaCepAPI`
```php
use AugustoMoura\LaravelToolkit\Helpers\ViaCepAPI;

$viaCepAPI = new ViaCepAPI;
$addressObject = $viaCepApi->buscarCep('70100000');
//EnderecoViaCep: {"cep": "70100-000","logradouro": "Praça dos Três Poderes","complemento": "","bairro": "Zona Cívico-Administrativa","localidade": "Brasília","uf": "DF","ibge": "5300108","gia": "","ddd": "61","siafi": "9701"}
```

- `LaravelToolkit` (general helper functions)
```php
use AugustoMoura\LaravelToolkit\Helpers\LaravelToolkit;

$var = [
	'maxValue' => 123,
	'phones' => [
		'12345678',
		'98764532',
	],
]
$varAsString = LaravelToolkit::exportVar($var);
//[maxValue: 123 | phones: ["1234567" | "98764532"]]
```
</details>

<details>
<summary>Value Objects</summary>

- `Cpf`
```php
$cpf = new Cpf('40101887078');
$cpf->formatado(); //401.018.870-78
$cpf->apenasNumeros(); //40101887078

$cpf = new Cpf('not a cpf string'); //throws \InvalidArgumentException
```
</details>