<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Observers\FolderObserver;
use App\Traits\HandlesPaths;
use App\Traits\HashesId;

class Folder extends Model
{
    use HandlesPaths, HashesId;

    protected $table = 'files';

    
    protected $fillable = [
        'name',
        'description',
        'path',
        'file_name',
        'extension',
        'mime',
        'type',
        'public_path',
        'parent_id',
        'driver',
        'driver_data',
        'uploaded_by',
        'meta',
    ];


    protected $casts = [
        'id' => 'integer',
        'file_size' => 'integer',
        'parent_id' => 'integer'
    ];

    protected $attributes = ['type' => 'folder'];

    protected $appends = [
        'hash'
    ];

    public function newQuery( $except_deleted = true ) {
        return parent::newQuery( $except_deleted )->where( 'type', '=', 'folder' );
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        Folder::observe(FolderObserver::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id')->withoutGlobalScope('fsType');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }
}
