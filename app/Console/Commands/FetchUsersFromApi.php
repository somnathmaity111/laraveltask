<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class FetchUsersFromApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:users-from-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch 5 users from API and save to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiUrl = Config::get('app.get_users_api_link');

        $response = Http::get($apiUrl, ['results' => 5]);

        if ($response->successful()) {
            $users = $response->json()['results'];

            foreach ($users as $userData) {
                $user = User::create([
                    'name' => $userData['name']['first'].' '.$userData['name']['last'],
                    'email' => $userData['email'],
                ]);

                UserDetail::create([
                    'user_id' => $user->id,
                    'gender' => $userData['gender'],
                ]);

                Location::create([
                    'user_id' => $user->id,
                    'city' => $userData['location']['city'],
                    'country' => $userData['location']['country'],
                ]);
            }

            $this->info('5 users added successfully!');
        } else {
            $this->error('Failed to fetch users.');
        }
    }
}
