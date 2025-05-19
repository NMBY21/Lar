<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'image_path',
    ];

    public function getPriceAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getStockAttribute($value)
    {
        return (int) $value;
    }
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y H:i');
    }
    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y H:i');
    }
    public function getTitleAttribute($value)
    {
        return ucwords($value);
    }
    public function getDescriptionAttribute($value)
    {
        return ucfirst($value);
    }
    public function getIdAttribute($value)
    {
        return (int) $value;
    }
    public function getImageAttribute($value)
    {
        return url('storage/' . $value);
    }
    public function getImageUrlAttribute()
    {
        return $this->image ? url('storage/' . $this->image) : null;
    }
    public function getImagePathAttribute()
    {
        return $this->image ? storage_path('app/public/' . $this->image) : null;
    }
    public function getImageSizeAttribute()
    {
        return $this->image ? \File::size($this->getImagePathAttribute()) : null;
    }
    public function getImageTypeAttribute()
    {
        return $this->image ? \File::mimeType($this->getImagePathAttribute()) : null;
    }
    public function getImageExtensionAttribute()
    {
        return $this->image ? \File::extension($this->getImagePathAttribute()) : null;
    }

}
