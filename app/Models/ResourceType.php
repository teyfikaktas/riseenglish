<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ResourceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',  // Bu alanları kullanacaksanız ekleyin
    ];

    // Otomatik slug oluşturma
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($resourceType) {
            // Eğer slug belirtilmemişse, name'den oluştur
            if (!$resourceType->slug) {
                $resourceType->slug = Str::slug($resourceType->name);
            }
        });
    }

    public function resources()
    {
        return $this->hasMany(Resource::class, 'type_id');
    }
}