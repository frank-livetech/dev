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
    protected $data,$type;

    public function __construct($data)
    {
        $this->data = $data;
    }
     /**
    * @return array []
    * Heading of CSV File
    */
    public function headings(): array
    {
        return Schema::getColumnListing('asset_templates_fields');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        return collect($this->data);

    }
}
