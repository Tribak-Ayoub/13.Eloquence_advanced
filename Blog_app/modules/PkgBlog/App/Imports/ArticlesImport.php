<?php

namespace App\Imports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\ToModel;

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
            //
        ]);
    }
}
