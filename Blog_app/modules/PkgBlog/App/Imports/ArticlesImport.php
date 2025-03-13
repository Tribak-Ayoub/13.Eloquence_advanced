<?php

namespace Modules\PkgBlog\App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Modules\PkgBlog\App\Models\Article;

class ArticlesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Article([
            'title' => $row['title'],
            'content' => $row['content'],
        ]);
    }
}
