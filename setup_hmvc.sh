#!/bin/bash

# Function to create module structure
create_module() {
    MODULE_NAME=$1
    echo "Creating module: $MODULE_NAME"

    # Create directories
    mkdir -p app/Modules/$MODULE_NAME/Controllers
    mkdir -p app/Modules/$MODULE_NAME/Models
    mkdir -p app/Modules/$MODULE_NAME/Views

    # Create a sample controller
    cat <<EOL > app/Modules/$MODULE_NAME/Controllers/${MODULE_NAME}Controller.php
<?php

namespace App\Modules\\$MODULE_NAME\Controllers;

use App\Controllers\BaseController;

class ${MODULE_NAME}Controller extends BaseController
{
    public function index()
    {
        echo "Hello from the $MODULE_NAME module!";
    }
}
EOL

    # Create a sample model
    cat <<EOL > app/Modules/$MODULE_NAME/Models/${MODULE_NAME}Model.php
<?php

namespace App\Modules\\$MODULE_NAME\Models;

use CodeIgniter\Model;

class ${MODULE_NAME}Model extends Model
{
    protected \$table = '${MODULE_NAME,,}';
}
EOL

    # Create a sample view
    cat <<EOL > app/Modules/$MODULE_NAME/Views/index.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$MODULE_NAME Module</title>
</head>
<body>
    <h1>Hello from the $MODULE_NAME Module View!</h1>
</body>
</html>
EOL

    echo "Module $MODULE_NAME created successfully."
}

# Create the Modules directory if it doesn't exist
mkdir -p app/Modules

# Add Modules namespace to Autoload configuration
AUTOLOAD_FILE="app/Config/Autoload.php"
NAMESPACE_LINE="'App\\\\Modules' => APPPATH . 'Modules',"

if grep -q "$NAMESPACE_LINE" "$AUTOLOAD_FILE"; then
    echo "Modules namespace is already configured."
else
    sed -i "/'App'         => APPPATH,/a\    $NAMESPACE_LINE" $AUTOLOAD_FILE
    echo "Added Modules namespace to Autoload configuration."
fi

# Function to add route for a module
add_route() {
    MODULE_NAME=$1
    ROUTES_FILE="app/Config/Routes.php"
    ROUTE_GROUP_LINE="\$routes->group('$MODULE_NAME', ['namespace' => 'App\Modules\\$MODULE_NAME\Controllers'], function(\$routes) {
        \$routes->get('/', '${MODULE_NAME}Controller::index');
    });"

    if grep -q "group('$MODULE_NAME'" "$ROUTES_FILE"; then
        echo "Routing is already configured for the $MODULE_NAME module."
    else
        echo "Adding routing configuration for $MODULE_NAME module to $ROUTES_FILE"
        echo -e "\n// Routes for $MODULE_NAME module\n$ROUTE_GROUP_LINE" >> $ROUTES_FILE
        echo "Routing configuration for $MODULE_NAME added."
    fi
}

# Prompt for the module name
read -p "Enter the module name: " MODULE_NAME

# Create the module and add its route
create_module "$MODULE_NAME"
add_route "$MODULE_NAME"

echo "HMVC-like structure setup with routing completed for module: $MODULE_NAME."
