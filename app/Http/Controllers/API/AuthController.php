<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response;
Use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\BrandSettings;
use Carbon\Carbon;

class AuthController extends Controller
{
    public $loginAfterSignUp = false;
/**
     * @OA\Post(
     * path="/api/signup",
     * summary="Sign Up",
     * description="Signup by  email, password,name",
     * operationId="register",
     * 
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user Properties",
     *    @OA\JsonContent(
     *       required={"name","email","password"},
     *        @OA\Property(property="name", type="string", format="name", example="user1"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, got a problem please retry")
     *        )
     *     
     * ),
     * @OA\Response(
    *     response=200,
    *     description="Success",
    *     @OA\JsonContent(
    *        
    *        @OA\Property(property="access_token", type="string",example="User Created Successfully!"),
    *        @OA\Property(property="user", type="object"
    *                       ),
    *       


    *     )
    *  
    * )
    *)
    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:2,100',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);
      
      return response()->json(['message'=>'User Created Successfully!','user'=>$user]);
    }
    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Log In",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"Auth"}, 
     * security={ {"apiAuth": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     
     * ),
     * @OA\Response(
    *     response=200,
    *     description="Success",
    *     @OA\JsonContent(
    *        
    *        @OA\Property(property="access_token", type="string",example="fngajkk234.kjsdnfjklar24df.fmasjknfg345134brkjsdjkff.34523tg"),
    *        @OA\Property(property="token_type", type="string",example="bearer"),
    *       @OA\Property(property="expires_in", type="string",example="60"),
    *       @A\Property(property="user", type="string",example="60"),

    *     )
    *  
    * )
    *)
    */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        if (! $token = auth('api')->attempt($validator->validated(),['exp' => Carbon::now()->addDays(7)->timestamp])) {
            return response()->json(['success' =>true ,'message' => 'Unauthorized'], 401);
        }

        $user = User::where('email',$request->email)->first();
        return response()->json(['success' =>true ,'message'=>'Logged In!','user'=>$user , 'token' => $token]);
    }
          /**
 * @OA\GET(
 * path="/api/user",
 * summary="auth user details",
 * description="user detail",
 * operationId="getAuthUser",
 * tags={"Auth"},
 * security={ {"apiAuth": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Success",
 * *    @OA\JsonContent(
 *      @OA\Property(property="access_token", type="string",example="fngajkk234.kjsdnfjklar24df.fmasjknfg345134brkjsdjkff.34523tg"),
    *        @OA\Property(property="token_type", type="string",example="bearer"),
    *       @OA\Property(property="expires_in", type="string",example="60"), 
    *  )
 *     ),
 * @OA\Response(
 *    response=401,
 *    description="Error",
 *   
 * )
 * )
 */
    public function getAuthUser(Request $request)
    {
        return response()->json(auth()->user());
    }
    /**
 * @OA\Post(
 * path="/api/logout",
 * summary="Logout",
 * description="Logout user and invalidate token",
 * operationId="authLogout",
 * tags={"Auth"},
 * security={ {"apiAuth": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Success"
 *     ),
 * @OA\Response(
 *    response=401,
 *    description="Returns when user is not authenticated",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Not authorized"),
 *    )
 * )
 * )
 */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */


      /**
 * @OA\GET(
 * path="/api/refreshToken",
 * summary="refreshToken",
 * description="refreshToken token",
 * operationId="refreshToken",
 * tags={"Auth"},
 * security={ {"apiAuth": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Success",
 * *    @OA\JsonContent(
 *      @OA\Property(property="access_token", type="string",example="fngajkk234.kjsdnfjklar24df.fmasjknfg345134brkjsdjkff.34523tg"),
    *        @OA\Property(property="token_type", type="string",example="bearer"),
    *       @OA\Property(property="expires_in", type="string",example="60"), 
    *  )
 *     ),
 * @OA\Response(
 *    response=401,
 *    description="Error",
 
 * )
 * )
 */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token)
    {
      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('api')->factory()->getTTL() * 60

      ]);
    }
/**
     * @OA\Post(
     * path="/api/edit_profile",
     * summary="Sign Up",
     * description="Signup by  email, password,name",
     * operationId="register",
     * 
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user Properties",
     *    @OA\JsonContent(
     *       required={"name","email","password"},
     *        @OA\Property(property="name", type="string", format="name", example="user1"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, got a problem please retry")
     *        )
     *     
     * ),
     * @OA\Response(
    *     response=200,
    *     description="Success",
    *     @OA\JsonContent(
    *        
    *        @OA\Property(property="access_token", type="string",example="User Created Successfully!"),
    *        @OA\Property(property="user", type="object"
    *                       ),
    *       


    *     )
    *  
    * )
    *)
    */
    public function updateProfile(Request $request){
        // return $request;
        $data = $request->all();
        $response = array();

        $validatorRule = [];
        $user = User::where('id',$request->user_id)->first();
        //return $user;
        if($user->name != $request->name){
            $validatorRule['name'] = ['required', 'string', 'max:255'];
        }
        elseif($user->email != $data['email']){
            $validatorRule['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
        }
      
        $validator = Validator::make($request->all(), $validatorRule);
        if ($validator->fails()) {
            $response['message'] = $validator->messages()->first();
            $response['code'] = 200;
            $response['success'] = false;
            return response()->json($response);

        }
        else{
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->updated_by = $request->user_id;
            
            // if($data['password'] != '' || $data['password'] != null){
            //     $user->password = bcrypt($data['password']);
            // }
            if($request->has('profile_pic')){
                
                $image = $request->file('profile_pic');
                $filenamewithextension = $data['profile_pic']->getClientOriginalName();
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                //get file extension
                $extension = $data['profile_pic']->getClientOriginalExtension();

                if($user->profile_pic){
                    $ext = pathinfo($user->profile_pic, PATHINFO_EXTENSION);
                    $ext = basename($user->profile_pic, '.'.$ext);
                    $user->profile_pic = $ext;
                }

                $filenametostore = $user->profile_pic ? $user->profile_pic.'.'.$extension : time().'.'.$extension;
                // $filePath = public_path('');
                // echo $extension;
                
                $image->move('files/user_photos/', $filenametostore);
                $user->profile_pic = $filenametostore;
                // return response()->json($user);
            }
            $user->save();
            $response['message'] = 'User Update Successfully!';
            $response['code'] = 200;
            $response['success'] = true;
            $response['user'] = $user;
            return response()->json($response);

        }

    }

}