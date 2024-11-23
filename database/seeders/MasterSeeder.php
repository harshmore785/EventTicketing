<?php

namespace Database\Seeders;

use App\Models\TicketType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Store Ticket Types
        $ticketTypes = [
            [
                'id'            => 1,
                'name'          => 'Early Bird',
                'description'   => '-',
            ],
            [
                'id'            => 2,
                'name'          => 'Regular',
                'description'   => '-',
            ],
            [
                'id'            => 3,
                'name'          => 'VIP',
                'description'   => '-',
            ],
        ];

        foreach ($ticketTypes as $ticketType)
        {
            TicketType::updateOrCreate([
                'id' => $ticketType['id']
            ], [
                'id'                => $ticketType['id'],
                'name'              => $ticketType['name'],
                'description'       => $ticketType['description']
            ]);
        }
    }
}
