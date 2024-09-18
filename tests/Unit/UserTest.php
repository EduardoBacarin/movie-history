<?php

namespace Tests\Unit;

use App\Http\Requests\CreateUserRequest;
use App\Models\User as ModelsUser;
use Tests\TestCase;
use App\Services\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserTest extends TestCase {
    private $data = [
        "_id" => "userId",
        "name" => "John Doe",
        "email" => "john@doe.com"
    ];

    public function setUp(): void{
        parent::setUp();
        DB::collection("users")->where("_id", $this->data['_id'])->delete();
        ModelsUser::where("_id", hash("sha256", "email:john@doe2.com"))->delete();
        DB::collection("users")->insert($this->data);
    }
    public function test_create_user_success(): void {

        $data = [
            "name" => "John Doe",
            "email" => "john@doe2.com",
            "password" => "johndoe123"
        ];

        $service = new User();
        $create = $service->create($data);
        $this->assertTrue($create['success']);
        $this->assertEquals(201, $create['code']);
        unset($data['password']);
        $this->assertDatabaseHas('users', $data);
    }


    public function test_update_user_success(): void {

        $data = [
            "name" => "John Doe",
            "email" => "johndoe@gmail.com",
        ];

        $service = new User();
        $update = $service->update("userId", $data);
        $this->assertTrue($update['success']);
        $this->assertEquals(200, $update['code']);
        $this->assertDatabaseHas('users', $data);
    }

    public function test_delete_user_success(): void {

        $service = new User();
        $delete = $service->delete("userId");
        $this->assertTrue($delete['success']);
        $this->assertEquals(200, $delete['code']);
        $this->assertDatabaseMissing('users', ['_id' => "userId"]);
    }

    public function test_get_user_success(): void {
        $service = new User();
        $get = $service->get("userId");
        $this->assertTrue($get['success']);
        $this->assertEquals(200, $get['code']);
        $this->assertEquals(["_id" => "userId","name" => "John Doe","email" => "john@doe.com"], $this->data);
    }

    public function test_validate_create_user_request_success(): void {
        $data = [
            "name" => "John Doe",
            "email" => "john@doe.com",
            "password" => "johndoe123"
        ];

        $request = new CreateUserRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }

    public function test_validate_create_user_request_failed_because_pass_is_empty(): void {
        $data = [
            "name" => "John Doe",
            "email" => "john@doe.com",
            "password" => ""
        ];

        $request = new CreateUserRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertEquals(["password" => ["The password field is required."]], $validator->messages()->messages());
    }

    public function test_validate_create_user_request_failed_because_name_is_less_than_3_digits(): void {
        $data = [
            "name" => "Jo",
            "email" => "john@doe.com",
            "password" => "johndoe123"
        ];

        $request = new CreateUserRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertEquals(["name" => ["The name field must be at least 3 characters."]], $validator->messages()->messages());
    }

    public function test_validate_create_user_request_failed_because_email_is_invalid(): void {
        $data = [
            "name" => "John Doe",
            "email" => "john.com",
            "password" => "johndoe123"
        ];

        $request = new CreateUserRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertEquals(["email" => ["The email field must be a valid email address."]], $validator->messages()->messages());
    }

    public function test_user_add_movie_history_success(): void {

        $service = new User();
        $addToHistory = $service->addToHistory("tt0816692", "userId");
        $get = $service->get("userId");
        $this->assertTrue($addToHistory['success']);
        $this->assertEquals(201, $addToHistory['code']);
        $this->assertArrayHasKey("movies", $get['data']);
        $this->assertEquals([
            "Title" => "Interstellar",
            "Year" => "2014",
            "Rated" => "PG-13",
            "Released" => "07 Nov 2014",
            "Runtime" => "169 min",
            "Genre" => "Adventure, Drama, Sci-Fi",
            "Director" => "Christopher Nolan",
            "Writer" => "Jonathan Nolan, Christopher Nolan",
            "Actors" => "Matthew McConaughey, Anne Hathaway, Jessica Chastain",
            "Plot" => "When Earth becomes uninhabitable in the future, a farmer and ex-NASA pilot, Joseph Cooper, is tasked to pilot a spacecraft, along with a team of researchers, to find a new planet for humans.",
            "Language" => "English",
            "Country" => "United States, United Kingdom, Canada",
            "Awards" => "Won 1 Oscar. 44 wins & 148 nominations total",
            "Poster" => "https://m.media-amazon.com/images/M/MV5BZjdkOTU3MDktN2IxOS00OGEyLWFmMjktY2FiMmZkNWIyODZiXkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg",
            "Ratings" => [
              [
                "Source" => "Internet Movie Database",
                "Value" => "8.7/10"
              ],
              [
                "Source" => "Rotten Tomatoes",
                "Value" => "73%"
              ],
              [
                "Source" => "Metacritic",
                "Value" => "74/100"
              ]
              ],
            "Metascore" => "74",
            "imdbRating" => "8.7",
            "imdbVotes" => "2,153,343",
            "imdbID" => "tt0816692",
            "Type" => "movie",
            "DVD" => "N/A",
            "BoxOffice" => "$188,020,017",
            "Production" => "N/A",
            "Website" => "N/A",
            "Response" => "True",
            ], $get['data']['movies'][0]);
    }

    public function test_user_remove_movie_history_success(): void {
        $service = new User();
        $addToHistory = $service->addToHistory("tt0816692", "userId");
        $removeFromHistory = $service->removeFromHistory("tt0816692", "userId");
        $get = $service->get("userId");
        $this->assertTrue($removeFromHistory['success']);
        $this->assertEquals(200, $removeFromHistory['code']);
        $this->assertEmpty($get['data']['movies']);
        DB::collection("users")->where("_id", "userId")->delete();
    }

    public function tearDown(): void{
        DB::collection("users")->where("_id", "userId")->delete();
        ModelsUser::where("_id", hash("sha256", "email:john@doe2.com"))->delete();

        parent::tearDown();
    }
}
