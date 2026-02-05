<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            $tenant = Tenant::create([
                'name' => $input['name'] . "'s Workspace",
            ]);

            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
                'tenant_id' => $tenant->id,
                'role' => 'admin',
            ]);

            $tenant->update(['owner_id' => $user->id]);

            TenantService::createDefaultBoardType($tenant);

            return $user;
        });
    }
}
