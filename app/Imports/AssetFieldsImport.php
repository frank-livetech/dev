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

            if(isset($rows[$i]['customer'])){
                if($rows[$i]['customer'] != null || $rows[$i]['customer'] != ''){
                    $cust = Customer::where('email',$rows[$i]['customer'])->first();
                }else{
                    $cust = null;
                }
            }else{
                $cust = null;
            }

            if(isset($rows[$i]['company'])){
                if(isset($rows[$i]['company']) && $rows[$i]['company'] != null || $rows[$i]['company'] != ''){
                    $comp = Company::where('name',$rows[$i]['company'])->first();
                }else{
                    $comp = null;
                }
            }else{
                $comp = null;
            }

            $cols = Arr::except($rows[$i], ['customer','company']);

            foreach($cols->keys() as $j => $field){

                if(isset($rows[$i][$field])){
                    $code = AssetFields::where('label','like', '%'.$field.'%')->where('asset_forms_id', $this->asset_type)->where('is_deleted',0)->first();
                    if($code != null || $code != []){
                        if($j == 0){
                            $update_fl_id = 'fl_'. $code->id;
                        }
                        $data[$i]['fl_'. $code->id] = $row[$field] ?? null;
                    }
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
                        ->where('form_id',$dt['form_id'])->where('is_deleted',0)
                        ->where($update_fl_id,$dt[$update_fl_id]);

            if($upd->get()->count() != 0){
                $record_up = $upd->update($dt);
                 $a= Assets::find($upd->get()[0]->asset_id);
                 $a->updated_by = Auth::id();
                 $a->save();
            }else{
                $asset = Assets::create([
                    'company_id' => ($comp != null) ? $comp->id : null,
                    'customer_id' => ($cust != null) ? $cust->id : null,
                    'asset_forms_id' => $this->asset_type,
                    'created_by' =>  Auth::id()
                ]);
                $dt['asset_id'] = $asset->id ?? null;

                DB::table('asset_records_'.$this->asset_type)->insert($dt);
            }
        }
    }
}
