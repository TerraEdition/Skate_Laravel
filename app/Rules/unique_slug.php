<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class unique_slug implements ValidationRule
{
    private $table;
    private $column;
    private $ignoreSlug;

    public function __construct($table, $column, $ignoreSlug)
    {
        $this->table = $table;
        $this->column = $column;
        $this->ignoreSlug = $ignoreSlug;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = DB::table($this->table)->where($this->column, $value)->where('slug', '!=', $this->ignoreSlug);
        if ($query->exists()) {
            $fail(__('global.unique'));
        };
    }
}
