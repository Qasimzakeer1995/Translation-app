<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TranslationService;

class TranslationController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string',
            'content' => 'required|string',
            'locale_id' => 'required|exists:locales,id',
            'tags' => 'array',
        ]);

        return response()->json(
            $this->translationService->store($data),
            201
        );
    }

    public function search(Request $request)
    {
        return response()->json(
            $this->translationService->search($request->all())
        );
    }

    public function export(Request $request)
    {
        $request->validate([
            'locale_id' => 'required|exists:locales,id',
        ]);

        return response()->json(
            $this->translationService->export($request->locale_id)
        );
    }
}
