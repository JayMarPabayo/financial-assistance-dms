<?php

namespace Database\Seeders;

use App\Models\Request;
use App\Models\Service;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create(['name' => 'John S. Doe', 'username' => 'admin', 'role' => 'Administrator']);

        Service::factory()->create([
            'name' => 'Burial Assistance',
            'description' => 'Provides burial assistance for poor, marginalized, vulnerable, and disadvantaged individuals.',
            'eligibility' => 'The Burial Assistance aids indigents and the marginalized to help them with the burial costs of deceased family members. Applications must be submitted within 15 calendar days (including weekends and holidays) from the date of death, then the Burial Assistance shall be provided to eligible clients, for a maximum amount of ₱5,000.00.',
            'status' => 'Available',
            'requirements' => '1. Original copy of the filled-out Burial Application form, signed by the Authorized Representative.
2. Original Copy of the Social Case Study Report or Certificate of Indigency/ Eligibility.
3. Photocopy of Registered Death Certificate
4. Photocopy of at least one (1) valid Identification Card of Deceased and Authorized Representative'
        ]);

        Service::factory()->create([
            'name' => 'Calamity Assistance',
            'description' => 'To provide cash assistance to all full-fledged members of affected by unforeseen calamities such as fire, earthquake, typhoon and flood.',
            'eligibility' => '1. Must be a full-fledged member.
2. Must be a resident of the calamity declared areas and suffered damages/loss to their properties.
3. Must be claim not more than three (3) months after the calamity.',
            'status' => 'Available',
            'requirements' => '1. Duly filled-out Calamity Assistance Benefit Form.
2. Certificate from the Zone Leader and/or Barangay indicating if the member suffered damages/loss of properties either totally or partially damaged.
3. Photocopy of member’s valid ID (1 government or 2 secondary ID) with 3 signature specimens.
4. Photocopy of authorized person’s valid ID (1 government or 2 secondary ID) with 3 signature specimens. (if through authorization)
5. Authorization letter duly signed by the member. (if through authorization)'
        ]);

        Request::factory(25)->create();
    }
}
