<?php

namespace Database\Seeders;

use App\Models\Enquiry;
use Illuminate\Database\Seeder;

class EnquirySeeder extends Seeder
{
    public function run(): void
    {
        $enquiries = [
            [
                'name' => 'Layla Hassan',
                'email' => 'layla.demo@example.com',
                'phone' => '+971500000001',
                'subject' => 'VAYU R1 1100 showroom visit',
                'enquiry_type' => 'test_drive',
                'message' => 'I would like to schedule a showroom visit for the VAYU R1 1100 this weekend.',
                'status' => 'pending',
            ],
            [
                'name' => 'Omar Farid',
                'email' => 'omar.demo@example.com',
                'phone' => null,
                'subject' => 'Service consultation',
                'enquiry_type' => 'service',
                'message' => 'Looking for maintenance packages available for adventure motorcycles in the demo catalogue.',
                'status' => 'contacted',
            ],
            [
                'name' => 'Sara Alami',
                'email' => 'sara.demo@example.com',
                'phone' => '+966500000002',
                'subject' => 'Parts availability',
                'enquiry_type' => 'parts',
                'message' => 'Do you stock demo spare-part kits for ATLAS adventure models?',
                'status' => 'pending',
            ],
            [
                'name' => 'James Carter',
                'email' => 'james.demo@example.com',
                'phone' => null,
                'subject' => 'General enquiry',
                'enquiry_type' => 'general',
                'message' => 'Please share opening hours for the fictional MOTOVEX showroom experience.',
                'status' => 'completed',
            ],
        ];

        foreach ($enquiries as $enquiry) {
            Enquiry::create($enquiry);
        }
    }
}
