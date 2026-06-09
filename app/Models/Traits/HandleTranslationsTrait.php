<?php

namespace App\Models\Traits;

trait HandleTranslationsTrait
{
    public array $translations = [];

    public static function fillTranslations(array $data, $record): array
    {
        foreach (['en', 'ar'] as $locale) {
            $data[$locale]['name'] = $record->translate($locale)?->name ?? '';
        }

        $data['price'] = $record->price;
        $data['has_discussion_for_delivery'] = (bool) $record->has_discussion_for_delivery;

        return $data;
    }

    public static function extractTranslations(array $data): array
    {
        $translations = [];
        foreach (['en', 'ar'] as $locale) {
            if (isset($data[$locale])) {
                $translations[$locale] = $data[$locale];
                unset($data[$locale]);
            }
        }

        return compact('data', 'translations');
    }

    public function mutateTranslations(): \Closure
    {
        return function (array $data): array {
            $result = static::extractTranslations($data);
            $this->translations = $result['translations'];

            return $result['data'];
        };
    }

    public function saveTranslations($record): void
    {
        foreach ($this->translations as $locale => $translation) {
            $record->translateOrNew($locale)->fill($translation)->save();
        }
    }

    public static function saveEditTranslations($record, array $data): void
    {
        foreach (['en', 'ar'] as $locale) {
            if (isset($data[$locale])) {
                $record->translateOrNew($locale)->fill($data[$locale])->save();
            }
        }
    }
}
