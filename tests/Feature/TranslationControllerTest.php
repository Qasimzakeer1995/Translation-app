<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class TranslationControllerTest extends TestCase
{
 public function test_create_translation_success()
    {
       
        $translationData = [
            'key' => 'greeting',
            'locale' => 'en',
            'content' => 'Hello, World!',
            'tags' => 'greeting, home'
        ];

    
        $response = $this->json('POST', '/api/translations', $translationData);

        $response->assertStatus(Response::HTTP_CREATED)
                 ->assertJson([
                     'message' => 'Translation created successfully.',
                 ]);


        $this->assertDatabaseHas('translations', [
            'key' => 'greeting',
            'locale' => 'en',
            'content' => 'Hello, World!',
        ]);
    }

    public function test_create_translation_validation_error()
    {
  
        $translationData = [
            'locale' => 'en',
            'content' => 'Hello, World!',
            'tags' => 'greeting, home'
        ];


        $response = $this->json('POST', '/api/translations', $translationData);


        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                 ->assertJsonValidationErrors(['key']);
    }

  

    public function test_get_translation_by_key_success()
    {
 
        Translation::create([
            'key' => 'greeting',
            'locale' => 'en',
            'content' => 'Hello, World!',
            'tags' => 'greeting, home'
        ]);

 
        $response = $this->json('GET', '/api/translations/greeting');


        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'key' => 'greeting',
                     'locale' => 'en',
                     'content' => 'Hello, World!',
                 ]);
    }

    
    public function test_get_translation_by_key_not_found()
    {

        $response = $this->json('GET', '/api/translations/nonexistent-key');

  
        $response->assertStatus(Response::HTTP_NOT_FOUND)
                 ->assertJson([
                     'message' => 'Translation not found.',
                 ]);
    }

    public function test_list_translations_success()
    {
      
        Translation::create(['key' => 'greeting', 'locale' => 'en', 'content' => 'Hello, World!', 'tags' => 'greeting']);
        Translation::create(['key' => 'farewell', 'locale' => 'en', 'content' => 'Goodbye!', 'tags' => 'farewell']);

     
        $response = $this->json('GET', '/api/translations');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonCount(2) // Ensure that there are two translations
                 ->assertJsonStructure([
                     '*' => [
                         'key', 'locale', 'content', 'tags',
                     ]
                 ]);
    }

    public function test_list_translations_with_tag_filter()
    {
        
        Translation::create(['key' => 'greeting', 'locale' => 'en', 'content' => 'Hello', 'tags' => 'greeting']);
        Translation::create(['key' => 'farewell', 'locale' => 'en', 'content' => 'Goodbye', 'tags' => 'farewell']);

      
        $response = $this->json('GET', '/api/translations', ['tag' => 'greeting']);


        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonCount(1) 
                 ->assertJson([
                     0 => [
                         'key' => 'greeting',
                         'tags' => 'greeting',
                     ]
                 ]);
    }


    public function test_export_translations_success()
    {

        Translation::create(['key' => 'greeting', 'locale' => 'en', 'content' => 'Hello', 'tags' => 'greeting']);
        Translation::create(['key' => 'farewell', 'locale' => 'en', 'content' => 'Goodbye', 'tags' => 'farewell']);

       
        $response = $this->json('GET', '/api/translations/export');

       
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'greeting' => 'Hello',
                     'farewell' => 'Goodbye',
                 ]);
    }
}
