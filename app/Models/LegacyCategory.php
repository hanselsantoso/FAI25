<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegacyCategory extends Model
{
    use HasFactory;

    /**
     * Menyesuaikan nama tabel karena tidak mengikuti konvensi plural.
     */
    protected $table = 'legacy_categories';

    /**
     * Primary key tabel berupa string custom.
     */
    protected $primaryKey = 'code';

    /**
     * Primary key bukan auto increment dan bertipe string.
     */
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Tabel tidak memiliki kolom created_at dan updated_at.
     */
    public $timestamps = false;

    protected $fillable = [
        'code',
        'title',
        'is_active',
    ];

    /**
     * Contoh accessor untuk label yang siap pakai di tampilan.
     */
    public function getLabelAttribute(): string
    {
        return sprintf('%s — %s', $this->code, $this->title);
    }

    /**
     * Contoh scope untuk mengambil data aktif saja.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
