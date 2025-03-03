<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kanvas\Apps\Enums\DefaultRoles;
use Kanvas\Apps\Models\Apps;
use Kanvas\Companies\Models\CompaniesBranches;
use Kanvas\Users\Actions\AssignCompanyAction;
use Kanvas\Users\Actions\RemoveCompanyAction;
use Kanvas\Users\Repositories\UsersRepository;

class KanvasUserRemoveFromCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanvas:users-remove {apps_id} {email} {branch_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove User from Company';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $app = Apps::getById((int) $this->argument('apps_id'));
        $email = $this->argument('email');
        $branchId = $this->argument('branch_id');

        $branch = CompaniesBranches::findOrFail($branchId);
        $company = $branch->company()->first();

        $assignCompanyAction = new RemoveCompanyAction(
            UsersRepository::getByEmail($email),
            $branch,
            $app
        );
        $assignCompanyAction->execute();

        $this->newLine();
        $this->info("User {$email} successfully remove from branch : " . $branch->name . ' ( ' . $branch->getKey() . ') in app  ' . $app->name);
        $this->newLine();
    }
}
