<?php

declare(strict_types=1);

namespace Kanvas\Companies\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Kanvas\Companies\DataTransferObject\CompaniesPutData;
use Kanvas\Companies\Models\Companies;
use Kanvas\Guild\Customers\Models\People;
use Kanvas\Guild\Leads\Models\Lead;
use Kanvas\Inventory\Attributes\Models\Attributes;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Warehouses\Models\Warehouses;
use Kanvas\Users\Models\Users;

class DeleteCompaniesAction
{
    /**
     * Construct function.
     *
     * @param CompaniesPutData $data
     */
    public function __construct(
        protected Users $user
    ) {
    }

    /**
     * Invoke function.
     */
    public function execute(int $id): bool
    {
        $companies = Companies::getById($id);

        if (! $companies->isOwner($this->user)) {
            throw new AuthorizationException('User cant delete this company');
        }

        DB::transaction(function () use ($companies) {
            $companies->softDelete();
        });

        return true;
    }
}
