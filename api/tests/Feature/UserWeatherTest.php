<?

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserWeatherTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting a list of users with weather data.
     *
     * @return void
     */
    public function test_get_all_users_weather_list()
    {
        // Create some users with known coordinates
        $users = User::factory(3)->create();

        // Call the user weather API and check the response
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertJsonCount(count($users), 'data');
        $response->assertJsonFragment(['message' => 'Users Loaded Successfully!']);
        $response->assertJsonStructure([
            "message",
            "data" => [
                [
                    "id",
                    "weather" => [
                        "weather_data" => [
                            "coord" => [
                                "lon",
                                "lat"
                            ],
                            "weather",
                            "timezone",
                        ],
                        "updated_time"
                    ]
                ]
            ]
        ]);
    }
    public function test_get_user_weather()
    {
        // Create 3 users
        User::factory(3)->create();
        //    get a random user
        $user = User::all()->random();
        // Call the user weather API and check the response
        $response = $this->get('/' . $user->id);

        $response->assertStatus(200);

        $response->assertJsonFragment(['message' => "User Loaded Successfully!"]);
        $response->assertJsonStructure([
            "message",
            "data" => [
                "id",
                "weather" => [
                    "weather_data" => [
                        "coord" => [
                            "lon",
                            "lat"
                        ],
                        "weather",
                        "timezone",
                    ],
                    "updated_time"
                ]
            ]
        ]);
    }
    public function test_get_user_weather_of_non_existent_user()
    {
        // Create 3 user
        User::factory(3)->create();
        $nonExistentId = User::max('id') + 100;
        // Call the get user by id route for non existent user 
        $response = $this->get('/' . $nonExistentId);

        $response->assertStatus(404);

        $response->assertJsonFragment(['message' => 'User Not Found']);
        $response->assertJsonStructure([
            "message",
            "data"
        ]);
    }
    public function test_force_update_user_weather()
    {
        // create 2 users
        User::factory(2)->create();
        // get a Random user
        $user = User::all()->random();
        // Get Current Weather of the random user
        $currentWeather = $this->get('/' . $user->id);
        // Sleep 15 second for current data to get old
        sleep(15);


        // Call the forceUpdate api for the random user
        $response = $this->put('/forceUpdate/' . $user->id);

        $response->assertStatus(200);
        $this->assertNotEquals($response->json()['data'], $currentWeather->json()['data']);

        // Check the force updated id matched the id of user id 1.
        $response->assertJsonPath('data.name', $user->name);
        // Check if the message is 'Weather Updated Successfully!'
        $response->assertJsonFragment(['message' => 'Weather Updated Successfully!']);
        // Check if the Json Structure matches or not
        $response->assertJsonStructure([
            "message",
            "data" => [
                "id",
                "weather" => [
                    "weather_data" => [
                        "coord" => [
                            "lon",
                            "lat"
                        ],
                        "weather",
                        "timezone",
                    ],
                    "updated_time"
                ]
            ]
        ]);
    }
    public function test_force_update_user_weather_for_non_existent_user()
    {
        // create 3 users
        User::factory(3)->create();
        // get nonexistent user ID
        $nonExistentId = User::max('id') + 100;
        // Call the forceUpdate api for user of id 3000 , that doesn't exist
        $response = $this->put('/forceUpdate/' . $nonExistentId);
        $response->assertStatus(404);

        // Check if the message is 'User Not Found'
        $response->assertJsonFragment(['message' => 'User Not Found']);
        // Check if the Json Structure matches or not
        $response->assertJsonStructure([
            "message",
            "data"
        ]);
    }
    // public function testGetNonExistentUserWeather()
    // {
    //     // Call the user weather API for a non-existent user and check the response
    //     $response = $this->get('/');

    //     $response->assertStatus(404);
    // }
}
