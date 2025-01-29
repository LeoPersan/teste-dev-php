<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'document_type',
        'document_number',
        'email',
        'phone',
        'address',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function documentNumber(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => preg_replace('/[^0-9]/', '', $value),
        );
    }

    public function phone(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => preg_replace('/[^0-9]/', '', $value),
        );
    }

    public function scopeSearch($query, array $filters)
    {
        $query->when(
            $filters['document_number'] ?? null,
            fn ($query, $documentNumber) => $query->documentNumber($documentNumber)
        );
    }

    public function scopeDocumentNumber($query, $documentNumber)
    {
        return $query->where('document_number', preg_replace('/[^0-9]/', '', $documentNumber));
    }
}
