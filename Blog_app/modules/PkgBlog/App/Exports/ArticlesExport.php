<?php

namespace Modules\PkgBlog\App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\PkgBlog\App\Models\Article;

class ArticlesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Article::all();
    }

        /**
     * Define the headers for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Content',
            'Created At',
            'Updated At',
        ];
    }
}
