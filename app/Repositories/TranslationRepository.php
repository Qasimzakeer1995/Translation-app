<?php

namespace App\Repositories;

use App\Models\Translation;
use App\Interfaces\TranslationRepositoryInterface;
use Illuminate\Support\Collection;

class TranslationRepository implements TranslationRepositoryInterface
{
    public function createOrUpdate(array $data): Translation
    {
        return Translation::updateOrCreate(
            [
                'key' => $data['key'],
                'locale_id' => $data['locale_id'],
            ],
            [
                'content' => $data['content'],
            ]
        );
    }

    public function search(array $filters): Collection
    {
        $query = Translation::query()->with(['tags', 'locale']);

        if (!empty($filters['key'])) {
            $query->where('key', 'like', '%' . $filters['key'] . '%');
        }

        if (!empty($filters['content'])) {
            $query->where('content', 'like', '%' . $filters['content'] . '%');
        }

        if (!empty($filters['tags'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('name', $filters['tags']);
            });
        }

        return $query->limit(1000)->get();
    }

    public function exportByLocale(int $localeId): Collection
    {
        return Translation::where('locale_id', $localeId)
            ->select('key', 'content')
            ->get()
            ->pluck('content', 'key');
    }
}
