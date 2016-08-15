<?php

namespace Med\Console\Commands;

use App\User as UserModel;
use Illuminate\Console\Command;
use Med\Services\MediumService;

class VerifyAuthTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medium:verify-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifies authentication for authors in our system';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $authenticatedUsers = UserModel::all()->map(function ($user) {
            try {
                new MediumService($user->medium_token);

                return ['name' => $user->name, 'authenticated' => 'Yes'];
            } catch (\Exception $e) {
                return ['name' => $user->name, 'authenticated' => 'No'];
            }
        });

        $this->table(['Name', 'Authenticated?'], $authenticatedUsers->toArray());
    }
}
