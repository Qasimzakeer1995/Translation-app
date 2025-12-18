<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;

class PopulateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:populate {count=100000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the translations table with a large number of records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');
        $locales = Locale::all();
        $tags = Tag::all();

        $this->info("Populating {$count} translations...");

        DB::beginTransaction();

        try {
            for ($i = 0; $i < $count; $i++) {
                $locale = $locales->random();
                $translation = Translation::create([
                    'key' => 'key_' . $i,
                    'content' => 'Content for key ' . $i,
                    'locale_id' => $locale->id,
                ]);

                $translation->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );
                
                if ($i % 1000 == 0) { 
                    $this->info("Inserted {$i} records...");
                }
            }

            DB::commit();
            $this->info("Successfully populated {$count} translations.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error populating translations: " . $e->getMessage());
        }
    }
}
