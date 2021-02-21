<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentAccountTest extends TestCase
{
    use RefreshDatabase;
   /**
    * @test
    */
    public function test_if_guest_user_cannot_view_all_users()
    {
        $response = $this->get('/student/user');
        $response->assertRedirect('/login');
    }

    public function test_if_student_can_redirect_to_profile_upon_login()
    {
        $user = User::factory()->isCordinator(0)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $response->assertRedirect('/student/user/'.$user->id);
    }

    public function test_if_cordinator_can_redirect_to_users_list_upon_login()
    {
        $user = User::factory()->isCordinator(1)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/student/user');

    }

    public function test_if_student_cannot_view_other_profile()
    {
        $user = User::factory()->isCordinator(0)->create();
        $user2 = User::factory()->isCordinator(0)->create();

        // $authUser = User::first();
        
        $response = $this->actingAs($user)->get('/student/user/'.$user2->id);
        $response->assertStatus(302);
    }

    public function test_if_students_can_view_their_profile()
    {
        $user = User::factory()->isCordinator(0)->create();

        $response = $this->actingAs($user)->get('/student/user/'.$user->id);

        $response->assertStatus(200);
    }
    

    public function test_if_cordinators_can_view_all_profiles()
    {
        User::factory()->isCordinator(1)->create();
        User::factory()->isCordinator(0)->count(4)->create();
        $user = User::first();

        $response = $this->actingAs($user)->get('/student/user/'.$user->id);

        $response->assertStatus(200);

    }

    public function test_if_a_profile_owner_can_update_their_profile()
    {
        Storage::fake('public');
        $user = User::factory()->isCordinator(1)->create();

        $file = UploadedFile::fake()->image('avatar.png');

        $user->name = "testName";
        $user->photo = $file;
        $response = $this->actingAs($user)->put('/student/user/'.$user->id, $user->toArray());


        $this->assertDatabaseHas('users',['id'=> $user->id , 'name' => 'testName']);


        // dd($user);
        $response->assertRedirect('student/user');


        $this->assertSame('testName', $user->name);

        
    }

    public function test_if_all_users_can_be_fetched()
    {
        $user = User::factory()->isCordinator(1)->create();

        $response = $this->actingAs($user)->get('/student/user');
        
        $response->assertStatus(200);

    }

    public function test_if_cordinator_can_create_students()
    {
        $user = User::factory()->isCordinator(1)->create();

        $student = User::factory()->isCordinator(0)->make();

        $data = [
            'name' => 'testStudent',
            'lastName' => 'testLastname',
            'email' => 'test@test.co',
            'email_verified_at' => now(),
            'phone' => '09012345678',
            'photo' => '',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
        ];

        $response = $this->actingAs($user)->post('/student/user', $data);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users' , ['email' => $data['email']]);
    }

    public function test_if_a_student_cannot_create_a_student()
    {
        $user = User::factory()->isCordinator(0)->create();

        $student = User::factory()->isCordinator(0)->make();

        $student = $student->toArray();

        // add password to the array

        $student['password'] = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

        $response = $this->actingAs($user)->post('/student/user', $student);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('users' , ['email' => $student['email']]);
    }
    
    public function test_if_cordinator_can_create_students_with_profile_pics()
    {
        Storage::fake('public');

        $user = User::factory()->isCordinator(1)->create();

        $student = User::factory()->isCordinator(0)->make();

        $file = UploadedFile::fake()->image('avatar.png');

        $data = [
            'name' => 'testStudent',
            'lastName' => 'testLastname',
            'email' => 'test@test.co',
            'email_verified_at' => now(),
            'phone' => '09012345678',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
            'photo' => $file
        ];
        $response = $this->actingAs($user)->post('/student/user', $data);

        $imageName = $file->hashName();

        Storage::disk('public')->assertExists($imageName);


        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users' , ['email' => $data['email']]);
    }

    public function test_if_new_user_form_page_is_accessible_by_cordinator()
    {
        $user = User::factory()->isCordinator(0)->create();

        $response = $this->actingAs($user)->get('/student/user/create');

        $response->assertStatus(200);
    }

    

    public function test_if_a_cordinator_can_delete_a_student()
    {
        $user = User::factory()->isCordinator(1)->create();

        $student = User::factory()->isCordinator(0)->create();

        $response = $this->actingAs($user)->delete('/student/user/'.$student->id);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('users' , ['email' => $student->email]);
    }

    public function test_if_a_student_cannot_delete_another_student()
    {
        $user = User::factory()->isCordinator(0)->create();

        $student = User::factory()->isCordinator(0)->create();

        $response = $this->actingAs($user)->delete('/student/user/'.$student->id);

        $this->assertDatabaseMissing('users' , ['email' => $student->email]);
    }

    public function test_if_a_student_can_delete_a_cordinator_profile()
    {
        $cordinator = User::factory()->isCordinator(1)->create();

        $student = User::factory()->isCordinator(0)->create();

        $response = $this->actingAs($student)->delete('/student/user/'.$cordinator->id);

        $this->assertDatabaseHas('users' , ['email' => $cordinator->email]);

    }
}
