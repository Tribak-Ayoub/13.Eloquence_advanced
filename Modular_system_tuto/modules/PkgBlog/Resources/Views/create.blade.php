<h1>Add New Article</h1>

<form action="{{ route('articles.store') }}" method="POST">
    @csrf
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required>

    <label for="content">Content:</label>
    <textarea name="content" id="content" required></textarea>

    <button type="submit">Add Article</button>
</form>

<a href="{{ route('articles.index') }}">Back to Articles</a>
