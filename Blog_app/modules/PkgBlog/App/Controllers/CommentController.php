<?php

namespace Modules\PkgBlog\App\Controllers;

use Illuminate\Http\Request;
use Modules\Core\App\Controllers\BaseController;
use Modules\PkgBlog\App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // You may want to list all comments or apply pagination or filters
        $comments = Comment::paginate(10);
        return view('blog::admin.comment.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Optionally, you can return a view to create a comment if needed
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000', // Add a max length for better control
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
        ]);

        // Optionally, check if the user is authorized to comment on the model type
        if (Auth::check()) {
            $validated['user_id'] = Auth::id();  // Associating the comment with the logged-in user

            // Create the comment
            Comment::create($validated);

            return redirect()->back()->with('success', 'Le commentaire a bien été créé.');
        }

        return redirect()->back()->with('error', 'Veuillez vous connecter pour ajouter un commentaire.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Typically not needed for comments, unless you want to view a specific comment
        $comment = Comment::findOrFail($id);
        return view('blog::admin.comment.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $comment = Comment::findOrFail($id);
        return view('blog::admin.comment.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment = Comment::findOrFail($id);
        
        // Ensure the user is authorized to update this comment (e.g., check if it's their comment)
        if (Auth::id() == $comment->user_id || Auth::user()->hasRole('admin')) {
            $comment->update($validated);
            return redirect()->back()->with('success', 'Le commentaire a bien été mis à jour.');
        }

        return redirect()->back()->with('error', 'Vous ne pouvez pas modifier ce commentaire.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);

        // Authorization check: Ensure the user can delete the comment
        if (Auth::id() == $comment->user_id || Auth::user()->hasRole('admin')) {
            $comment->delete();
            return redirect()->back()->with('success', 'Le commentaire a bien été supprimé.');
        }

        return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer ce commentaire.');
    }
}
