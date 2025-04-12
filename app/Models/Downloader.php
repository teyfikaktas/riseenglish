<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Downloader extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname',
        'email',
        'phone',
        'is_subscribed',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_subscribed' => 'boolean',
    ];

    /**
     * Get the resource downloads for the downloader.
     */
    public function downloads()
    {
        return $this->hasMany(ResourceDownload::class);
    }
}