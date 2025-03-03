<?php

declare(strict_types=1);

namespace Kanvas\AccessControlList\Actions;

use Bouncer;
use Illuminate\Support\Facades\Validator;
use Kanvas\AccessControlList\Enums\RolesEnums;
use Kanvas\Apps\Models\Apps;
use Kanvas\Companies\Models\Companies;
use Kanvas\Exceptions\ValidationException;
use Silber\Bouncer\Database\Role as SilberRole;

class CreateRoleAction
{
    /**
     * __construct.
     *
     * @return void
     */
    public function __construct(
        public string $name,
        public string $title,
        public ?Apps $app = null
    ) {
        $this->app = $app ?? app(Apps::class);
    }

    /**
     * execute.
     */
    public function execute(?Companies $company = null): SilberRole
    {
        //Bouncer::scope()->to(RolesEnums::getScope($this->app, $company));
        Bouncer::scope()->to(RolesEnums::getScope($this->app));

        $validator = Validator::make(
            [
                'name' => $this->name
            ],
            [
                'name' => 'required|unique:roles,name,null,id,scope,' . RolesEnums::getScope($this->app),
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->first() . 'for roles in the current app');
        }

        $role = Bouncer::role()->firstOrCreate([
            'name' => $this->name,
            'title' => $this->title,
        ]);

        return $role;
    }
}
