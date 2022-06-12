<?php

namespace Tests\Feature;

use App\Models\Client;
use Tests\TestCase;
use Faker\Factory as Faker;

class ClientTest extends TestCase
{
    public function test_can_view_all_clients()
    {
        $limit = rand(5, 15);
        $offset = rand(0,3);

        $response = $this->getJson("/api/clients?limit={$limit}&offset={$offset}");

        $response->assertStatus(200);
    }

    public function test_can_create_a_new_client()
    {
        $faker = Faker::create();

        $data = [
            "name" => $faker->name,
            "gender" => Client::$gender[rand(0,2)],
            "email" => $faker->email,
            "phone" => "+977-981234".rand(1000, 9999),
            "address" => $faker->address,
            "nationality" => "nepali",
            "dob" => now()->format("d/m/Y"),
            "educational_background" => Client::$educational_backgrounds[rand(0, 6)],
            "preferred_mode_of_contact" => Client::$preffered_mode_of_contact[rand(0, 2)]
        ];

        $response = $this->postJson("/api/clients", $data);

        $response->assertStatus(200);
    }
}
