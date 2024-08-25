<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateModule extends BaseCommand
{
    protected $group = 'Modules';
    protected $name = 'module:create';
    protected $description = 'Creates a new module with HMVC-like structure';

    public function run(array $params)
    {
        // Prompt for the module name
        $moduleName = CLI::prompt('Enter the module name');

        if (empty($moduleName)) {
            CLI::error('Module name cannot be empty!');
            return;
        }

        // Create module structure
        $this->createModule($moduleName);

        // Add route for the module
        $this->addRoute($moduleName);

        CLI::write("HMVC-like structure setup with routing completed for module: $moduleName.", 'green');
    }

    protected function createModule($moduleName)
    {
        $moduleName = ucfirst($moduleName);
        CLI::write("Creating module: $moduleName", 'yellow');

        // Create directories
        $baseDir = APPPATH . "Modules/$moduleName/";
        $this->createDir("$baseDir/Controllers");
        $this->createDir("$baseDir/Models");
        $this->createDir("$baseDir/Views");

        // Create a sample controller
        $controllerContent = <<<EOL
<?php

namespace App\Modules\\$moduleName\Controllers;

use App\Controllers\BaseController;

class {$moduleName}Controller extends BaseController
{
    public function index()
    {
        \$data = [
		    'title' => '$moduleName',
            'view' => 'App\Modules\\$moduleName\Views\index'
        ];

		return view('template/layout', \$data);
    }
}
EOL;

        file_put_contents("$baseDir/Controllers/{$moduleName}Controller.php", $controllerContent);

        // Create a sample model
        $modelContent = <<<EOL
<?php

namespace App\Modules\\$moduleName\Models;

use CodeIgniter\Model;

class {$moduleName}Model extends Model
{
    protected \$table = '{$moduleName}';
}
EOL;

        file_put_contents("$baseDir/Models/{$moduleName}Model.php", $modelContent);

        // Create a sample view
        $viewContent = <<<EOL
<section>
    <h1>Hello from the $moduleName Module View!</h1>
</section>
EOL;

        file_put_contents("$baseDir/Views/index.php", $viewContent);

        CLI::write("Module $moduleName created successfully.", 'green');
    }

    protected function addRoute($moduleName)
    {
        $moduleName = ucfirst($moduleName);
        CLI::write("Adding route for module: $moduleName", 'yellow');

        $groupName = strtolower($moduleName);

        $routesFile = APPPATH . 'Config/Routes.php';
        $routeGroupLine = <<<EOL
\$routes->group('$groupName', ['namespace' => 'App\Modules\\$moduleName\Controllers'], function(\$routes) {
    \$routes->get('/', '{$moduleName}Controller::index');
});
EOL;

        if (strpos(file_get_contents($routesFile), "group('$moduleName'") !== false) {
            CLI::write("Routing is already configured for the $moduleName module.", 'blue');
        } else {
            file_put_contents($routesFile, "\n// Routes for $moduleName module\n$routeGroupLine\n", FILE_APPEND);
            CLI::write("Routing configuration for $moduleName added.", 'green');
        }
    }

    protected function createDir($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            CLI::write("Created directory: $path", 'green');
        }
    }
}
