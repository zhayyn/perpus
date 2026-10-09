<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LibrarySetting extends Model
{
    protected $table    = 'library_settings';
    protected $fillable = ['key', 'value', 'type', 'label', 'description', 'group'];

    /**
     * Ambil nilai setting berdasarkan key.
     * Kita cache hasil akhir (primitive value) bukan Model instance-nya, 
     * untuk mencegah error unserialize pada Livewire/Redis.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("library_setting_val_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            if (!$setting) return $default;

            return match ($setting->type) {
                'integer' => (int) $setting->value,
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                default   => $setting->value,
            };
        });
    }

    /**
     * Simpan nilai setting dan hapus cache-nya.
     */
    public static function set(string $key, mixed $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
        Cache::forget("library_setting_val_{$key}");
        Cache::forget("library_setting_{$key}"); // Hapus juga cache model yang lama
    }
}
