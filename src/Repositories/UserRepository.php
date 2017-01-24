<?php

namespace module\users\Repositories;

use Illuminate\Database\QueryException;
use module\users\Models\User;
use module\users\Exceptions\UserException;
/**
 * Class UserRepository
 *
 * User resources.
 */
class UserRepository
{
    public function getallrecords()
    {
    	try {
            $userlist = User::all();
            return $userlist;
        } catch (\Exception $e) {
            throw new UserException('Exception thrown while trying to fetch Introgallerys', 50001001);
        }
    }

    /*
    |   Get Perticular User by Id
    */

    public function getUserbyId($id)
    {
       $userlist = User::find($id);

        if (empty($userlist)) {
            try {
                // success
            } catch (\Exception $e) { 
                throw new UserException('User not found', 40400000);
            }
        }

        return $userlist;
    }

    /*
    |   Create new user
    */

    public function createUser($payload)
    {
        try {
           /*$userImageArray=(!isset( $payload['imageName'])) ? array() : $this->uploadBase64Image( $payload['imageName']);
            $image = (!isset( $userImageArray['secure_url'])) ?"" :  $userImageArray['secure_url'];*/

            $dataArray=array();
            $dataArray=[
                'UserId'        =>  rand(00000,99999),
                'FirstName'     =>  $payload['FirstName'],
                'LastName'      =>  $payload['LastName'],
                'AddressId'     =>  $payload['AddressId'],
                'Email'         =>  $payload['Email'],
                'Gender'        =>  $payload['Gender'],
                'DateofBirth'   =>  $payload['DateofBirth'],
                'Password'      =>  $payload['Password'],
                'UserTypeId'    =>  $payload['UserTypeId'],
                'UserImage'     =>  $payload['UserImage'],
            ];
            
            $newuser = User::create($dataArray);
            return $newuser;

        } catch (QueryException $e) {
            
            throw new UserException('Exception thrown while trying to create user by query', 50001001);

        } catch (\Exception $e) { 
            throw new UserException($e->getMessage(), 50001001);
        }
    }

    /*
    | Update Exiting User 
    */

    public function updateUser($payload,$user_id, $request,$type='all')
    {
        try {
            $userlist = $this->getUserbyId($user_id);

            // update Value
            if($type=="all")
            {
                /* $userImageArray=(!isset( $payload['imageName'])) ? array() : $this->uploadBase64Image( $payload['imageName']);
                $image = (!isset( $userImageArray['secure_url'])) ?"" :  $userImageArray['secure_url'];
                
                if(isset($imageArray['secure_url']) && $imageArray['secure_url']) {
                    $introgallery->imageName = $imageArray['secure_url'];
                } */

                $userlist->FirstName    = $payload['FirstName'];
                $userlist->LastName     = $payload['LastName'];
                $userlist->AddressId    = $payload['AddressId'];
                $userlist->PhoneNumber  = $payload['PhoneNumber'];
                $userlist->Email        = $payload['Email'];
                $userlist->Gender       = $payload['Gender'];
                $userlist->DateofBirth  = $payload['DateofBirth'];
                $userlist->Password     = $payload['Password'];
                $userlist->UserTypeId   = $payload['UserTypeId'];
                $userlist->UserImage    = $payload['UserImage'];
            }
            if($type=="active")
            {
               $userlist->IsActive = $payload['IsActive'];
            }
            if($type=="delete")
            {
               $userlist->IsDelete = $payload['IsDelete'];
            }

            $userlist->save();
            return $userlist;

        } catch (Exception $e) {
            
             throw new UserException('Exception thrown while trying to update User', 50001001);
        } 
    }

    /*
    |  Delete user
    */ 
    public function deleteUser($id)
    {
        try{
            $userlist = $this->getUserbyId($id);
            if(!empty($userlist)){
                $userlist->delete();    
                return $userlist;
            } else {
                return null;
            }
            //return null;
        } catch (\Exception $e) { // @codeCoverageIgnoreStart

            throw new UserException('Exception thrown while trying to delete user', 50001001);
        }
    }
}


