<?php

use App\Karina\Event;
use App\Karina\RegistrationType;
use App\Karina\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $user = factory(User::class)->create();

        $startAt = (new DateTime())->modify('+1 day');
        $endAt = (new DateTime())->modify('+5 day');
        factory(Event::class, 20)->create([
            'user_id' => $user->id,
            'title' => 'Event name',
            'description' => 'Event description',
            'place' => 'Place',
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
        ])->each(function ($event) {
            factory(RegistrationType::class, 2)->create([
                'event_id' => $event->id,
            ]);
        });
    }
}
