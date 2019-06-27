<?php


namespace V1\DAL\Concrete;


use App\DAL\BaseEloquentUuid;
use App\DAL\Contracts\TipRepository;
use App\Models\Tip;

class EloquentTip extends BaseEloquentUuid implements TipRepository
{
    public function __construct(Tip $tip)
    {
        parent::__construct($tip);
    }
}
