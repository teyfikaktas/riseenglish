<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceDownload extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'downloader_id',
        'resource_id',
        'ip_address',
    ];

    /**
     * Get the resource that was downloaded.
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    /**
     * Get the downloader that downloaded the resource.
     */
    public function downloader()
    {
        return $this->belongsTo(Downloader::class);
    }
}