<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Tag;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        $tags = Tag::where('user_id', Auth::id())->get();
        return view('welcome', compact('notes', 'tags'));
    }

    public function show($id)
    {
        return response()->json(Note::findOrFail($id));
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'tag_id' => 'nullable|exists:tags,id',
    ]);

    // إنشاء الملاحظة
    $note = Note::create([
        'user_id' => Auth::id(),
        'title' => $request->title,
        'content' => $request->content,
    ]);

    // ربط العلامة بالملاحظة إذا تم تحديدها
    if ($request->tag_id) { // تغيير من has('tag_id') إلى التحقق المباشر
        $note->tags()->attach($request->tag_id);
    }

    return response()->json(['message' => 'تمت إضافة الملاحظة بنجاح']);
}

public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'tag_id' => 'nullable|exists:tags,id',
    ]);

    // البحث عن الملاحظة وتحديثها
    $note = Note::findOrFail($id);
    $note->update([
        'title' => $request->title,
        'content' => $request->content,
    ]);

    // تحديث العلامة المرتبطة بالملاحظة
    if ($request->tag_id) { // تغيير من has('tag_id') إلى التحقق المباشر
        $note->tags()->sync([$request->tag_id]);
    } else {
        $note->tags()->detach();
    }

    return response()->json(['message' => 'تم تحديث الملاحظة بنجاح']);
}

    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->tags()->detach(); // إزالة جميع العلامات المرتبطة بالملاحظة
        $note->delete(); // حذف الملاحظة

        return response()->json(['message' => 'تم حذف الملاحظة']);
    }
}