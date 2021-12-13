<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use App\Models\SaveNewTag;
use App\Models\getTags;
use App\Models\Products;
use Illuminate\Http\Request;
use Validator;

class MarketingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function contact_manager(){

        $contacts = ContactInfo::where('is_deleted', 0)->get([
            'id','first_name', 'last_name','company','email_1','email_2','office_num','cell_num',
            'street_addr_1','street_addr_2','city_name','state','zip_code','country_name','notes',
            'email_list_tags','active_customer','tag_id'
        ]);
        // dd($contacts);
        return view('marketing.contact_manager.index',compact('contacts'));

    }

    public function contact(Request $request){
        $data = $request->all();
        $do_validate = true;
        if($data['import'] == 'true'){
            $do_validate = false;
        }

        $response = array();

        try{
            $validator = Validator::make($request->all(), [ 
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'company' => ['required', 'string', 'max:255'],
                'email_1' => ['required', 'string', 'email', 'max:255'],
                'cell_num' => ['required', 'string', 'max:40'],
                'street_addr_1' => ['required', 'string', 'max:255'],
                'city_name' => ['required', 'string', 'max:255'],
                'state' => ['required', 'string', 'max:255'],
                'zip_code' => ['required', 'string', 'max:255'],
                'country_name' => ['required', 'string', 'max:255'],
            ]);
            if ($validator->fails() && $do_validate) {

                $response['message'] = $validator->messages()->first();
                $response['status_code'] = 200;
                $response['success'] = false;
                return response()->json($response);

            }else{
                if($request->input('contact_id')){
                    $contact = ContactInfo::findOrFail($request->input('contact_id'));
                    $contact->first_name = $data['first_name'];
                    $contact->last_name = $data['last_name'];
                    $contact->company = $data['company'];
                    $contact->email_1 = $data['email_1'];
                    $contact->email_2 = $data['email_2'];
                    $contact->office_num = $data['office_num'];
                    $contact->cell_num = $data['cell_num'];
                    $contact->street_addr_1 = $data['street_addr_1'];
                    $contact->street_addr_2 = $data['street_addr_2'];
                    $contact->city_name = $data['city_name'];
                    $contact->state = $data['state'];
                    $contact->zip_code = $data['zip_code'];
                    $contact->country_name = $data['country_name'];
                    $contact->notes = $data['notes'];
                    $contact->tag_id = $data['tag_ids'];
                    $contact->updated_by = \Auth::user()->id;

                    $contact->save();
                    $response['message'] = 'Contact Updated Successfully!';
                    $response['data'] = $contact;

                }else{
                    if($data['import'] == 'true'){
                        $ret = [];
                        unset($data['import']);
                        for($i=0; $i<sizeof($data); $i++){
                            $decoded_data = json_decode($data[$i], true);
                            $decoded_data['tag_id'] = null;
                            $decoded_data['created_by'] = \Auth::user()->id;
                            
                            $added = ContactInfo::create($decoded_data);
                            $decoded_data['id'] = $added->id;
                            $ret[] = $decoded_data;
                        }
                        $data = $ret;
                    }else{
                        if(array_key_exists('tag_ids', $data)){
                            $data['tag_id'] = $data['tag_ids'];
                        }
                        $data['created_by'] = \Auth::user()->id;
                        
                        $added = ContactInfo::create($data);
                        $data['id'] = $added->id;
                    }
                    $response['message'] = 'Contact Saved Successfully!';
                    $response['data'] = $data;
                }
            }

            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        }catch(Exception $e){
            $response['message'] = $e->getMessage();//'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }
    public function product_template(Request $request , $id){
        return view('marketing.product_manager.product_template',compact('id'));
    }
    
    public function product_manager(){
        return view('marketing.product_manager.index');

    }
    public function digital_goods(Request $request , $id){
        return view('marketing.digital_goods',compact('id'));


    }
    
    // public function hard_goods(){
    //     return view('marketing.edit_product');

    // }
    public function save_tag(Request $request){
        //return $request;

        $data = $request->all();
        $response = array();
        try{
           /* return $response;
            if(!empty($request->id)){
            
                $tags_id = SaveNewTag::where('id',$request->id)->first();
                $tags_id->name = $data['name'];
                $tagss_id->updated_by = \Auth::user()->id;
            
            if($tags_id){

                $tags_id->update();
                $response['message'] = 'Tags Update Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }*/

            $data['created_by'] = \Auth::user()->id;
            $tag = SaveNewTag::create($data);
            $response['message'] = 'New Tag Added Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }
    catch(Exception $e){
            $response['message'] = 'This Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }
    public function get_Tags(){
        //return $request;
        $tagsNew = SaveNewTag::all();

        try{
            $response['data'] = $tagsNew;
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }catch(Exception $e){
            $response['message'] = 'This Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }
    public function get_contacts(){

        $data = array();

        $contacts = ContactInfo::all();

        foreach($contacts as $contact){
            $contact->Id =  $contact->id;
            $contact->{'First Name'} =  $contact->first_name;
            $contact->{'Last Name'} =  $contact->last_name;
            $contact->Company =  $contact->company;
            $contact->Email_1 =  $contact->email_1;
            $contact->Address =  $contact->address;
            array_push($data,$contact);

        }

        $response['status_code'] = 200;
        $response['success'] = true;
        $response['contact'] = $data;
        return response()->json($response);
        
          
    }

    /*Product Add */
    public function addProduct(Request $request){
        $data = $request->all();
        $response = array();
        try{
            if($request->product_id != null && $request->product_id != ""){

                if( $request->hasFile('feature_image') ) {
                    $image = $request->file('feature_image');
                    $imageName = rand(). '.' . $image->extension();
                    $image->move(public_path('files/products_imgs'), $imageName);

                    $data['feature_image'] = $imageName;
                }  

                
                Products::where('id', $request->product_id )->update($data);
                $response['message'] = 'New Goods Updated Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);
            }
            else{

                if( $request->hasFile('feature_image') ) {
                    $image = $request->file('feature_image');
                    $imageName = rand(). '.' . $image->extension();
                    $image->move(public_path('files/products_imgs'), $imageName);

                    $data['feature_image'] = $imageName;
                }                

                $data['created_by'] = \Auth::user()->id;
                Products::create($data);
                $response['message'] = 'New Goods Added Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);
            }
        }
        catch(Exception $e){
            $response['message'] = 'This Went wrong!';
            $response['status_code'] = 500;
            $response['error'] = true;
            return response()->json($response);
        }
        return $request->all();
    }

    public function edit_goods(Request $request , $id){
        $product = Products::find($id);
        return view('marketing.edit_product',compact('product'));
    }
   
    public function delProducts(Request $request){

        $products = Products::where("id",$request->id)->first();
        $products->is_deleted = 1;
        $products->save();

        $response['message'] = 'Product Deleted Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
        
    }

    public function getProducts(){
        
            // $products = Products::all();
            $products = Products::where('is_deleted', null)->get();
            $response['message'] = 'List Fetched.';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['products'] = $products;
            $response['date_format'] = Session('system_date');
            return response()->json($response);
    }
    

 
}
