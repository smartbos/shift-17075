<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rap2hpoutre\FastExcel\FastExcel;

class Roomcode extends Model
{
    protected $fillable = ['date', 'code'];

    public function storeUsingFile($file)
    {
        $rows = (new FastExcel())->import($file);

        foreach ($rows as $row) {
            $code = substr($row['코드'], 0, 6);

            try {
                $this->create([
                    'date' => $row['날짜'],
                    'code' => $code
                ]);
            } catch (\Exception $e) {

            }
        }
    }
}
