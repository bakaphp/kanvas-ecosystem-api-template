<?php

declare(strict_types=1);

namespace Kanvas\Users\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kanvas\Companies\Models\Companies;
use Kanvas\Companies\Models\CompaniesBranches;
use Kanvas\Models\BaseModel;

/**
 * Model UsersInvite.
 *
 * @property string $invite_hash;
 * @property int $users_id;
 * @property int $companies_id;
 * @property int $companies_branches_id;
 * @property int $role_id
 * @property int $apps_id
 * @property int $email
 * @property ?string $firstname
 * @property ?string $lastname
 * @property ?string $description
 */
class UsersInvite extends BaseModel
{
    public $table = 'users_invite';

    protected $guarded = [];

    /**
     * Not deleted scope.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeCompany(Builder $query): Builder
    {
        return $query->where('companies_id', auth()->user()->defaultCompany->id);
    }

    /**
     * Get the user that owns the UsersInvite.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'users_id');
    }

    /**
     * Belongs to company.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'companies_id');
    }

    /**
     * Belongs to branch.
     *
     * @return BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(CompaniesBranches::class, 'companies_branches_id');
    }
}
