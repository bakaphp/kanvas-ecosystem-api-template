<?php

declare(strict_types=1);

namespace Kanvas\Models;

use Baka\Traits\KanvasModelTrait;
use Baka\Traits\KanvasScopesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Kanvas\Traits\SoftDeletes;

class BaseModel extends EloquentModel
{
    use HasFactory;
    use KanvasModelTrait;
    use KanvasScopesTrait;
    //use SoftDeletes;

    protected $connection = 'mysql';

    protected $attributes = [
        'is_deleted' => 0,
    ];
}
