<?php

namespace Modules\PkgBlog\App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\PkgBlog\App\Models\Article;

class ArticlesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Article::all(['title', 'content', 'user_id', 'category_id', 'created_at', 'updated_at']);
    }

        /**
     * Define the headers for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Title',
            'Content',
            'User ID',
            'Category ID',
            'Created At',
            'Updated At',
        ];
    }
}
