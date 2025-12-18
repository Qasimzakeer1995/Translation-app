<?php

namespace App\Services;

use App\Interfaces\TranslationRepositoryInterface;
use App\Models\Tag;

class TranslationService
{
    protected $translationRepository;

    public function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }


    public function store(array $data)
    {
        $translation = $this->translationRepository->createOrUpdate($data);

   
        if (!empty($data['tags'])) {
            $tagIds = Tag::whereIn('name', $data['tags'])->pluck('id');
            $translation->tags()->sync($tagIds);
        }

        return $translation->load('tags');
    }


    public function search(array $filters)
    {
        return $this->translationRepository->search($filters);
    }

  
    public function export(int $localeId): array
    {
        return $this->translationRepository->exportByLocale($localeId)->toArray();
    }
}
