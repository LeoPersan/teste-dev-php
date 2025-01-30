<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;

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

    /**
     * @return Attribute<callable, callable>
     */
    public function documentNumber(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => preg_replace('/[^0-9]/', '', $value),
        );
    }

    /**
     * @return Attribute<callable, callable>
     */
    public function phone(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => preg_replace('/[^0-9]/', '', $value),
        );
    }

    /**
     * @param  Builder<Supplier>  $query
     * @param  array<string, mixed>  $filters
     */
    public function scopeSearch(Builder $query, array $filters): void
    {
        $query->when(
            $filters['document_number'] ?? '',
            fn ($query, $documentNumber) => is_string($documentNumber) && $query->documentNumber($documentNumber)
        );
    }

    /**
     * @param  Builder<Supplier>  $query
     * @param  string  $documentNumber
     */
    public function scopeDocumentNumber(Builder $query, string $documentNumber): void
    {
        $query->where('document_number', preg_replace('/[^0-9]/', '', $documentNumber));
    }

    protected static function booted(): void
    {
        static::saved(fn () => cache()->tags('suppliers')->flush());
        static::deleted(fn () => cache()->tags('suppliers')->flush());
        static::updated(fn () => cache()->tags('suppliers')->flush());
    }
}
