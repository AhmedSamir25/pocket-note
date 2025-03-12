<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::where('user_id', Auth::id())->get();
        return view('welcome', compact('tags'));
    }

    public function show($id)
    {
        return response()->json(Tag::findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Tag::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'تمت إضافة العلامة بنجاح']);
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->update($request->all());

        return response()->json(['message' => 'تم تحديث العلامة بنجاح']);
    }

    public function destroy($id)
    {
        Tag::findOrFail($id)->delete();

        return response()->json(['message' => 'تم حذف العلامة']);
    }
}