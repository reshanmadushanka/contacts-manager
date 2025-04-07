<?php

namespace App\Repositories\Contact;

use App\Models\Contact;
use App\Models\User;

/**
 * Interface for the Contact Repository
 *
 * This interface defines the methods that should be implemented by any class
 * that interacts with the contact data source. It provides a contract for
 * creating, reading, updating, and deleting contact records.
 */
interface ContactRepositoryInterface
{
    /**
     * Get all contacts
     */
    public function all(array $filters, int $perPage, $userId = null);

    /**
     * Get a contact by ID
     */
    public function find(int $id);

    /**
     * Create a new contact
     */
    public function create(array $data, User $user);

    /**
     * Update an existing contact
     */
    public function update(Contact $contact, array $data);

    /**
     * Delete an existing contact
     */
    public function delete(int $id);
}
