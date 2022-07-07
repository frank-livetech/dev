<?php

namespace App\Imports;

use App\Models\AssetFields;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetFieldsImport implements ToModel, WithHeadingRow
{
    protected $asset_type;

    public function  __construct(int $asset_type) {
        $this->asset_type= $asset_type;
    }
   /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new AssetFields([
            'asset_forms_id' => $this->asset_type,
            'label' => $row['label'],
            'type' =>  $row['type'],
            'placeholder' => $row['placeholder'],
            'description' => $row['description'],
            'required' => $row['required'] ?? 0,
            'is_multi' => $row['is_multi'] ?? 0,
            'copy_icon' => $row['copy_icon'] ?? 0,
            'options' => $row['options'],
            'col_width' => $row['col_width'],
            'created_by' => $row['created_by'],
            'updated_by' => $row['updated_by'],
            'deleted_at' => $row['deleted_at'],
            'deleted_by' => $row['deleted_by'],
            'is_deleted' => $row['is_deleted'] ?? 0,
        ]);
    }
}
