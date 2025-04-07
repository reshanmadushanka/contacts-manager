<?php

namespace App\Repositories\Contact;

use App\Models\Contact;
use App\Models\User;

class ContactRepository implements ContactRepositoryInterface
{
    /**
     * Get all contacts
     */
    public function all(array $filters = [], int $perPage = 15, $userId = null)
    {
        try {
            $query = Contact::query();
            $query->select('id', 'name', 'email', 'phone_number', 'added_by')
                ->when($userId, function ($q) use ($userId) {
                    $q->where('added_by', $userId);
                });

            if (! empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('name', 'like', '%'.$filters['search'].'%')
                        ->orWhere('email', 'like', '%'.$filters['search'].'%')
                        ->orWhere('phone_number', 'like', '%'.$filters['search'].'%');
                });
            }

            return $query->paginate($perPage);
        } catch (\Illuminate\Database\QueryException $e) {
            throw new \Exception('Database error while retrieving contacts: '.$e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Unexpected error while retrieving contacts: '.$e->getMessage());
        }
    }

    /**
     * Get a contact by ID
     */
    public function find(int $id)
    {
        try {
            return Contact::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Contact not found with ID: '.$id);
        } catch (\Exception $e) {
            throw new \Exception('Error while finding contact: '.$e->getMessage());
        }
    }

    /**
     * Create a new contact
     */
    public function create(array $data, User $user)
    {
        try {
            return $user->contacts()->create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            throw new \Exception('Database error while creating contact: '.$e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Unexpected error while creating contact: '.$e->getMessage());
        }
    }

    /**
     * Update an existing contact
     */
    public function update(Contact $contact, array $data)
    {
        try {
            $contact->update($data);

            return $contact;
        } catch (\Illuminate\Database\QueryException $e) {
            throw new \Exception('Database error while updating contact: '.$e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Unexpected error while updating contact: '.$e->getMessage());
        }
    }

    /**
     * Delete an existing contact
     */
    public function delete(int $id)
    {
        try {
            $contact = Contact::find($id);
            if (! $contact) {
                throw new \Exception('Contact not found or already deleted.');
            }

            return $contact->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            throw new \Exception('Database error while deleting contact: '.$e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Error while deleting contact: '.$e->getMessage());
        }
    }
}
