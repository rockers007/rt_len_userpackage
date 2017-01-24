<?php
namespace module\users\Models;

use Illuminate\Database\Eloquent\Model;
use module\users\Models\User;

class User extends Model
{
    protected $table = 'Users';
    protected $primaryKey = 'Id';
    
    protected $fillable = ['Id','UserId','FirstName','LastName','AddressId','PhoneNumber','Email','Gender','DateofBirth','Password','UserTypeId','UserImage','IsActive','IsDelete'];
    protected $visible = ['Id','UserId','FirstName','LastName', 'AddressId','PhoneNumber','Email','Gender','DateofBirth','Password','UserTypeId','UserImage','IsActive','IsDelete'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
}