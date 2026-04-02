<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Category::class);

        return view('admin.categories.index', [
            'categories' => Category::paginate(20)
        ]);
    }

    public function create()
    {
        $this->authorize('create', Category::class);

        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        
        $category->delete();

        return back()->with('success', 'Category deleted');
    }
}