<?php

namespace Modules\PkgBlog\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\App\Controllers\BaseController;
use Modules\PkgBlog\App\Models\Tag;

class TagController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tag::query();

        // Search filtering
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Paginate the tags
        $tags = $query->paginate(10);
        return view('blog::admin.tag.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blog::admin.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',  // Added unique validation to prevent duplicate tags
        ]);

        // Create and store the new tag
        $tag = new Tag();
        $tag->name = $request->name;
        $tag->save();

        // Redirect with success message
        return redirect()->route('tags.index')->with('success', 'Le tag a bien été créé.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Show the tag details if needed (optional for now)
        $tag = Tag::findOrFail($id);
        return view('blog::admin.tag.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the tag to edit
        $tag = Tag::findOrFail($id);
        return view('blog::admin.tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $id,  // Exclude current tag from unique validation
        ]);

        // Find the tag and update it
        $tag = Tag::findOrFail($id);
        $tag->name = $request->name;
        $tag->save();

        // Redirect with success message
        return redirect()->route('tags.index')->with('success', 'Le tag a bien été mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find and delete the tag
        $tag = Tag::findOrFail($id);
        $tag->delete();

        // Redirect with success message
        return redirect()->route('tags.index')->with('success', 'Le tag a bien été supprimé.');
    }
}
