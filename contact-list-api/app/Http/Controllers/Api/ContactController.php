<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\ContactService;
use Exception;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(protected ContactService $contactService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $contacts = $this->contactService->getAllContacts($request->all(), $request->get('per_page'));
            return ApiResponse::success($contacts, 'Contacts fetched');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request)
    {
        try {
            $contact = $this->contactService->createContact($request->validated(), auth()->user());
            return ApiResponse::success(new ContactResource($contact), 'Contact created', 201);
        } catch (Exception $e) {
            return ApiResponse::error('Failed to create contact', 500, [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $contact = $this->contactService->getContactById($id);
            return ApiResponse::success(new ContactResource($contact), 'Contact details');
        } catch (Exception $e) {
            return ApiResponse::error('Contact not found', 404, [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest  $request, Contact $contact)
    {
        try {
            $updatedContact = $this->contactService->updateContact($contact, $request->validated());
            return ApiResponse::success(new ContactResource($updatedContact), 'Contact updated');
        } catch (Exception $e) {
            return ApiResponse::error('Failed to update contact', 400, [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->contactService->deleteContact($id);
            return ApiResponse::success([], 'Contact deleted');
        } catch (Exception $e) {
            return ApiResponse::error('Failed to delete contact', 400, [$e->getMessage()]);
        }
    }
}
