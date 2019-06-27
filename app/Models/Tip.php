<?php


namespace App\Models;


use App\Models\Base\UuidModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string      guid
 * @property string      guid_text Use this when accessing from object
 * @property string      title
 * @property string      description
 * @property Carbon      created_at
 * @property Carbon      updated_at
 * @property Carbon|null deleted_at
 */
class Tip extends UuidModel
{
    use SoftDeletes;

    protected $table = 'tips';

    protected $fillable = ['title', 'description'];
}
