# Laravel Toolkit Skill

Ferramentas do pacote `augustomoura/laravel-toolkit`.

## 1. Busca de CEP (ViaCep)

Busca endereços brasileiros via API do ViaCep.

### Usage

```php
use AugustoMoura\LaravelToolkit\Helpers\ViaCepAPI;
use AugustoMoura\LaravelToolkit\DTO\EnderecoViaCep;

// Static usage
$endereco = ViaCepAPI::staticBuscarCep('01001000');

// Or instance
$api = new ViaCepAPI();
$endereco = $api->buscarCep('01001000');
```

### EnderecoViaCep Properties

```php
$endereco->cep;         // "01001-000"
$endereco->logradouro;   // "Praça da Sé"
$endereco->complemento;  // " lado ímpar"
$endereco->bairro;       // "Sé"
$endereco->localidade;   // "São Paulo"
$endereco->uf;           // "SP"
$endereco->ibge;         // "3550308"
$endereco->gia;          // "1004"
$endereco->ddd;         // "11"
$endereco->siafi;        // "7107"
```

### Testing with Mock

```php
$api = new ViaCepAPI();
$api->setMockResponse(200, json_encode([
    'cep' => '01001000',
    'logradouro' => 'Test Rua',
    'bairro' => 'Test Bairro',
    'localidade' => 'São Paulo',
    'uf' => 'SP'
]));

$endereco = $api->buscarCep('01001000');
```

---

## 2. Macros

### Collection Macros

```php
use Illuminate\Support\Collection;

// recursive - Transforma arrays/objetos em Collections recursivamente
collect([['a' => 1], ['b' => 2]])->recursive();

// mapToInteger - Converte todos os itens para int
collect(['1', '2', '3'])->mapToInteger(); // [1, 2, 3]

// containsAll - Verifica se todos os elementos existem na coleção
collect([1, 2, 3])->containsAll([1, 2]); // true

// keyByLabel - Indexa por label, resolvendo duplicatas automaticamente
collect([
    ['name' => 'João'],
    ['name' => 'João'],
    ['name' => 'Maria']
])->keyByLabel(fn($item) => $item['name']);
// ['João' => [...], 'João 2' => [...], 'Maria' => [...]]

// implodeWithDiffLastSeparator - "A, B e C"
collect([
    ['name' => 'A'],
    ['name' => 'B'],
    ['name' => 'C']
])->implodeWithDiffLastSeparator('name', [', ', ' e ']); // "A, B e C"

// insertAfter - Insere elemento após TARGET
collect([1, 2, 3])->insertAfter(2, 'novo'); // [1, 2, 'novo', 3]

// emptyStringsToNull - Converte strings vazias para null
collect(['a' => '', 'b' => 'x'])->emptyStringsToNull(); // ['a' => null, 'b' => 'x']

// trimStrings - Aplica trim em todas as strings
collect([' name ' => ' john '])->trimStrings();

// removeStringFromKeys - Remove substring das chaves
collect(['prefix_name' => 'value'])->removeStringFromKeys('prefix_'); // ['name' => 'value']

// prependKeys - Adiciona prefixo às chaves
collect(['name' => 'john'])->prependKeys('user_'); // ['user_name' => 'john']

// firstWhereHasMin - Retorna primeiro item onde propriedade tem menor valor
collect([['qtd' => 10], ['qtd' => 5], ['qtd' => 8]])->firstWhereHasMin('qtd'); // ['qtd' => 5]
```

### String & Stringable Macros

```php
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

// capitalizedName - Capitaliza nomes preservando conectivos
Str::of('joão da silva')->capitalizedName(); // "João da Silva"
Str::of('john and mary')->capitalizedName(); // "John and Mary"

// superTrim - Remove espaços e caracteres invisíveis
Str::superTrim(" texto  \n\r\t"); // "texto"

// wordWrapWithoutBreakingWords - Divide string em linhas sem quebrar palavras
Str::wordWrapWithoutBreakingWords("Este é um texto muito longo", 10); 
// ["Este é um", "texto", "muito", "longo"]

// removeExcessWhitespaces - Normaliza espaços
Str::removeExcessWhitespaces("texto    com   espaços"); // "texto com espaços"

// dotNotation - Converte array notation para dot
Str::dotNotation("user[name]"); // "user.name"

// titleWithSpaces - Transforma kebab case em title with spaces
Str::of('my-title-case')->titleWithSpaces(); // "My Title Case"
```

### Query Builder Macros

```php
use Illuminate\Database\query;

// whereAny - Aplica múltiplas condições com OR
Model::query()->whereAny([
    fn($q) => $q->where('status', 'active'),
    fn($q) => $q->where('featured', true),
])->get();

// whereNot - Atalho para and not
Model::query()->whereNot('status', 'deleted')->get();
```

### Test Response Macros

```php
// assertContentHtmlMatchesSelector - Verifica seletor CSS no HTML da resposta
$response->assertContentHtmlMatchesSelector('.error-message');
$response->assertContentHtmlMatchesSelector('.hidden-element', false); // Deve existir 0 elementos

// Exemplo em teste
$this->get('/pagina')
    ->assertContentHtmlMatchesSelector('form input[name="email"]')
    ->assertContentHtmlMatchesSelector('.alert-danger', false);
```

---

## 3. Traits para Testes (Pest/PHPUnit)

Use em seus testes:

```php
use Tests\TestCase;
use AugustoMoura\LaravelToolkit\Traits\MakesAssertionsForObjects;
use AugustoMoura\LaravelToolkit\Traits\MakesAssertionsForStrings;
use AugustoMoura\LaravelToolkit\Traits\MakesAssertionsForValidationRules;

class MyTest extends TestCase
{
    use MakesAssertionsForObjects;
    use MakesAssertionsForStrings;
    use MakesAssertionsForValidationRules;

    // ...
}
```

### MakesAssertionsForObjects

```php
// assertObjectHasAttributes
$obj = (object) ['name' => 'John', 'age' => 30];
$this->assertObjectHasAttributes($obj, [
    'name' => 'John',
    'age' => 30
]);
```

### MakesAssertionsForStrings

```php
// assertEqualsNormalizingSpaces - Compara strings normalizando espaços múltiplos
$this->assertEqualsNormalizingSpaces(
    "João    da Silva",
    "João da Silva"
); // Passa!
```

### MakesAssertionsForValidationRules

```php
use Illuminate\Contracts\Validation\Rule;

// Assert single value
$rule = new MyCustomRule();
$this->assertValidationRule($rule, 'valid-value', true);
$this->assertValidationRule($rule, 'invalid-value', false);

// Assert multiple values (array input => expected result)
$this->assertValidationRuleForMultipleValues($rule, [
    'valid-a' => true,
    'valid-b' => true,
    'invalid-a' => false,
    'invalid-b' => false,
]);

// Laravel 11+ ValidationRule (callback)
use Illuminate\Contracts\Validation\ValidationRule;
$laravel11Rule = new MyLaravel11Rule();
$this->assertLaravel11ValidationRule($laravel11Rule, 'value', true);
```