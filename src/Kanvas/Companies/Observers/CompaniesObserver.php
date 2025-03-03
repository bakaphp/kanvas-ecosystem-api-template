<?php

declare(strict_types=1);

namespace Kanvas\Companies\Observers;

use Illuminate\Support\Str;
use Kanvas\Apps\Models\Apps;
use Kanvas\Companies\Branches\Actions\CreateCompanyBranchActions;
use Kanvas\Companies\Branches\DataTransferObject\CompaniesBranchPostData;
use Kanvas\Companies\Groups\Actions\CreateCompanyGroupActions;
use Kanvas\Companies\Models\Companies;
use Kanvas\Companies\Models\CompaniesBranches;
use Kanvas\Enums\AppEnums;
use Kanvas\Enums\StateEnums;
use Kanvas\Users\Actions\AssignRole;

class CompaniesObserver
{
    /**
     * Handle the Apps "saving" event.
     */
    public function creating(Companies $company): void
    {
        $company->uuid = Str::uuid()->toString();
    }

    /**
     * Handle the Apps "saving" event.
     */
    public function created(Companies $company): void
    {
        $app = app(Apps::class);
        $user = $company->user()->first();

        $app->associateCompany($company);

        $createCompanyGroup = new CreateCompanyGroupActions($company, $app);
        $createCompanyGroup->execute($company->name, StateEnums::ON->getValue());

        $createCompanyBranch = new CreateCompanyBranchActions(
            $user,
            new CompaniesBranchPostData(
                AppEnums::DEFAULT_NAME->getValue(),
                $company->id,
                $company->users_id,
                StateEnums::YES->getValue(),
                $company->email
            )
        );

        $branch = $createCompanyBranch->execute();

        //associate to all branches of this company
        $tempBranch = new CompaniesBranches();
        $tempBranch->id = 0;
        $company->associateUser(
            $user,
            StateEnums::ON->getValue(),
            $tempBranch
        );

        $company->associateUser(
            $user,
            StateEnums::ON->getValue(),
            $branch
        );

        $company->associateUserApp(
            $user,
            $app,
            StateEnums::ON->getValue()
        );

        $assignRole = new AssignRole($user, $company, $app);
        $assignRole->execute(AppEnums::DEFAULT_ROLE_NAME->getValue());

        if (! $user->get(Companies::cacheKey())) {
            $user->set(Companies::cacheKey(), $company->id);
        }

        if (! $user->get($company->branchCacheKey())) {
            $user->set($company->branchCacheKey(), $branch->id);
        }
    }
}
