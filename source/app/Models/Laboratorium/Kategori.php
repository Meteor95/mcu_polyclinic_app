<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'lab_kategori';
    protected $fillable = ['nama_kategori', 'parent_id', 'grup_kategori'];
    public function parent()
    {
        return $this->belongsTo(Kategori::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Kategori::class, 'parent_id');
    }
}
