<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
class AuthControllerTest extends TestCase
{
   public function test_login_success()
    {
     
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

       
        $credentials = ['email' => 'test@example.com', 'password' => 'password'];

     
        $response = $this->json('POST', '/api/login', $credentials);

    
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'token',
            ]);
    }

    public function test_login_failure()
    {
     
        $credentials = ['email' => 'nonexistent@example.com', 'password' => 'wrong-password'];


        $response = $this->json('POST', '/api/login', $credentials);


        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'message' => 'Invalid credentials', 
            ]);
    }

    
    public function test_register_success()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ];


        $response = $this->json('POST', '/api/register', $userData);


        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'token',
            ]);
    }

   
    public function test_register_validation_error()
    {
        $userData = [
            'name' => 'John Doe',
            'password' => 'password',
        ];


        $response = $this->json('POST', '/api/register', $userData);

  
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']); 
    }

    public function test_register_existing_user()
    {
        $existingUser = User::factory()->create([
            'email' => 'existing.user@example.com'
        ]);

        $userData = [
            'name' => 'Existing User',
            'email' => 'existing.user@example.com',
            'password' => 'password',
        ];
        $response = $this->json('POST', '/api/register', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The email has already been taken.',
            ]);
    }
}
