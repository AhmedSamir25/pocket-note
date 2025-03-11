<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // جلب جميع الملاحظات
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())->get();
        return view('welcome', compact('notes'));
    }

    // جلب ملاحظة واحدة
    public function show($id)
    {
        return response()->json(Note::findOrFail($id));
    }

    // إضافة ملاحظة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Note::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'تمت إضافة الملاحظة بنجاح']);
    }

    // تحديث ملاحظة
    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);
        $note->update($request->all());

        return response()->json(['message' => 'تم تحديث الملاحظة بنجاح']);
    }

    // حذف ملاحظة
    public function destroy($id)
    {
        Note::findOrFail($id)->delete();

        return response()->json(['message' => 'تم حذف الملاحظة']);
    }
}
