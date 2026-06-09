<?php

namespace Database\Seeders;

use App\Models\Registration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RegistrationSeeder extends Seeder
{
    /**
     * Seed sample registrations so the admin dashboard charts/stats are populated.
     * Image paths are left null on purpose — that skips the WebP optimizer hook
     * and avoids missing-file errors for synthetic rows.
     */
    public function run(): void
    {
        $types     = ['Men', 'Women', 'Boy', 'Girl'];
        $events    = ['Senior', 'Junior', 'Sub-Junior'];
        $districts = [
            'Patna', 'Gaya', 'Begusarai', 'Muzaffarpur', 'Bhagalpur',
            'Darbhanga', 'Nalanda', 'Saran', 'Purnia', 'Rohtas',
        ];
        $clubs = [
            'SOS Begusarai', 'City Sports Club', 'Patna Handball Academy',
            'Bihar Youth Club', 'Ganga Sporting', 'United Players',
        ];
        $first = ['Aman', 'Rahul', 'Payal', 'Sneha', 'Vikram', 'Pooja', 'Ravi', 'Anjali', 'Suraj', 'Kiran', 'Manish', 'Priya'];
        $last  = ['Kumar', 'Singh', 'Devi', 'Raj', 'Sharma', 'Yadav', 'Prasad', 'Verma'];

        for ($i = 0; $i < 45; $i++) {
            $type = $types[array_rand($types)];
            // Boys/Girls skew to junior levels, Men/Women span all levels.
            $pool  = in_array($type, ['Boy', 'Girl'], true)
                ? ['Junior', 'Junior', 'Sub-Junior']
                : $events;
            $event = $pool[array_rand($pool)];

            $createdAt = Carbon::now()
                ->subDays(random_int(0, 120))
                ->setTime(random_int(8, 20), random_int(0, 59));

            Registration::create([
                'registration_type' => $type,
                'event_type'        => $event,
                'first_name'        => $first[array_rand($first)],
                'middle_name'       => random_int(0, 1) ? $first[array_rand($first)] : null,
                'last_name'         => $last[array_rand($last)],
                'dob'               => Carbon::now()->subYears(random_int(10, 30))->subDays(random_int(0, 364))->toDateString(),
                'email'             => 'player' . $i . '@example.com',
                'father_name'       => $first[array_rand($first)] . ' ' . $last[array_rand($last)],
                'mother_name'       => $first[array_rand($first)] . ' ' . $last[array_rand($last)],
                'address'           => 'House ' . random_int(1, 200) . ', Ward ' . random_int(1, 20),
                'village_city'      => $districts[array_rand($districts)],
                'state'             => 'Bihar',
                'district'          => $districts[array_rand($districts)],
                'club1'             => $clubs[array_rand($clubs)],
                'club2'             => random_int(0, 1) ? $clubs[array_rand($clubs)] : null,
                'pincode'           => (string) random_int(800001, 855117),
                'country'           => 'India',
                'aadhaar'           => (string) random_int(100000000000, 999999999999),
                'mobile'            => '9' . str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT),
                'photo_path'        => 'registrations/photos/sample.jpg',
                'signature_path'    => 'registrations/signatures/sample.jpg',
                'created_at'        => $createdAt,
                'updated_at'        => $createdAt,
            ]);
        }

        $this->command?->info('Seeded 45 sample registrations.');
    }
}
