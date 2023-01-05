<?php

namespace App\Models;

use App\Enums\StorageDiskEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property string $description
 * @property StorageDiskEnum $image_disk
 * @property string $image_extension
 * @property-read string $image_path
 * @property-read string $image_name
 * @property-read string $image_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description',
    ];

    protected $casts = [
        'image_disk' => StorageDiskEnum::class,
    ];

    public function getImagePathAttribute(): string
    {
        return sprintf('products/%s', $this->image_name);
    }

    public function getImageNameAttribute(): string
    {
        return sprintf('%s.%s', $this->sku, $this->image_extension);
    }

    public function getImageUrlAttribute(): string
    {
        return Storage::disk($this->image_disk->value)->url($this->image_path);
    }
}
