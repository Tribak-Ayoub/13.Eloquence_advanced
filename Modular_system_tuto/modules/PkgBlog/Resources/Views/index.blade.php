<h1>Blog Articles</h1>
<a href="{{route('articles.create')}}">add article</a>
@if($articles->isNotEmpty())
    @foreach ($articles as $article)
        <h2>{{ $article->title }}</h2>
        <p>{{ $article->content }}</p>
    @endforeach
@else
    <p>No articles found</p>
@endif
