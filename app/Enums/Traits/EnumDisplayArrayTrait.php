<?php

namespace App\Enums\Traits;

trait EnumDisplayArrayTrait
{
    public function defaultDisplayValue(): string
    {
        return str($this->value)
            ->snake()
            ->replace('_', ' ')
            ->title()
            ->value();
    }

    public function displayValue(): string
    {
        return $this->defaultDisplayValue();
    }

    public static function displayArray(array $displayOnly = []): array
    {
        $displayValues = count($displayOnly) > 0 ? $displayOnly : self::cases();

        if (! method_exists(self::class, 'cases')) {
            return [];
        }

        $cases = collect($displayValues);

        return $cases
            ->mapWithKeys(fn ($case): array => [
                $case->value => $case->displayValue(),
            ])
            ->toArray();
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
