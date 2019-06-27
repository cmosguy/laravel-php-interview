<?php


namespace App\Models\Base;


use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

abstract class UuidModel extends Model
{
    use HasBinaryUuid;

    public $incrementing = false;

    public function getKeyName(): string
    {
        return 'guid';
    }
}
