<?php
namespace AugustoMoura\LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Converte o valor para string por segurança antes do replace
        $value = str((string) $value)->upper()->toString();

        // 1. Limpa e converte para maiúsculo
        $cnpj = strtoupper(preg_replace('/[^A-Z0-9]/', '', $value));

        // 2. Valida o formato: exatos 12 caracteres alfanuméricos seguidos de 2 numéricos
        if (!preg_match('/^[A-Z0-9]{12}[0-9]{2}$/', $cnpj)) {
            $fail('O :attribute informado não é um CNPJ válido.');
            return;
        }

        // 3. Regra opcional: invalidar repetições clássicas do formato antigo (opcional no formato alfa)
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            $fail('O :attribute informado não é um CNPJ válido.');
            return;
        }

        // 4. Cálculo do 1º Dígito Verificador
        $pesos1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma1 = 0;
        for ($i = 0; $i < 12; $i++) {
            // A função ord() pega o valor ASCII do caractere
            $soma1 += (ord($cnpj[$i]) - 48) * $pesos1[$i];
        }
        $dv1 = $soma1 % 11 < 2 ? 0 : 11 - ($soma1 % 11);

        if ((int) $cnpj[12] !== $dv1) {
            $fail('O :attribute informado não é um CNPJ válido.');
            return;
        }

        // 5. Cálculo do 2º Dígito Verificador
        $pesos2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma2 = 0;
        for ($i = 0; $i < 13; $i++) {
            $soma2 += (ord($cnpj[$i]) - 48) * $pesos2[$i];
        }
        $dv2 = $soma2 % 11 < 2 ? 0 : 11 - ($soma2 % 11);

        if ((int) $cnpj[13] !== $dv2) {
            $fail('O :attribute informado não é um CNPJ válido.');
        }
    }
}