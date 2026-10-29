<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /** Enquiry types defined by the schema. */
    private const TYPES = ['test_drive', 'service', 'parts', 'general'];

    public function index(Request $request)
    {
        $this->authorize('viewAny', Enquiry::class);

        $search = $request->filled('search') ? trim($request->input('search')) : null;
        $type   = $request->filled('enquiry_type') ? $request->input('enquiry_type') : null;
        $status = $request->filled('status') ? $request->input('status') : null;

        // Summary cards reflect the searched/typed scope but ignore the status
        // filter — the cards themselves are the status breakdown.
        $scope = Enquiry::query();
        $this->applyFilters($scope, $search, $type, null);

        $statusCounts = (clone $scope)
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $stats = [
            'total'     => (int) $statusCounts->sum(),
            'pending'   => (int) ($statusCounts['pending'] ?? 0),
            'contacted' => (int) ($statusCounts['contacted'] ?? 0),
            'completed' => (int) ($statusCounts['completed'] ?? 0),
        ];

        $query = Enquiry::query();
        $this->applyFilters($query, $search, $type, $status);

        $enquiries = $query->latest()->paginate(15)->withQueryString();

        $enquiryTypes = self::TYPES;

        return view('admin.enquiries.index', compact('enquiries', 'stats', 'enquiryTypes'));
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', Enquiry::class);

        $search = $request->filled('search') ? trim($request->input('search')) : null;
        $type   = $request->filled('enquiry_type') ? $request->input('enquiry_type') : null;
        $status = $request->filled('status') ? $request->input('status') : null;

        $query = Enquiry::query();
        $this->applyFilters($query, $search, $type, $status);

        $filename = 'enquiries-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
            fputcsv($out, ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Type', 'Status', 'Message', 'Received']);

            $query->latest()->chunk(200, function ($enquiries) use ($out) {
                foreach ($enquiries as $enquiry) {
                    fputcsv($out, [
                        $enquiry->id,
                        $enquiry->name,
                        $enquiry->email,
                        $enquiry->phone,
                        $enquiry->subject,
                        str_replace('_', ' ', $enquiry->enquiry_type),
                        $enquiry->status,
                        $enquiry->message,
                        optional($enquiry->created_at)->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function applyFilters(Builder $query, ?string $search, ?string $type, ?string $status): void
    {
        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('enquiry_type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }
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
