@extends('layouts.admin')

@section('content')
<div class="container my-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 font-weight-bold text-dark">Détails de l'article</h1>
        <p class="lead text-muted">Explorez les détails de l’article sélectionné.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Article Card -->
    <div class="card shadow-lg border-0 mb-5">
        <div class="card-body">
            <!-- Article Title -->
            <h2 class="h3 font-weight-semibold text-dark">{{ $article->title }}</h2>

            <!-- Author and Date -->
            <div class="mt-4 text-muted">
                <p>
                    Publié le <span class="font-weight-medium">{{ $article->created_at->format('F j, Y') }}</span>
                </p>
            </div>

            <!-- Categories -->
            <div class="mt-4 text-sm text-muted">
                <p>Catégorie:
                    @if($article->category)
                        <span class="font-weight-semibold text-dark">{{ $article->category->name }}</span>
                    @else
                        <span class="font-weight-semibold text-muted">Aucune catégorie disponible</span>
                    @endif
                </p>
            </div>

            <!-- Tags -->
            <div class="mt-4 text-sm text-muted">
                <p>Tags:
                    @if($article->tags->isNotEmpty())
                        <span class="font-weight-semibold text-dark">
                            @foreach($article->tags as $tag)
                                {{ $tag->name }}@if(!$loop->last), @endif
                            @endforeach
                        </span>
                    @else
                        <span class="font-weight-semibold text-muted">Aucun tag disponible</span>
                    @endif
                </p>
            </div>

            <!-- Article Content -->
            <div class="mt-4 text-dark">
                {!! nl2br(e($article->content)) !!}
            </div>

            <!-- Action Buttons -->
            <div class="mt-5 d-flex justify-content-between">
                <!-- Back to Articles Button -->
                <form action="{{ route('Blog\articles.index') }}" method="GET">
                    <button type="submit" class="btn btn-secondary">Retour aux articles</button>
                </form>

                <div>
                    <!-- Edit Article Button -->
                    <form action="{{ route('Blog\articles.edit', $article->id) }}" method="GET" class="d-inline">
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </form>

                    <!-- Delete Article Button -->
                    <form action="{{ route('Blog\articles.destroy', $article->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-0 border" style="background-color: #f0f2f5;">
                <div class="card-body p-4">
                    <h2 class="h3 font-weight-semibold text-dark text-center">Commentaires</h2>

                    <!-- Add Comment -->
                    <div class="form-outline mb-4">
                        <form action="{{ route('Blog\comments.store') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="commentable_id" value="{{ $article->id }}">
                            <input type="hidden" name="commentable_type" value="Modules\PkgBlog\App\Models\Article">
                            <input type="text" name="text" id="addComment" class="form-control" placeholder="Tapez un commentaire..." />
                            <button type="submit" class="btn btn-success my-2">Ajouter</button>
                        </form>
                    </div>

                    <!-- Display Comments -->
                    @foreach($article->comments as $comment)
                    <div class="card mb-4">
                        <div class="card-body">
                            <p>{{ $comment->text }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <p class="small mb-0 ms-2">{{ $comment->user->name ?? 'Auteur Anonyme' }}</p>
                                </div>
                                <div class="d-flex align-items-center text-body">
                                    <form action="{{ route('Blog\comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
