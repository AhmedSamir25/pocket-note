<?php

namespace App\Http\Controllers;
use App\Models\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tags::where('user_id', Auth::id())->get();
        return view('welcome', compact('tags'));
    }

    public function show($id)
    {
        return response()->json(Tags::findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Tags::create([
            'user_id' => Auth::id(),
            'name' => $request->title,
        ]);

        return response()->json(['message' => 'تمت إضافة الملاحظة بنجاح']);
    }

    public function update(Request $request, $id)
    {
        $tag = Tags::findOrFail($id);
        $tag->update($request->all());

        return response()->json(['message' => 'تم تحديث الملاحظة بنجاح']);
    }

    public function destroy($id)
    {
        Tags::findOrFail($id)->delete();

        return response()->json(['message' => 'تم حذف الملاحظة']);
    }
}
