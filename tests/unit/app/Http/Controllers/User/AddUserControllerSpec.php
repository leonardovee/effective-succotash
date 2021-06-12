<?php

use App\Http\Controllers\User\UserController;
use App\Data\Usecase\User\DbAddUser;
use App\Domain\Model\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $sut;
    private $stub;

    private function makeRequest(): Request
    {
        return new Request([
            'name' => 'any_name',
            'email' => 'any_email',
            'password' => 'any_password',
            'document' => 'any_document',
            'type' => 1
        ]);
    }

    private function makeSut()
    {
        $this->stub = $this->createMock(DbAddUser::class);

        $this->sut = new UserController($this->stub);
    }

    public function test_should_return_unprocessable_entity_if_validation_fails()
    {
        $this->makeSut();

        $request = new Request();

        $this->expectException(ValidationException::class);

        $this->sut->handle($request);
    }

    public function test_should_call_db_add_user_with_correct_values()
    {
        $this->makeSut();

        $request = $this->makeRequest();

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->document = $request->document;
        $user->type = $request->type;

        $this->stub->expects($this->once())->method('add')->with($user);

        $this->sut->handle($request);
    }

    public function test_should_return_server_error_if_db_add_user_throws()
    {
        $this->makeSut();

        $request = $this->makeRequest();

        $this->stub->expects($this->once())->method('add')->will(
            $this->throwException(new Exception)
        );

        $this->expectException(HttpException::class);

        $this->sut->handle($request);
    }

    public function test_should_return_user_id_on_sucess()
    {
        $this->makeSut();

        $request = $this->makeRequest();

        $anyUserId = 2503;
        $this->stub->expects($this->once())->method('add')->willReturn($anyUserId);

        $response = $this->sut->handle($request);

        $this->assertSame($anyUserId, $response->getData()->id);
        $this->assertSame(201, $response->getStatusCode());
    }
}