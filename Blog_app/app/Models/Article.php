<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;

class Article extends Model
{
  //
  use HasFactory;


  protected $fillable = ['title', 'content', 'category_id', 'user_id'];

  public function category()
  {
    return $this->belongsTo(Category::class);
  }
  public function tags()
  {
    return $this->belongsToMany(Tag::class);
  }

  public function comments()
  {
    return $this->morphMany(Comment::class, 'commentable');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
