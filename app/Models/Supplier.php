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
     * @param array{page: int|null, per_page: int|null, document_number: string|null} $filters
     * @return Supplier[]
     */
    protected function scopeSearchWithCache(Builder $query, array $filters): array
    {
        $filters = ['page' => 1, 'per_page' => 10, 'document_number' => false, ...$filters];

        if (config('cache_suppliers_filtered')) {
            return $this->cacheFiltered($query, $filters);
        }

        return $this->cacheAll($query, $filters);
    }

    /**
     * @param  Builder<Supplier>  $query
     * @param array{page: int|null, per_page: int|null, document_number: string|null} $filters
     * @return Supplier[]
     */
    protected function cacheFiltered(Builder $query, array $filters): array
    {
        return cache()->tags('suppliers')->rememberForever(
            "suppliers::" . serialize($filters),
            fn () => $query->search($filters)->simplePaginate($filters['per_page'])->items()
        );
    }

    /**
     * @param  Builder<Supplier>  $query
     * @param array{page: int|null, per_page: int|null, document_number: string|null} $filters
     * @return Supplier[]
     */
    protected function cacheAll(Builder $query, array $filters): array
    {
        return cache()->tags('suppliers')->rememberForever("suppliers::all", fn () => $query->get())
            ->when(
                $filters['document_number'],
                fn ($suppliers) => $suppliers->where('document_number', $filters['document_number'])
            )
            ->slice(($filters['page'] - 1) * $filters['per_page'], $filters['per_page'])->all();
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
