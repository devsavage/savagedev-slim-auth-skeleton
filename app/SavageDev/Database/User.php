<?php
namespace SavageDev\Database;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "users";

    protected $fillable = ["username", "email", "password"];
}
