<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ContactApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        return $user;
    }

    /**
     * A basic feature test example.
     */
    public function test_create_contact(): void
    {
        $this->authenticate();

        $data = [
            'name' => 'Reshan Wickramasinghe',
            'email' => 'reshan@test.com',
            'phone_number' => '0711380025',
        ];

        $response = $this->postJson('/api/contacts', $data);
        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Contact created'])
            ->assertJsonFragment(['name' => 'Reshan Wickramasinghe']);

        $this->assertDatabaseHas('contacts', ['email' => 'reshan@test.com']);
    }

    /**
     * Test for getting all contacts
     */
    public function test_get_all_contacts(): void
    {
        $this->authenticate();

        Contact::factory()->count(15)->create(['added_by' => auth()->user()->id]);
        $response = $this->getJson('/api/contacts?page=1&per_page=8');
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Contacts fetched'])
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'phone_number',
                            'added_by',
                        ],
                    ],
                    'links',
                ],
            ]);
        $response->assertJson([
            'data' => [
                'current_page' => 1,
                'per_page' => 8,
            ],
        ]);
    }

    /**
     * Test for getting a single contact
     */
    public function test_get_contact_by_id(): void
    {
        $this->authenticate();

        $contact = Contact::factory()->create(['added_by' => auth()->user()->id]);
        $response = $this->getJson('/api/contacts/'.$contact->id);
        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $contact->id])
            ->assertJsonFragment(['name' => $contact->name]);
    }

    /**
     * Test for getting a single contact
     */
    public function test_update_contact()
    {
        $this->authenticate();

        $contact = Contact::factory()->create([
            'added_by' => auth()->user()->id,
        ]);

        $update = [
            'name' => 'Contact Test',
            'email' => $contact->email,
            'phone_number' => $contact->phone_number,
        ];

        $response = $this->putJson("/api/contacts/{$contact->id}", $update);
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Contact Test'])
            ->assertJsonFragment(['message' => 'Contact updated'])
            ->assertJsonFragment(['id' => $contact->id]);
        $this->assertDatabaseMissing('contacts', ['name' => $contact->name]);

        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'name' => 'Contact Test']);
    }

    /**
     * Test for deleting a contact
     */
    public function test_delete_a_contact()
    {
        $this->authenticate();

        $contact = Contact::factory()->create();

        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Contact deleted']);
        $this->assertDatabaseHas('contacts', ['id' => $contact->id]);
        $this->assertNotNull(Contact::withTrashed()->find($contact->id)->deleted_at);
        $this->assertNull(Contact::find($contact->id));
    }
}
