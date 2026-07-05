<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Company extends Model
{
	use HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }

	public function invitations()
	{
	    return $this->hasMany(Invitation::class);
	}


}
