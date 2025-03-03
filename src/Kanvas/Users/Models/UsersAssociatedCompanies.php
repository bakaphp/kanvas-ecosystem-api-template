<?php

declare(strict_types=1);

namespace Kanvas\Users\Models;

use Baka\Traits\HasCompositePrimaryKeyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kanvas\Companies\Models\Companies;
use Kanvas\Models\BaseModel;

/**
 * UsersAssociatedCompanies Model.
 *
 * @property int $users_id
 * @property int $companies_id
 * @property int $companies_branches_id
 * @property string $identify_id
 * @property int $user_active
 * @property string $user_role
 * @property string $password
 */
class UsersAssociatedCompanies extends BaseModel
{
    use HasCompositePrimaryKeyTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_associated_company';

    protected $primaryKey = ['users_id', 'companies_id', 'companies_branches_id'];

    protected $fillable = [
        'users_id',
        'companies_id',
        'companies_branches_id',
        'identify_id',
        'user_active',
        'user_role',
    ];

    /**
     * Users relationship.
     *
     * @return Users
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'users_id');
    }

    /**
     * Users relationship.
     *
     * @return Companies
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'companies_id');
    }
}
