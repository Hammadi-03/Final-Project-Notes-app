<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Label;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $settings = Auth::user()->settings ?? [];
        $sortOrder = ($settings['add_to_bottom'] ?? true) ? 'asc' : 'desc';

        $query = Auth::user()->notes()->active();

        if ($sortOrder === 'asc') {
            $query->oldest();
        } else {
            $query->latest();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $notes      = $query->get();
        $pinned     = $notes->where('is_pinned', true);
        $unpinned   = $notes->where('is_pinned', false);
        $totalNotes = Auth::user()->notes()->count();

        return view('notes.index', compact('notes', 'pinned', 'unpinned', 'totalNotes'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'color'   => ['nullable', 'string', 'in:default,blue,green,yellow,red,purple'],
            'image'   => ['nullable', 'image', 'max:4096'],
            'is_archived' => ['nullable', 'boolean'],
            'label_ids' => ['nullable', 'array'],
            'label_ids.*' => ['exists:labels,id']
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('notes', 'public');
        }

        $note = Auth::user()->notes()->create([
            'title'   => $request->filled('title') ? $data['title'] : 'Untitled',
            'content' => $data['content'] ?? '',
            'color'   => $data['color'] ?? 'default',
            'image'   => $imagePath,
            'is_archived' => $request->has('is_archived') ? (bool)$request->is_archived : false,
        ]);

        if (!empty($request->label_ids)) {
            $note->labels()->sync($request->label_ids);
        }

        $msg = $note->is_archived ? 'Note created and archived.' : 'Note created.';

        return redirect()->back()
            ->with('success', $msg);
    }

    public function edit(Note $note)
    {
        Gate::authorize('update', $note);
        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        Gate::authorize('update', $note);

        $data = $request->validate([
            'title'   => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'color'   => ['nullable', 'string', 'in:default,blue,green,yellow,red,purple'],
            'image'   => ['nullable', 'image', 'max:4096'],
        ]);

        $updateData = [
            'title' => $data['title'] ?? '',
            'content' => $data['content'] ?? '',
            'color' => $data['color'] ?? $note->color,
        ];

        if ($request->hasFile('image')) {
            if ($note->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($note->image);
            }
            $updateData['image'] = $request->file('image')->store('notes', 'public');
        } elseif ($request->input('remove_image') == '1') {
            if ($note->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($note->image);
            }
            $updateData['image'] = null;
        }

        $note->update($updateData);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'note' => $note
            ]);
        }

        return redirect()->route('notes.index')
            ->with('success', 'Note updated successfully!');
    }

    public function destroy(Note $note)
    {
        Gate::authorize('delete', $note);
        $note->delete();

        return redirect()->route('notes.index')
            ->with('deleted', true);
    }

    public function togglePin(Note $note)
    {
        Gate::authorize('update', $note);
        $note->update(['is_pinned' => !$note->is_pinned]);

        $msg = $note->fresh()->is_pinned ? 'Note pinned!' : 'Note unpinned.';

        return back()->with('success', $msg);
    }

    public function updateColor(Request $request, Note $note)
    {
        Gate::authorize('update', $note);

        $data = $request->validate([
            'color' => ['required', 'string', 'in:default,blue,green,yellow,red,purple'],
        ]);

        $note->update(['color' => $data['color']]);

        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request, Note $note)
    {
        Gate::authorize('update', $note);

        $request->validate([
            'image' => ['required', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            if ($note->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($note->image);
            }
            
            $path = $request->file('image')->store('notes', 'public');
            $note->update(['image' => $path]);

            return response()->json([
                'success' => true,
                'image_url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['success' => false], 400);
    }

    public function trash()
    {
        $notes = Auth::user()->notes()->onlyTrashed()->latest()->get();
        return view('notes.trash', compact('notes'));
    }

    public function restore($id)
    {
        $note = Auth::user()->notes()->onlyTrashed()->findOrFail($id);
        $note->restore();

        return redirect()->route('notes.trash')
            ->with('success', 'Note restored.');
    }

    public function forceDelete($id)
    {
        $note = Auth::user()->notes()->onlyTrashed()->findOrFail($id);
        if ($note->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($note->image);
        }
        $note->forceDelete();

        return redirect()->route('notes.trash')
            ->with('success', 'Note deleted forever.');
    }

    public function emptyTrash()
    {
        $notes = Auth::user()->notes()->onlyTrashed()->get();
        foreach ($notes as $note) {
            if ($note->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($note->image);
            }
            $note->forceDelete();
        }

        return redirect()->route('notes.trash')
            ->with('success', 'Trash emptied.');
    }

    public function duplicate(Note $note)
    {
        Gate::authorize('update', $note);

        $newNote = $note->replicate();
        if ($note->title) {
            $newNote->title = $note->title . ' (Copy)';
        } else {
            $newNote->title = 'Copy';
        }
        
        if ($note->image) {
            $extension = pathinfo($note->image, PATHINFO_EXTENSION);
            $newPath = 'notes/' . uniqid() . '.' . $extension;
            \Illuminate\Support\Facades\Storage::disk('public')->copy($note->image, $newPath);
            $newNote->image = $newPath;
        }
        $newNote->save();

        return redirect()->route('notes.index')
            ->with('success', 'Note duplicated.');
    }

    public function archive(Request $request)
    {
        $settings = Auth::user()->settings ?? [];
        $sortOrder = ($settings['add_to_bottom'] ?? true) ? 'asc' : 'desc';

        $query = Auth::user()->notes()->archived();

        if ($sortOrder === 'asc') {
            $query->oldest();
        } else {
            $query->latest();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $notes = $query->get();
        $pinned = $notes->where('is_pinned', true);
        $unpinned = $notes->where('is_pinned', false);
        $totalNotes = Auth::user()->notes()->count();

        return view('notes.index', compact('notes', 'pinned', 'unpinned', 'totalNotes'))->with('isArchivePage', true);
    }

    public function toggleArchive(Note $note)
    {
        Gate::authorize('update', $note);
        $note->update(['is_archived' => !$note->is_archived]);

        // If a note is archived, it should be unpinned as well to match Google Keep behavior
        if ($note->is_archived) {
            $note->update(['is_pinned' => false]);
        }

        $msg = $note->fresh()->is_archived ? 'Note archived.' : 'Note unarchived.';

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }

    public function storeLabel(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $label = Auth::user()->labels()->create([
            'name' => $data['name']
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'label' => $label]);
        }

        return back()->with('success', 'Label created.');
    }

    public function updateLabel(Request $request, Label $label)
    {
        if ($label->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $label->update(['name' => $data['name']]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'label' => $label]);
        }

        return back()->with('success', 'Label updated.');
    }

    public function destroyLabel(Label $label)
    {
        if ($label->user_id !== Auth::id()) {
            abort(403);
        }

        $label->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Label deleted.');
    }

    public function labelView(Request $request, Label $label)
    {
        if ($label->user_id !== Auth::id()) {
            abort(403);
        }

        $settings = Auth::user()->settings ?? [];
        $sortOrder = ($settings['add_to_bottom'] ?? true) ? 'asc' : 'desc';

        $query = $label->notes()->active();

        if ($sortOrder === 'asc') {
            $query->oldest();
        } else {
            $query->latest();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $notes = $query->get();
        $pinned = $notes->where('is_pinned', true);
        $unpinned = $notes->where('is_pinned', false);
        $totalNotes = Auth::user()->notes()->count();

        return view('notes.index', compact('notes', 'pinned', 'unpinned', 'totalNotes'))
            ->with('currentLabel', $label);
    }

    public function toggleNoteLabel(Request $request, Note $note)
    {
        Gate::authorize('update', $note);

        $request->validate([
            'label_id' => ['required', 'exists:labels,id']
        ]);

        $labelId = $request->label_id;
        $label = Label::findOrFail($labelId);
        
        if ($label->user_id !== Auth::id()) {
            abort(403);
        }

        $note->labels()->toggle($labelId);
        $attached = $note->labels()->where('label_id', $labelId)->exists();

        return response()->json([
            'success' => true,
            'attached' => $attached,
            'message' => $attached ? 'Label added' : 'Label removed'
        ]);
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'add_to_bottom' => ['nullable', 'boolean'],
            'move_checked_to_bottom' => ['nullable', 'boolean'],
            'dark_theme' => ['nullable', 'boolean'],
        ]);

        $user = Auth::user();
        $currentSettings = $user->settings ?? [];

        $newSettings = array_merge($currentSettings, [
            'add_to_bottom' => $request->has('add_to_bottom') ? (bool)$request->add_to_bottom : false,
            'move_checked_to_bottom' => $request->has('move_checked_to_bottom') ? (bool)$request->move_checked_to_bottom : false,
            'dark_theme' => $request->has('dark_theme') ? (bool)$request->dark_theme : false,
        ]);

        $user->update(['settings' => $newSettings]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'settings' => $newSettings]);
        }

        return back()->with('success', 'Settings updated.');
    }

    public function submitFeedback(Request $request)
    {
        $request->validate([
            'content' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        Auth::user()->feedbacks()->create([
            'content' => $request->content
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Thank you for your feedback!']);
        }

        return back()->with('success', 'Thank you for your feedback!');
    }
}
