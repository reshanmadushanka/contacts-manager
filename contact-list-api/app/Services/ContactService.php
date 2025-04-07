<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\User;
use App\Repositories\Contact\ContactRepository;

class ContactService
{
    /**
     * The ContactService class is responsible for handling business logic related to contacts.
     * It interacts with the ContactRepository to perform CRUD operations on contact data.
     */
    public function __construct(protected ContactRepository $contactRepository)
    {
    }

    /**
     * Get all contacts.
     * This method retrieves all contacts based on the provided filters and pagination.
     */
    public function getAllContacts(array $filters = [], int $perPage = 15, $userId = null)
    {
        return $this->contactRepository->all($filters, $perPage, $userId);
    }

    /**
     * Get a contact by ID.
     * This method retrieves a contact by its unique identifier.
     */
    public function getContactById($id)
    {
        return $this->contactRepository->find($id);
    }

    /**
     * Create a new contact.
     * This method creates a new contact using the provided data and associates it with the given user.
     */
    public function createContact($data, User $user)
    {
        return $this->contactRepository->create($data, $user);
    }

    /**
     * Update an existing contact.
     * This method updates the contact with the provided data.
     */
    public function updateContact(Contact $contact, $data)
    {
        return $this->contactRepository->update($contact, $data);
    }

    /**
     * Delete a contact by ID.
     * This method deletes the contact with the specified ID.
     */
    public function deleteContact($id)
    {
        return $this->contactRepository->delete($id);
    }
}
