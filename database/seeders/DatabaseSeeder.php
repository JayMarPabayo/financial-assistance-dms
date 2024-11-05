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

        User::factory()->create(['name' => 'John S. Doe', 'username' => 'staff', 'email' => 'jaymarpabayo@gmail.com', 'role' => 'Staff']);
        User::factory()->create(['name' => 'Jai Pastrana', 'username' => 'admin', 'email' => 'jaymarpabayo@gmail.com', 'role' => 'Administrator']);

        Service::factory()->create([
            'name' => 'Burial Assistance',
            'description' => 'Provides burial assistance for poor, marginalized, vulnerable, and disadvantaged individuals.',
            'eligibility' => 'The Burial Assistance aids indigents and the marginalized to help them with the burial costs of deceased family members. Applications must be submitted within 15 calendar days (including weekends and holidays) from the date of death, then the Burial Assistance shall be provided to eligible clients, for a maximum amount of ₱5,000.00.',
            'status' => 'Available'
            //             'requirements' => '1. Death Certificate (Original)
            // 2. Funeral Contract (Original)
            // 3. Certificate of Indigency (Original) - Name of the person who process
            // 4. Valid I.D (Person who process and the person who died)
            // 5. Letter of Intent (Sulat Hangyo)'
        ]);

        Service::factory()->create([
            'name' => 'Calamity Assistance',
            'description' => 'To provide cash assistance to all full-fledged members of affected by unforeseen calamities such as fire, earthquake, typhoon and flood.',
            'eligibility' => '1. Must be a full-fledged member.
2. Must be a resident of the calamity declared areas and suffered damages/loss to their properties.
3. Must be claim not more than three (3) months after the calamity.',
            'status' => 'Available'
            //             'requirements' => '1. Brgy. Certification (Original) - indicating the name of member who suffered damage/loss of properties.
            // 2. Brgy. Indigency (Original)
            // 3. Valid I.d
            // 4. Letter of Intent (Sulat hangyo)'
        ]);

        Service::factory()->create([
            'name' => 'Hospital Bill Assistance',
            'description' => 'To provide to all individuals with health-related problems seeking financial help, in partnership with government and private hospitals, health facilities and other partners.',
            'eligibility' => 'The target beneficiaries of the hospital bill assistance program are individuals and families who are identified as poor, vulnerable, and marginalized. This includes low-income individuals and indigenous people. This program aims to provide financial assistance for their medical needs, ensuring that they have access to quality healthcare services regardless of their socio-economic status.',
            'status' => 'Available'
            //             'requirements' => '1. Updated Hospital Bill (Original) Hospital
            // 2. Medical Abstract (Original) Hospital
            // 3. Social Case Study (Original) Hospital
            // 4. Justification Letter (Original) Hospital
            // 5. Brgy. Indigency
            // 6. Letter of Intent (sulat hangyo)
            // 7. Valid I.D'
        ]);

        Service::factory()->create([
            'name' => 'Medicine Assistance',
            'description' => 'Provides medicine assistance to all indigent people who does not have enough financial to support their medicine. This helps individuals that already in a state of maintenance to survive in their everyday lives.',
            'eligibility' => 'Belongs to the informal sector. Other poor, marginalized and vulnerable/disadvantaged individual. Provides medicine assistance for poor, marginalized, vulnerable, and disadvantaged individuals.',
            'status' => 'Available'
            //             'requirements' => '1. Prescription with price (Resita nga naay presyo)
            // 2. Medical abstract (Doctor who give you the prescription)
            // 3. Brgy. Indigency
            // 4. Letter of Intent (Sulat hangyo)
            // 5. Valid I.D'
        ]);

        Service::factory()->create([
            'name' => 'Educational Assistance',
            'description' => 'Provides educational assistance support to economically disadvantaged individuals, particularly children and youth, to ensure their access to quality education.',
            'eligibility' => 'The program is available for both senior high school and college/university students. Applicants must be part of a household identified by the National Household Targeting System for Poverty Reduction as poor or near-poor.',
            'status' => 'Available'
            //             'requirements' => '1. COR (Certificate of registration)
            // 2. School I.D
            // 3. Brgy. Indigency
            // 4. Letter of Intent (Sulat hangyo)'
        ]);

        Service::factory()->create([
            'name' => 'Solicitation',
            'description' => 'Solicitation for Fiesta, Brgy. Activities and etc.',
            'eligibility' => 'Must be a bonified individuals around Misamis Oriental only.',
            'status' => 'Available'
            // 'requirements' => '1. Solicitation letter'
        ]);

        Service::factory()->create([
            'name' => 'Logistics',
            'description' => 'Provides Chairs, Tables and Venue if available to their desired date.',
            'eligibility' => 'For residents of Tagoloan only',
            'status' => 'Available'
            // 'requirements' => '1. Letter - please indicate the number of participants, date and time of the said events.'
        ]);

        Service::factory()->create([
            'name' => 'Transportation Assistance',
            'description' => 'Provides free transportation assistance to those people who does not have enough financial to buy tickets.',
            'eligibility' => 'Indigent people who wants to go back to their families. Eligible to all indigent people around Misamis Oriental.
Providing chairs and tables in their availability.',
            'status' => 'Available'
            //             'requirements' => '1. Police Blotter
            // 2. Brgy. Indigency
            // 3. Valid I.D
            // 4. Letter of Intent (sulat hangyo)'
        ]);

        Request::factory(5)->create();
    }
}
