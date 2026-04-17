<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class ContactsController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contacts = $query->latest('status_updated_at')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('contacts.partials.table', compact('contacts'))->render(),
                'pagination' => $contacts->links()->toHtml(),
                'total' => $contacts->total(),
            ]);
        }

        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

   public function store(Request $request): JsonResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20|unique:contacts',
        'email' => 'nullable|email|max:255',
        'file' => 'nullable|file|mimes:pdf,doc,docx|max:25600',
        'status' => 'required|in:new,active,pending,cancelled,success',
    ]);

    $filePath = null;
    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('contacts', 'public');
    }

    Contact::create([
        'name' => $validated['name'],
        'phone' => $validated['phone'],
        'email' => $validated['email'],
        'file_path' => $filePath,
        'status' => $validated['status'],
        'status_updated_at' => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Contatto creato con successo']);
}

    public function show(Contact $contact)
    {
        return response()->json([
            'html' => view('contacts.partials.details', compact('contact'))->render(),
        ]);
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:contacts,phone,'.$contact->id,
            'email' => 'nullable|email|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'status' => 'required|in:new,active,pending,cancelled,success',
        ]);

        $filePath = $contact->file_path;
        if ($request->hasFile('file')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('contacts', 'public');
        }

        $contact->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'file_path' => $filePath,
            'status' => $validated['status'],
            'status_updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Contatto aggiornato con successo']);
    }

    public function updateStatus(Request $request, Contact $contact): JsonResponse
    {
        $request->validate(['status' => 'required|in:new,active,pending,cancelled,success']);

        $contact->update([
            'status' => $request->status,
            'status_updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Stato aggiornato con successo']);
    }

    public function destroy(Contact $contact): JsonResponse
    {
        if ($contact->file_path) {
            Storage::disk('public')->delete($contact->file_path);
        }

        $contact->delete(); // Soft delete

        return response()->json(['success' => true, 'message' => 'Contatto eliminato con successo']);
    }

    public function restore($id): JsonResponse
    {
        $contact = Contact::withTrashed()->findOrFail($id);
        $contact->restore();

        return response()->json(['success' => true, 'message' => 'Contatto ripristinato']);
    }
}