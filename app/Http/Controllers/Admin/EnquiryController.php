<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Enquiry::class);

        $query = Enquiry::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $enquiries = $query->latest()->paginate(15)->withQueryString();

        return view('admin.enquiries.index', compact('enquiries'));
    }

    public function show(Enquiry $enquiry)
    {
        $this->authorize('view', $enquiry);

        if ($enquiry->status === 'pending') {
            $enquiry->update(['status' => 'contacted']);
        }

        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $this->authorize('update', $enquiry);

        $data = $request->validate([
            'status' => ['required', 'in:pending,contacted,completed,cancelled'],
        ]);

        $enquiry->update($data);

        return redirect()->route('admin.enquiries.show', $enquiry)->with('success', 'Enquiry status updated.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $this->authorize('delete', $enquiry);

        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('success', 'Enquiry deleted.');
    }
}
