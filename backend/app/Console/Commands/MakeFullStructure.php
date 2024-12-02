<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeFullStructure extends Command
{
    protected $signature = 'make:full-structure {name}';
    protected $description = 'Generate migration, controller, requests, model, and repository with CRUD';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $pluralName = Str::plural($name);

        // Generate migration
        Artisan::call("make:migration create_" . Str::snake($pluralName) . "_table");
        $this->info("Migration created: create_" . Str::snake($pluralName) . "_table");

        // Generate model
        Artisan::call("make:model $name");
        $this->info("Model created: $name");

        // Generate controller
        $controllerPath = app_path("Http/Controllers/{$name}Controller.php");
        file_put_contents($controllerPath, $this->getControllerTemplate($name));
        $this->info("Controller created: {$name}Controller");

        // Generate requests
        $requestNamespace = app_path("Http/Requests/{$name}");
        if (!is_dir($requestNamespace)) {
            mkdir($requestNamespace, 0755, true);
        }

        file_put_contents($requestNamespace . "/Retrieve.php", $this->getRequestTemplate("Retrieve", $name));
        file_put_contents($requestNamespace . "/Store.php", $this->getRequestTemplate("Store", $name));
        file_put_contents($requestNamespace . "/Update.php", $this->getRequestTemplate("Update", $name));
        file_put_contents($requestNamespace . "/Delete.php", $this->getRequestTemplate("Delete", $name));
        $this->info("Requests created: Retrieve, Store, Update, Delete");

        // Generate repository
        $repositoryPath = app_path("Repositories");
        if (!is_dir($repositoryPath)) {
            mkdir($repositoryPath, 0755, true);
        }

        file_put_contents($repositoryPath . "/{$name}Repository.php", $this->getRepositoryTemplate($name));
        $this->info("Repository created: {$name}Repository");

        $this->info('Full structure created successfully!');
    }

    private function getControllerTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Http\Controllers;

use App\Http\Requests\\{$name}\\Retrieve;
use App\Http\Requests\\{$name}\\Store;
use App\Http\Requests\\{$name}\\Update;
use App\Http\Requests\\{$name}\\Delete;
use App\Repositories\\{$name}Repository;
use Illuminate\Http\JsonResponse;

class {$name}Controller extends Controller
{
    protected \${$name}Repository;

    public function __construct()
    {
        \$this->{$name}Repository = new {$name}Repository;
    }

    public function retrievePaginate(Retrieve \$request): JsonResponse
    {
        \$params = [
            'search' => \$request->input('search'),
            'currentPage' => \$request->input('page', 1),
            'pageSize' => \$request->input('pageSize', 10),
        ];

        \$response = \$this->{$name}Repository->retrievePaginate(\$params);
        return \$this->{$name}Repository->getJsonResponse(\$response);
    }

    public function retrieveAll(): JsonResponse
    {
        \$response = \$this->{$name}Repository->retrieveAll();
        return \$this->{$name}Repository->getJsonResponse(\$response);
    }

    public function store(Store \$request): JsonResponse
    {
        \$data = \$request->validated();
        \$response = \$this->{$name}Repository->store(\$data);
        return \$this->{$name}Repository->getJsonResponse(\$response);
    }

    public function update(Update \$request): JsonResponse
    {
        \$data = \$request->validated();
        \$response = \$this->{$name}Repository->update(\$data);
        return \$this->{$name}Repository->getJsonResponse(\$response);
    }

    public function delete(Delete \$request): JsonResponse
    {
        \$data = [
            'id' => \$request->input('id'),
        ];

        \$response = \$this->{$name}Repository->delete(\$data);
        return \$this->{$name}Repository->getJsonResponse(\$response);
    }
}
PHP;
    }

    private function getRequestTemplate($type, $name)
    {
        return <<<PHP
<?php

namespace App\Http\Requests\\{$name};

use Illuminate\Foundation\Http\FormRequest;

class {$type} extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Add validation rules here
        ];
    }
}
PHP;
    }

    private function getRepositoryTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Repositories;

class {$name}Repository
{
    public function retrievePaginate(array \$params)
    {
        // Add logic here
    }

    public function retrieveAll()
    {
        // Add logic here
    }

    public function store(array \$data)
    {
        // Add logic here
    }

    public function update(array \$data)
    {
        // Add logic here
    }

    public function delete(array \$data)
    {
        // Add logic here
    }

    public function getJsonResponse(\$response)
    {
        return response()->json(\$response);
    }
}
PHP;
    }
}
