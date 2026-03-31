<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('user_id',auth()->id())
            ->latest()
            ->paginate(5);
            // 10にするかも
            return view('categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);
        Category::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'type' => $request->type,
        ]);
        return redirect()->route('categories.index')->with('success','カテゴリを作成しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        if($category->user_id !== auth()->id()) {
            abort(403);
        }
        return view('categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        if($category->user_id !== auth()->id()) {
            abort(403);
        }
        $request->validate([
            'name'=> 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);
        $category->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);
        return redirect()->route('categories.index')->with('success','カテゴリを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if($category->user_id !== auth()->id()) {
            abort(403);
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success','カテゴリを削除しました。');
    }
}
