<?php

namespace App\Imports;

use App\Models\AssetFields;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class AssetFieldsImport implements ToCollection, WithHeadingRow
{
    protected $asset_type;

    public function  __construct(int $asset_type)
    {
        $this->asset_type= $asset_type;
    }

    public function headingRow(): int
    {
        return 1;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        DB::table('asset_records_'.$this->asset_type)->insert($rows->toArray());
    }
}
