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
        $update_data_asst = [];
        $update_fl_id = 0;
        $matched_index = [];

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

            $cols = Arr::except($rows[$i], ['customer','company']);
            // foreach($cols->keys() as $j => $field){
            //     $code = AssetFields::where('label','like', '%'.$field.'%')->where('asset_forms_id', $this->asset_type)->first();
            //     if($code != null || $code != []){
            //         // dd($row[$field],$code->id,$field,$j);
            //         $matched = DB::table('asset_records_'.$this->asset_type)->where('fl_'.$code->id, $row[$field])->first();

            //         if($matched){
            //             $update_data[$i]['fl_'.$code->id] = $row[$field];
            //             $update_fl_id = $matched->id;
            //             if($rows[$i][$field]  == $row[$field]){
            //                 $matched_index[] = $i;
            //             }
            //             // DB::table('asset_records_'.$this->asset_type)
            //             //     ->where('id',$matched->id)
            //             //     ->update(array('fl_'.$code->id => $row[$field]));
            //         }
            //     }

            // }


            foreach($cols->keys() as $j => $field){
                $code = AssetFields::where('label','like', '%'.$field.'%')->where('asset_forms_id', $this->asset_type)->first();
                if($code != null || $code != []){
                    if($j == 0){
                        $update_fl_id = 'fl_'. $code->id;
                    }
                    $data[$i]['fl_'. $code->id] = $row[$field] ?? null;
                }

                $data[$i]['form_id'] = $this->asset_type ?? null;
                $data[$i]['created_at'] = Carbon::now();
                $data[$i]['updated_at'] = Carbon::now();
                $data[$i]['created_by'] = Auth::id();
                $data[$i]['updated_by'] = null;


            }
        }

        foreach($data as $i => $dt)
        {

            $upd = DB::table('asset_records_'.$this->asset_type)
                            ->where('form_id',$dt['form_id'])
                    ->where($update_fl_id,$dt[$update_fl_id]);
            if($upd->get()->count() != 0){
                $upd->update($data[$i]);
            }else{
                $asset = Assets::create([
                    'company_id' => ($comp != null) ? $comp->id : null,
                    'customer_id' => ($cust != null) ? $cust->id : null,
                    'asset_forms_id' => $this->asset_type
                ]);
                $dt['asset_id'] = $asset->id ?? null;
                DB::table('asset_records_'.$this->asset_type)->insert($dt);
            }
        }
    }
}
