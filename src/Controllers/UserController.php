<?php
namespace module\users\Controllers;

use Illuminate\Routing\Controller;
use Dingo\Api\Http\Request;
//use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use module\users\Repositories\UserRepository;
use module\users\Models\User;

class UserController extends ApiController
{
   protected $UserRepository;
   public function __construct(UserRepository $UserRepository) {
        
      $this->UserRepository = $UserRepository;
   }
   public function index()
   {
        try {
            $userlist = $this->UserRepository->getallrecords();
            $data['data'] = $userlist;
            $this->setStatusCodeSuccess();
            return $this->respond($data);

        } catch (\Exception $e) {

            $this->setStatusCodeException();
            $data['message'] = $e->getMessage();
            return $this->respondFail($data);
        }
   }
   public function show($id)
   {
        try {

            $userlist =  $this->UserRepository->getUserbyId($id);          
            $data['data'] = $userlist;
            $this->setStatusCodeSuccess();
            return $this->respond($data);

        } catch (\Exception $e) {
            
            $this->setStatusCodeException();
            $data['message'] = $e->getMessage();
            return $this->respondFail($data);

        }
   	}
    public function store(Request $request)
    {
        try { 
            // Validation rules
            $rules = [
                'FirstName' => ['required'],
                'LastName' => ['required'],
                'AddressId' => ['required'],
                'PhoneNumber' => ['required'],
                'Email' => ['required'],
                'Gender' => ['required'],
                'DateofBirth' => ['required'],
                'Password' => ['required'],
                'UserTypeId' => ['required'],
                'UserImage' => ['required']
            ];

            $requestData = json_decode($request->getContent(), true);
            
            $payload = array(
                    'FirstName'     =>  $requestData['FirstName'],
                    'LastName'      =>  $requestData['LastName'],
                    'AddressId'     =>  $requestData['AddressId'],
                    'PhoneNumber'   =>  $requestData['PhoneNumber'],
                    'Email'         =>  $requestData['Email'],
                    'Gender'        =>  $requestData['Gender'],
                    'DateofBirth'   =>  $requestData['DateofBirth'],
                    'Password'      =>  $requestData['Password'],
                    'UserTypeId'    =>  $requestData['UserTypeId'],
                    'UserImage'     =>  $requestData['UserImage'],
                );
            $validator = app('validator')->make($payload, $rules);

            if ($validator->fails()) {
                $messages="";
                $errors = $validator->errors();
                foreach ($errors->all() as $message) {
                   if( $messages=="") $messages=$message;
                   else $messages.="\n". $message;
                }
                $this->setStatusCodeFailValidation();
                $data['message'] = $messages;
                return $this->respondFail($data);
            }

            // Create New User

            $userlist = $this->UserRepository->createUser($payload); 
            $data['data'] = $userlist;
            $this->setStatusCodeSuccess();
            return $this->respond($data);

        } catch (\Exception $e) {

            $this->setStatusCodeException();
            $data['message'] = $e->getMessage();
            return $this->respondFail($data);
        }
    }
    public function update(Request $request , $user_id)
    {
        try { 
           
            // Validation rules
            $rules = [
                'FirstName' => ['required'],
                'LastName' => ['required'],
                'AddressId' => ['required'],
                'PhoneNumber' => ['required'],
                'Email' => ['required'],
                'Gender' => ['required'],
                'DateofBirth' => ['required'],
                'Password' => ['required'],
                'UserTypeId' => ['required'],
                'UserImage' => ['required']
            ];

            $requestData = json_decode($request->getContent(), true);
            
            $payload = array(
                'FirstName'     =>  $requestData['FirstName'],
                'LastName'      =>  $requestData['LastName'],
                'AddressId'     =>  $requestData['AddressId'],
                'PhoneNumber'   =>  $requestData['PhoneNumber'],
                'Email'         =>  $requestData['Email'],
                'Gender'        =>  $requestData['Gender'],
                'DateofBirth'   =>  $requestData['DateofBirth'],
                'Password'      =>  $requestData['Password'],
                'UserTypeId'    =>  $requestData['UserTypeId'],
                'UserImage'     =>  $requestData['UserImage'],
            );

            /*if(isset($requestData['imageName'])) {
               $payload['imageName'] = $requestData['imageName']; 
            }*/
            
            $validator = app('validator')->make($payload, $rules);

            if ($validator->fails()) {
                $messages="";
                $errors = $validator->errors();
                foreach ($errors->all() as $message) {
                   if($messages == "") 
                    $messages = $message;
                   else 
                    $messages .= "\n".$message;
                }

                $this->setStatusCodeFailValidation();
                $data['message'] = $messages;
                return $this->respondFail($data);
            }

            $userlist = $this->UserRepository->updateUser($payload, $user_id, $request,$type="all");
            $data['data'] = $userlist;
            $this->setStatusCodeSuccess();
            return $this->respond($data);

        } catch (\Exception $e) {
            $this->setStatusCodeException();
            $data['message'] = $e->getMessage();
            return $this->respondFail($data);
        }
    }
    public function active(Request $request , $user_id)
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $payload = array('IsActive'=>$requestData['IsActive']);

            $userlist = $this->UserRepository->updateUser($payload,$user_id,$request,'active');
            $data['data'] = $userlist;
            $this->setStatusCodeSuccess();
            return $this->respond($data);
        } catch (\Exception $e) {
            $this->setStatusCodeException();
            $data['message'] =$e->getMessage();
            return $this->respondFail($data);
        }
    }
    public function delete(Request $request , $user_id)
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $payload = array('IsDelete'=>$requestData['IsDelete']);

            $userlist = $this->UserRepository->updateUser($payload,$user_id,$request,'delete');
            $data['data'] = $userlist;
            $this->setStatusCodeSuccess();
            return $this->respond($data);
        } catch (\Exception $e) {
            $this->setStatusCodeException();
            $data['message'] =$e->getMessage();
            return $this->respondFail($data);
        }
        
    }
    public function destroy($id)
    {
        try { 
            $userlist = $this->UserRepository->deleteUser($id);
            if(!empty($userlist)){
                $data['status'] = 'success';
                $m['message'] = "User delete successfully";
                $data['errors'] = $m;
            } else {
                $data['status'] = 'fail';
                $m['message'] = "Enter valid UserId";
                $data['errors'] = $m;
            }
            $this->setStatusCodeSuccess();
            return $this->respond($data);
        } catch (\Exception $e) {
            $this->setStatusCodeException();
            $data['message'] = $e->getMessage();
            return $this->respondFail($data);
        }
    }

}
