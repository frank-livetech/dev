<?php

namespace App\Exports;

use App\Models\AssetFields;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetFieldsExport implements FromCollection, WithHeadings
{
    protected $data,$table_id;

    public function __construct($table_id,$data)
    {
        $this->data = $data;
        $this->table_id = $table_id;
    }
     /**
    * @return array []
    * Heading of CSV File
    */
    public function headings(): array
    {
        return Schema::getColumnListing('asset_records_'.$this->table_id);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }
}
