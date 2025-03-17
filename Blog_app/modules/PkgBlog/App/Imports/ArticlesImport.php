<?php

namespace Modules\PkgBlog\App\Imports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\PkgBlog\App\Models\Article;

class ArticlesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        return new Article([
            'title' => $row['title'] ?? 'Untitled',
            'content' => $row['content'] ?? 'No content',
            'user_id' => Auth::id() ?? 1,
            'category_id' => (int) ($row['category_id'] ?? 1),
            'created_at' => $row['created_at'] ?? now(),
            'updated_at' => $row['updated_at'] ?? now(),
        ]);
    }
}
