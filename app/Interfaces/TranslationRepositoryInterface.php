<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;
use App\Models\Translation;

interface TranslationRepositoryInterface
{
    public function createOrUpdate(array $data): Translation;

    public function search(array $filters): Collection;

    public function exportByLocale(int $localeId): Collection;
}
