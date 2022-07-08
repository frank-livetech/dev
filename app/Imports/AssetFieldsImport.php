<?php

namespace App\Imports;

use App\Models\AssetFields;
use App\Models\AssetForms;
use App\Models\Assets;
use App\Models\Company;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
        $data = [];
        foreach($rows as $i => $row){
            if($rows[$i]['customer'] != null || $rows[$i]['customer'] != ''){
                $cust = Customer::where('email',$rows[$i]['customer'])->first();
            }else{
                $cust = null;
            }

            if($rows[$i]['company'] != null || $rows[$i]['company'] != ''){
                $comp = Company::where('name',$rows[$i]['company'])->first();
            }else{
                $comp = null;
            }


            $asset = Assets::create([
                'company_id' => ($comp != null) ? $comp->id : null,
                'customer_id' => ($cust != null) ? $cust->id : null,
                'asset_forms_id' => $this->asset_type
            ]);

            $cols = Arr::except($rows[$i], ['customer','company']);

            foreach($cols->keys() as $j => $field){
                $code = AssetFields::where('label','like', '%'.$field.'%')->where('asset_forms_id', $this->asset_type)->first();
                if($code != null || $code != []){
                    $data[$i]['fl_'. $code->id] = $row[$field] ?? null;
                }

                $data[$i]['form_id'] = $this->asset_type ?? null;
                $data[$i]['asset_id'] = $asset->id ?? null;
                $data[$i]['created_at'] = Carbon::now();
                $data[$i]['updated_at'] = Carbon::now();
                $data[$i]['created_by'] = Auth::id();
                $data[$i]['updated_by'] = null;
            }
        }

        DB::table('asset_records_'.$this->asset_type)->insert($data);
    }
}
