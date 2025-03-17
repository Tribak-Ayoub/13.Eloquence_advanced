<?php

namespace Modules\PkgBlog\App\Services;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Modules\PkgBlog\App\Exports\ArticlesExport;
use Modules\PkgBlog\App\Imports\ArticlesImport;

class ArticleImportExportService
{
    /**
     * Import articles from an Excel or CSV file.
     *
     * @param Request $request
     * @return void
     */
    public function import(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        if (!$file) {
            return back()->with('error', 'No file uploaded.');
        }

        // Import the data using the ArticlesImport class
        Excel::import(new ArticlesImport, $file);
    }

    /**
     * Export articles to an Excel file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        // Export the articles to an Excel file using the ArticlesExport class
        return Excel::download(new ArticlesExport, 'articles.xlsx');
    }
}
