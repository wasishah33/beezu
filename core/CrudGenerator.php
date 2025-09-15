<?php

namespace Core;

class CrudGenerator
{
    private array $config;
    private string $basePath;

    public function __construct()
    {
        $this->basePath = ROOT_PATH;
    }

    /**
     * Generate complete CRUD for a resource
     */
    public function generate(array $config): array
    {
        $this->config = require APP_PATH . '/config/crud.php';
        $results = [];

        try {
            // Generate migration
            $results['migration'] = $this->generateMigration();

            // Generate model
            $results['model'] = $this->generateModel();

            // Generate controller
            $results['controller'] = $this->generateController();

            // Generate views
            $results['views'] = $this->generateViews();

            // Generate routes
            $results['routes'] = $this->appendRoutes();

            // Run migration if requested
            if ($config['run_migration'] ?? false) {
                $results['migration_run'] = $this->runMigration();
            }

            return [
                'success' => true,
                'message' => 'CRUD generated successfully',
                'results' => $results
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'results' => $results
            ];
        }
    }

    /**
     * Generate migration file
     */
    private function generateMigration(): string
    {
        $tableName = $this->config['table_name'];
        $fields = $this->config['fields'];
        $timestamp = date('Y_m_d_His');
        $className = 'Create' . $this->toPascalCase($tableName) . 'Table';
        $filename = "{$timestamp}_create_{$tableName}_table.php";
        $path = $this->basePath . '/database/migrations/' . $filename;

        // Create migrations directory if it doesn't exist
        $this->createDirectory(dirname($path));

        $content = "<?php\n\n";
        $content .= "use Core\\Database;\n\n";
        $content .= "class {$className}\n";
        $content .= "{\n";
        $content .= "    public function up(Database \$db): void\n";
        $content .= "    {\n";
        $content .= "        \$sql = \"CREATE TABLE IF NOT EXISTS `{$tableName}` (\n";

        // Add fields
        $fieldDefinitions = ["            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT"];

        foreach ($fields as $field) {
            $fieldDef = "            `{$field['name']}` " . $this->getSqlType($field);

            if (!($field['nullable'] ?? false)) {
                $fieldDef .= " NOT NULL";
            } else {
                $fieldDef .= " NULL";
            }

            if (isset($field['default'])) {
                $fieldDef .= " DEFAULT " . $this->getSqlDefault($field);
            }

            if ($field['unique'] ?? false) {
                $fieldDef .= " UNIQUE";
            }

            $fieldDefinitions[] = $fieldDef;
        }

        // Add timestamps if enabled
        if ($this->config['timestamps'] ?? true) {
            $fieldDefinitions[] = "            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP";
            $fieldDefinitions[] = "            `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        }

        // Add primary key
        $fieldDefinitions[] = "            PRIMARY KEY (`id`)";

        // Add indexes
        foreach ($fields as $field) {
            if ($field['index'] ?? false) {
                $fieldDefinitions[] = "            KEY `idx_{$field['name']}` (`{$field['name']}`)";
            }
        }

        $content .= implode(",\n", $fieldDefinitions);
        $content .= "\n        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci\";\n";
        $content .= "        \n";
        $content .= "        \$db->execute(\$sql);\n";
        $content .= "    }\n";
        $content .= "    \n";
        $content .= "    public function down(Database \$db): void\n";
        $content .= "    {\n";
        $content .= "        \$sql = \"DROP TABLE IF EXISTS `{$tableName}`\";\n";
        $content .= "        \$db->execute(\$sql);\n";
        $content .= "    }\n";
        $content .= "}\n";

        file_put_contents($path, $content);

        return $filename;
    }

    /**
     * Generate model file
     */
    private function generateModel(): string
    {
        $modelName = $this->toPascalCase($this->config['model_name']);
        $tableName = $this->config['table_name'];
        $path = $this->basePath . '/app/Models/' . $modelName . '.php';

        $content = "<?php\n\n";
        $content .= "namespace App\\Models;\n\n";
        $content .= "use Core\\Model;\n\n";
        $content .= "class {$modelName} extends Model\n";
        $content .= "{\n";
        $content .= "    protected static string \$table = '{$tableName}';\n";
        $content .= "    \n";

        // Add fillable properties
        $fillable = array_map(fn($f) => "'{$f['name']}'", $this->config['fields']);
        $content .= "    protected array \$fillable = [" . implode(', ', $fillable) . "];\n";
        $content .= "    \n";

        // Add validation rules
        $content .= "    /**\n";
        $content .= "     * Get validation rules\n";
        $content .= "     */\n";
        $content .= "    public static function validationRules(): array\n";
        $content .= "    {\n";
        $content .= "        return [\n";

        foreach ($this->config['fields'] as $field) {
            $rules = $this->getValidationRules($field);
            if ($rules) {
                $content .= "            '{$field['name']}' => '{$rules}',\n";
            }
        }

        $content .= "        ];\n";
        $content .= "    }\n";

        // Add search scope
        $content .= "    \n";
        $content .= "    /**\n";
        $content .= "     * Search scope\n";
        $content .= "     */\n";
        $content .= "    public static function search(string \$query)\n";
        $content .= "    {\n";
        $content .= "        \$q = static::query();\n";

        $searchableFields = array_filter($this->config['fields'], fn($f) => $f['searchable'] ?? false);
        if (!empty($searchableFields)) {
            $content .= "        \$searchFields = ['" . implode("', '", array_column($searchableFields, 'name')) . "'];\n";
            $content .= "        \n";
            $content .= "        foreach (\$searchFields as \$field) {\n";
            $content .= "            \$q->orWhere(\$field, 'LIKE', \"%{\$query}%\");\n";
            $content .= "        }\n";
        }

        $content .= "        \n";
        $content .= "        return \$q;\n";
        $content .= "    }\n";

        // Add accessor/mutator for specific field types
        foreach ($this->config['fields'] as $field) {
            if ($field['type'] === 'password') {
                $content .= "    \n";
                $content .= "    /**\n";
                $content .= "     * Hash password before saving\n";
                $content .= "     */\n";
                $content .= "    public function set" . $this->toPascalCase($field['name']) . "Attribute(string \$value): void\n";
                $content .= "    {\n";
                $content .= "        \$this->attributes['{$field['name']}'] = password_hash(\$value, PASSWORD_DEFAULT);\n";
                $content .= "    }\n";
            }
        }

        $content .= "}\n";

        file_put_contents($path, $content);

        return $modelName;
    }

    /**
     * Generate controller file
     */
    private function generateController(): string
    {
        $controllerName = $this->toPascalCase($this->config['controller_name']) . 'Controller';
        $modelName = $this->toPascalCase($this->config['model_name']);
        $resourceName = strtolower($this->config['resource_name']);
        $resourceNamePlural = $this->config['resource_plural'];
        $path = $this->basePath . '/app/Controllers/' . $controllerName . '.php';

        $content = "<?php\n\n";
        $content .= "namespace App\\Controllers;\n\n";
        $content .= "use Core\\Controller;\n";
        $content .= "use Core\\ValidationException;\n";
        $content .= "use Core\\Session;\n";
        $content .= "use App\\Models\\{$modelName};\n\n";
        $content .= "class {$controllerName} extends Controller\n";
        $content .= "{\n";

        // Index method
        $content .= "    /**\n";
        $content .= "     * Display listing\n";
        $content .= "     */\n";
        $content .= "    public function index(): void\n";
        $content .= "    {\n";
        $content .= "        \$query = {$modelName}::query();\n";
        $content .= "        \n";
        $content .= "        // Handle search\n";
        $content .= "        if (\$search = \$this->request->get('search')) {\n";
        $content .= "            \$query = {$modelName}::search(\$search);\n";
        $content .= "        }\n";
        $content .= "        \n";
        $content .= "        // Handle sorting\n";
        $content .= "        \$sortBy = \$this->request->get('sort', 'id');\n";
        $content .= "        \$sortOrder = \$this->request->get('order', 'DESC');\n";
        $content .= "        \$query->orderBy(\$sortBy, \$sortOrder);\n";
        $content .= "        \n";
        $content .= "        // Pagination\n";
        $content .= "        \$page = (int) \$this->request->get('page', 1);\n";
        $content .= "        \$perPage = " . ($this->config['per_page'] ?? 10) . ";\n";
        $content .= "        \$total = \$query->count();\n";
        $content .= "        \$items = \$query->limit(\$perPage)->offset((\$page - 1) * \$perPage)->get();\n";
        $content .= "        \n";
        $content .= "        \$this->render('{$resourceNamePlural}/index', [\n";
        $content .= "            'title' => '" . $this->config['display_name_plural'] . "',\n";
        $content .= "            'items' => \$items,\n";
        $content .= "            'pagination' => [\n";
        $content .= "                'current' => \$page,\n";
        $content .= "                'total' => ceil(\$total / \$perPage),\n";
        $content .= "                'per_page' => \$perPage,\n";
        $content .= "                'total_items' => \$total\n";
        $content .= "            ],\n";
        $content .= "            'search' => \$search,\n";
        $content .= "            'sort' => ['by' => \$sortBy, 'order' => \$sortOrder]\n";
        $content .= "        ]);\n";
        $content .= "    }\n\n";

        // Create method
        $content .= "    /**\n";
        $content .= "     * Show create form\n";
        $content .= "     */\n";
        $content .= "    public function create(): void\n";
        $content .= "    {\n";
        $content .= "        \$this->render('{$resourceNamePlural}/create', [\n";
        $content .= "            'title' => 'Create " . $this->config['display_name'] . "'\n";
        $content .= "        ]);\n";
        $content .= "    }\n\n";

        // Store method
        $content .= "    /**\n";
        $content .= "     * Store new record\n";
        $content .= "     */\n";
        $content .= "    public function store(): void\n";
        $content .= "    {\n";
        $content .= "        try {\n";
        $content .= "            \$data = \$this->validate({$modelName}::validationRules());\n";
        $content .= "            \n";

        // Handle file uploads
        foreach ($this->config['fields'] as $field) {
            if ($field['type'] === 'file' || $field['type'] === 'image') {
                $content .= "            // Handle {$field['name']} upload\n";
                $content .= "            if (\$file = \$this->request->file('{$field['name']}')) {\n";
                $content .= "                \$data['{$field['name']}'] = \$this->uploadFile(\$file, '{$resourceNamePlural}');\n";
                $content .= "            }\n";
                $content .= "            \n";
            }
        }

        $content .= "            {$modelName}::create(\$data);\n";
        $content .= "            \n";
        $content .= "            Session::flash('success', '" . $this->config['display_name'] . " created successfully');\n";
        $content .= "            \$this->redirect('/{$resourceNamePlural}');\n";
        $content .= "            \n";
        $content .= "        } catch (ValidationException \$e) {\n";
        $content .= "            Session::flash('errors', \$e->getErrors());\n";
        $content .= "            Session::flash('old_input', \$this->request->all());\n";
        $content .= "            \$this->redirect('/{$resourceNamePlural}/create');\n";
        $content .= "        }\n";
        $content .= "    }\n\n";

        // Edit method
        $content .= "    /**\n";
        $content .= "     * Show edit form\n";
        $content .= "     */\n";
        $content .= "    public function edit(\$id): void\n";
        $content .= "    {\n";
        $content .= "        \$item = {$modelName}::find(\$id);\n";
        $content .= "        \n";
        $content .= "        if (!\$item) {\n";
        $content .= "            \$this->response->setStatusCode(404);\n";
        $content .= "            echo '" . $this->config['display_name'] . " not found';\n";
        $content .= "            return;\n";
        $content .= "        }\n";
        $content .= "        \n";
        $content .= "        \$this->render('{$resourceNamePlural}/edit', [\n";
        $content .= "            'title' => 'Edit " . $this->config['display_name'] . "',\n";
        $content .= "            'item' => \$item\n";
        $content .= "        ]);\n";
        $content .= "    }\n\n";

        // Update method
        $content .= "    /**\n";
        $content .= "     * Update record\n";
        $content .= "     */\n";
        $content .= "    public function update(\$id): void\n";
        $content .= "    {\n";
        $content .= "        \$item = {$modelName}::find(\$id);\n";
        $content .= "        \n";
        $content .= "        if (!\$item) {\n";
        $content .= "            \$this->response->setStatusCode(404);\n";
        $content .= "            echo '" . $this->config['display_name'] . " not found';\n";
        $content .= "            return;\n";
        $content .= "        }\n";
        $content .= "        \n";
        $content .= "        try {\n";
        $content .= "            \$data = \$this->validate({$modelName}::validationRules());\n";
        $content .= "            \n";

        // Handle file uploads for update
        foreach ($this->config['fields'] as $field) {
            if ($field['type'] === 'file' || $field['type'] === 'image') {
                $content .= "            // Handle {$field['name']} upload\n";
                $content .= "            if (\$file = \$this->request->file('{$field['name']}')) {\n";
                $content .= "                \$data['{$field['name']}'] = \$this->uploadFile(\$file, '{$resourceNamePlural}');\n";
                $content .= "            }\n";
                $content .= "            \n";
            }
        }

        $content .= "            \$item->update(\$data);\n";
        $content .= "            \n";
        $content .= "            Session::flash('success', '" . $this->config['display_name'] . " updated successfully');\n";
        $content .= "            \$this->redirect('/{$resourceNamePlural}');\n";
        $content .= "            \n";
        $content .= "        } catch (ValidationException \$e) {\n";
        $content .= "            Session::flash('errors', \$e->getErrors());\n";
        $content .= "            Session::flash('old_input', \$this->request->all());\n";
        $content .= "            \$this->redirect('/{$resourceNamePlural}/edit/' . \$id);\n";
        $content .= "        }\n";
        $content .= "    }\n\n";

        // Delete method
        $content .= "    /**\n";
        $content .= "     * Delete record\n";
        $content .= "     */\n";
        $content .= "    public function destroy(\$id): void\n";
        $content .= "    {\n";
        $content .= "        \$item = {$modelName}::find(\$id);\n";
        $content .= "        \n";
        $content .= "        if (!\$item) {\n";
        $content .= "            \$this->response->setStatusCode(404);\n";
        $content .= "            echo '" . $this->config['display_name'] . " not found';\n";
        $content .= "            return;\n";
        $content .= "        }\n";
        $content .= "        \n";
        $content .= "        \$item->delete();\n";
        $content .= "        \n";
        $content .= "        Session::flash('success', '" . $this->config['display_name'] . " deleted successfully');\n";
        $content .= "        \$this->redirect('/{$resourceNamePlural}');\n";
        $content .= "    }\n";

        // Add file upload helper if needed
        if ($this->hasFileFields()) {
            $content .= "    \n";
            $content .= "    /**\n";
            $content .= "     * Upload file\n";
            $content .= "     */\n";
            $content .= "    private function uploadFile(array \$file, string \$folder): ?string\n";
            $content .= "    {\n";
            $content .= "        if (\$file['error'] !== UPLOAD_ERR_OK) {\n";
            $content .= "            return null;\n";
            $content .= "        }\n";
            $content .= "        \n";
            $content .= "        \$uploadDir = PUBLIC_PATH . '/uploads/' . \$folder;\n";
            $content .= "        if (!is_dir(\$uploadDir)) {\n";
            $content .= "            mkdir(\$uploadDir, 0777, true);\n";
            $content .= "        }\n";
            $content .= "        \n";
            $content .= "        \$extension = pathinfo(\$file['name'], PATHINFO_EXTENSION);\n";
            $content .= "        \$filename = uniqid() . '.' . \$extension;\n";
            $content .= "        \$destination = \$uploadDir . '/' . \$filename;\n";
            $content .= "        \n";
            $content .= "        if (move_uploaded_file(\$file['tmp_name'], \$destination)) {\n";
            $content .= "            return '/uploads/' . \$folder . '/' . \$filename;\n";
            $content .= "        }\n";
            $content .= "        \n";
            $content .= "        return null;\n";
            $content .= "    }\n";
        }

        $content .= "}\n";

        file_put_contents($path, $content);

        return $controllerName;
    }

    /**
     * Generate view files
     */
    private function generateViews(): array
    {
        $resourceNamePlural = $this->config['resource_plural'];
        $viewsPath = $this->basePath . '/views/' . $resourceNamePlural;

        // Create views directory
        $this->createDirectory($viewsPath);

        $views = [];

        // Generate index view
        $views['index'] = $this->generateIndexView($viewsPath);

        // Generate create view
        $views['create'] = $this->generateCreateView($viewsPath);

        // Generate edit view
        $views['edit'] = $this->generateEditView($viewsPath);

        // Generate form partial
        $views['_form'] = $this->generateFormPartial($viewsPath);

        return $views;
    }

    /**
     * Generate index view
     */
    private function generateIndexView(string $path): string
    {
        $filename = $path . '/index.php';
        $resourceNamePlural = $this->config['resource_plural'];
        $displayNamePlural = $this->config['display_name_plural'];

        $content = "<?php \$view = new Core\\View(); ?>\n\n";
        $content .= "<div class=\"container mx-auto px-4 py-8\">\n";
        $content .= "    <div class=\"flex justify-between items-center mb-6\">\n";
        $content .= "        <h1 class=\"text-3xl font-bold\"><?= \$view->e(\$title) ?></h1>\n";
        $content .= "        <a href=\"/{$resourceNamePlural}/create\" class=\"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded\">\n";
        $content .= "            Create New\n";
        $content .= "        </a>\n";
        $content .= "    </div>\n\n";

        // Flash messages
        $content .= "    <?php if (\$success = Core\\Session::flash('success')): ?>\n";
        $content .= "        <div class=\"bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4\">\n";
        $content .= "            <?= \$view->e(\$success) ?>\n";
        $content .= "        </div>\n";
        $content .= "    <?php endif; ?>\n\n";

        // Search form
        $content .= "    <form method=\"GET\" class=\"mb-6\">\n";
        $content .= "        <div class=\"flex gap-2\">\n";
        $content .= "            <input type=\"text\" name=\"search\" value=\"<?= \$view->e(\$search ?? '') ?>\" \n";
        $content .= "                   placeholder=\"Search...\" \n";
        $content .= "                   class=\"flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500\">\n";
        $content .= "            <button type=\"submit\" class=\"bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded\">\n";
        $content .= "                Search\n";
        $content .= "            </button>\n";
        $content .= "            <?php if (!empty(\$search)): ?>\n";
        $content .= "                <a href=\"/{$resourceNamePlural}\" class=\"bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded\">\n";
        $content .= "                    Clear\n";
        $content .= "                </a>\n";
        $content .= "            <?php endif; ?>\n";
        $content .= "        </div>\n";
        $content .= "    </form>\n\n";

        // Table
        $content .= "    <div class=\"overflow-x-auto bg-white rounded-lg shadow\">\n";
        $content .= "        <table class=\"min-w-full table-auto\">\n";
        $content .= "            <thead class=\"bg-gray-50\">\n";
        $content .= "                <tr>\n";

        // Table headers
        foreach ($this->config['list_fields'] as $field) {
            $fieldConfig = $this->getFieldConfig($field);
            $content .= "                    <th class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">\n";
            $content .= "                        " . ($fieldConfig['label'] ?? ucfirst($field)) . "\n";
            $content .= "                    </th>\n";
        }

        $content .= "                    <th class=\"px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider\">\n";
        $content .= "                        Actions\n";
        $content .= "                    </th>\n";
        $content .= "                </tr>\n";
        $content .= "            </thead>\n";
        $content .= "            <tbody class=\"bg-white divide-y divide-gray-200\">\n";
        $content .= "                <?php if (empty(\$items)): ?>\n";
        $content .= "                    <tr>\n";
        $content .= "                        <td colspan=\"" . (count($this->config['list_fields']) + 1) . "\" class=\"px-6 py-4 text-center text-gray-500\">\n";
        $content .= "                            No {$displayNamePlural} found\n";
        $content .= "                        </td>\n";
        $content .= "                    </tr>\n";
        $content .= "                <?php else: ?>\n";
        $content .= "                    <?php foreach (\$items as \$item): ?>\n";
        $content .= "                        <tr class=\"hover:bg-gray-50\">\n";

        // Table data
        foreach ($this->config['list_fields'] as $field) {
            $fieldConfig = $this->getFieldConfig($field);
            $content .= "                            <td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">\n";

            if ($fieldConfig['type'] === 'image') {
                $content .= "                                <?php if (\$item->{$field}): ?>\n";
                $content .= "                                    <img src=\"<?= \$view->e(\$item->{$field}) ?>\" alt=\"\" class=\"h-10 w-10 rounded-full object-cover\">\n";
                $content .= "                                <?php endif; ?>\n";
            } elseif ($fieldConfig['type'] === 'boolean') {
                $content .= "                                <?= \$item->{$field} ? '✓' : '✗' ?>\n";
            } else {
                $content .= "                                <?= \$view->e(\$item->{$field}) ?>\n";
            }

            $content .= "                            </td>\n";
        }

        // Action buttons
        $content .= "                            <td class=\"px-6 py-4 whitespace-nowrap text-right text-sm font-medium\">\n";
        $content .= "                                <a href=\"/{$resourceNamePlural}/edit/<?= \$item->id ?>\" class=\"text-indigo-600 hover:text-indigo-900 mr-2\">Edit</a>\n";
        $content .= "                                <form method=\"POST\" action=\"/{$resourceNamePlural}/delete/<?= \$item->id ?>\" class=\"inline\" onsubmit=\"return confirm('Are you sure?')\">\n";
        $content .= "                                    <?= csrf_field() ?>\n";
        $content .= "                                    <button type=\"submit\" class=\"text-red-600 hover:text-red-900\">Delete</button>\n";
        $content .= "                                </form>\n";
        $content .= "                            </td>\n";
        $content .= "                        </tr>\n";
        $content .= "                    <?php endforeach; ?>\n";
        $content .= "                <?php endif; ?>\n";
        $content .= "            </tbody>\n";
        $content .= "        </table>\n";
        $content .= "    </div>\n\n";

        // Pagination
        $content .= "    <div class=\"mt-6 flex justify-center\">\n";
        $content .= "        <?php if (\$pagination['total'] > 1): ?>\n";
        $content .= "            <nav class=\"flex space-x-2\">\n";
        $content .= "                <?php for (\$i = 1; \$i <= \$pagination['total']; \$i++): ?>\n";
        $content .= "                    <a href=\"?page=<?= \$i ?><?= !empty(\$search) ? '&search=' . urlencode(\$search) : '' ?>\"\n";
        $content .= "                       class=\"px-3 py-1 rounded <?= \$pagination['current'] == \$i ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' ?>\">\n";
        $content .= "                        <?= \$i ?>\n";
        $content .= "                    </a>\n";
        $content .= "                <?php endfor; ?>\n";
        $content .= "            </nav>\n";
        $content .= "        <?php endif; ?>\n";
        $content .= "    </div>\n";
        $content .= "</div>\n";
        file_put_contents($filename, $content);
        return 'index.php';
    }
    /**
     * Generate create view
     */
    private function generateCreateView(string $path): string
    {
        $filename = $path . '/create.php';
        $resourceNamePlural = $this->config['resource_plural'];
        $displayName = $this->config['display_name'];

        $content = "<?php \$view = new Core\\View(); ?>\n\n";
        $content .= "<div class=\"container mx-auto px-4 py-8\">\n";
        $content .= "    <h1 class=\"text-2xl font-bold mb-6\">Create {$displayName}</h1>\n";
        $content .= "    <form method=\"POST\" enctype=\"multipart/form-data\" action=\"/{$resourceNamePlural}/store\" class=\"space-y-4\">\n";
        $content .= "        <?php include __DIR__ . '/_form.php'; ?>\n";
        $content .= "        <div class=\"flex gap-2\">\n";
        $content .= "            <button type=\"submit\" class=\"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded\">Save</button>\n";
        $content .= "            <a href=\"/{$resourceNamePlural}\" class=\"bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded\">Cancel</a>\n";
        $content .= "        </div>\n";
        $content .= "    </form>\n";
        $content .= "</div>\n";

        file_put_contents($filename, $content);
        return 'create.php';
    }
    /**
     * Generate edit view
     */
    private function generateEditView(string $path): string
    {
        $filename = $path . '/edit.php';
        $resourceNamePlural = $this->config['resource_plural'];
        $displayName = $this->config['display_name'];

        $content = "<?php \$view = new Core\\View(); ?>\n\n";
        $content .= "<div class=\"container mx-auto px-4 py-8\">\n";
        $content .= "    <h1 class=\"text-2xl font-bold mb-6\">Edit {$displayName}</h1>\n";
        $content .= "    <form method=\"POST\" enctype=\"multipart/form-data\" action=\"/{$resourceNamePlural}/update/<?= \$item->id ?>\" class=\"space-y-4\">\n";
        $content .= "        <?php include __DIR__ . '/_form.php'; ?>\n";
        $content .= "        <div class=\"flex gap-2\">\n";
        $content .= "            <button type=\"submit\" class=\"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded\">Update</button>\n";
        $content .= "            <a href=\"/{$resourceNamePlural}\" class=\"bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded\">Cancel</a>\n";
        $content .= "        </div>\n";
        $content .= "    </form>\n";
        $content .= "</div>\n";

        file_put_contents($filename, $content);
        return 'edit.php';
    }
    /**
     * Generate form partial view
     */
    private function generateFormPartial(string $path): string
    {
        $filename = $path . '/_form.php';
        $fields = $this->config['fields'];

        $content = "<?php \$view = new Core\\View(); ?>\n\n";

        foreach ($fields as $field) {
            $label = ucfirst($field['name']);
            $name = $field['name'];
            $type = $field['type'];

            $content .= "<div>\n";
            $content .= "    <label class=\"block text-sm font-medium text-gray-700 mb-1\" for=\"{$name}\">{$label}</label>\n";

            if ($type === 'textarea') {
                $content .= "    <textarea name=\"{$name}\" id=\"{$name}\" class=\"w-full border px-3 py-2 rounded\" rows=\"4\"><?= \$view->old('{$name}', \$item->{$name} ?? '') ?></textarea>\n";
            } elseif ($type === 'boolean') {
                $content .= "    <input type=\"checkbox\" name=\"{$name}\" id=\"{$name}\" value=\"1\" <?= !empty(\$item->{$name}) ? 'checked' : '' ?>>\n";
            } elseif ($type === 'file' || $type === 'image') {
                $content .= "    <input type=\"file\" name=\"{$name}\" id=\"{$name}\" class=\"w-full border px-3 py-2 rounded\">\n";
                $content .= "    <?php if (!empty(\$item->{$name})): ?>\n";
                $content .= "        <div class=\"mt-2\">\n";
                if ($type === 'image') {
                    $content .= "            <img src=\"<?= \$view->e(\$item->{$name}) ?>\" class=\"h-20 w-20 object-cover rounded\">\n";
                } else {
                    $content .= "            <a href=\"<?= \$view->e(\$item->{$name}) ?>\" target=\"_blank\">Download</a>\n";
                }
                $content .= "        </div>\n";
                $content .= "    <?php endif; ?>\n";
            } else {
                $content .= "    <input type=\"text\" name=\"{$name}\" id=\"{$name}\" value=\"<?= \$view->old('{$name}', \$item->{$name} ?? '') ?>\" class=\"w-full border px-3 py-2 rounded\">\n";
            }

            $content .= "</div>\n\n";
        }

        file_put_contents($filename, $content);
        return '_form.php';
    }
    /**
     * Append routes to routes/web.php
     */
    private function appendRoutes(): void
    {
        $resource = strtolower($this->config['resource_plural']);
        $controller = ucfirst($this->config['resource']) . 'Controller';
        $routesFile = __DIR__ . '/../routes/web.php'; // adjust path if needed

        $routes = "\n\n// Auto-generated routes for {$this->config['display_name']}\n";

        if ($this->config['auth_protected']) {
            $routes .= "\$router->middleware([\\App\\Middlewares\\AuthMiddleware::class])->group(function (\$router) {\n";
        } else {
            $routes .= "/* Public Routes */\n";
        }

        $routes .= "    \$router->get('/{$resource}', '{$controller}@index');\n";
        $routes .= "    \$router->get('/{$resource}/create', '{$controller}@create');\n";
        $routes .= "    \$router->post('/{$resource}/store', '{$controller}@store');\n";
        $routes .= "    \$router->get('/{$resource}/edit/{id}', '{$controller}@edit');\n";
        $routes .= "    \$router->post('/{$resource}/update/{id}', '{$controller}@update');\n";
        $routes .= "    \$router->get('/{$resource}/delete/{id}', '{$controller}@destroy');\n";

        if ($this->config['auth_protected']) {
            $routes .= "});\n";
        }

        file_put_contents($routesFile, $routes, FILE_APPEND);
    }

    /**
     * Run migration to create table
     */
    private function runMigration(): void
    {
        $table = strtolower($this->config['resource_plural']);
        $fieldsSql = [];

        foreach ($this->config['fields'] as $field => $type) {
            $fieldsSql[] = "`$field` $type";
        }

        $sql = "CREATE TABLE IF NOT EXISTS `$table` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        " . implode(", ", $fieldsSql) . ",
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;";

        try {
            $pdo = new \PDO("mysql:host=localhost;dbname=your_db", "username", "password");
            $pdo->exec($sql);
            echo "Migration for $table executed successfully.\n";
        } catch (\PDOException $e) {
            echo "Migration failed: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Get field configuration by name
     */
    private function getFieldConfig(string $name): ?array
    {
        foreach ($this->config['fields'] as $field) {
            if ($field['name'] === $name) {
                return $field;
            }
        }
        return null;
    }

    /**
     * Check if any field is of type file or image
     */
    private function hasFileFields(): bool
    {
        foreach ($this->config['fields'] as $field) {
            if (in_array($field['type'], ['file', 'image'])) {
                return true;
            }
        }
        return false;
    }
    /**
     * Get SQL data type for a field
     */
    private function getSqlType(array $field): string
    {
        return match ($field['type']) {
            'string' => 'VARCHAR(' . ($field['length'] ?? 255) . ')',
            'text' => 'TEXT',
            'integer' => 'INT(11)',
            'bigint' => 'BIGINT(20)',
            'boolean' => 'TINYINT(1)',
            'date' => 'DATE',
            'datetime' => 'DATETIME',
            'float' => 'FLOAT',
            'decimal' => 'DECIMAL(' . ($field['precision'] ?? 8) . ',' . ($field['scale'] ?? 2) . ')',
            'file', 'image' => 'VARCHAR(255)',
            default => 'VARCHAR(255)',
        };
    }
    /**
     * Get SQL default value for a field
     */
    private function getSqlDefault(array $field): string
    {
        if (is_null($field['default'])) {
            return 'NULL';
        }
        return match ($field['type']) {
            'string', 'text', 'file', 'image', 'date', 'datetime' => "'" . addslashes($field['default']) . "'",
            'boolean' => $field['default'] ? '1' : '0',
            'integer', 'bigint', 'float', 'decimal' => (string) $field['default'],
            default => "'" . addslashes($field['default']) . "'",
        };
    }
    /**
     * Get validation rules for a field
     */
    private function getValidationRules(array $field): string
    {
        $rules = [];
        if (!empty($field['required'])) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }
        switch ($field['type']) {
            case 'string':
                $rules[] = 'string';
                if (!empty($field['length'])) {
                    $rules[] = 'max:' . $field['length'];
                }
                break;
            case 'text':
                $rules[] = 'string';
                break;
            case 'integer':
                $rules[] = 'integer';
                break;
            case 'bigint':
                $rules[] = 'integer';
                break;
            case 'boolean':
                $rules[] = 'boolean';
                break;
            case 'date':
                $rules[] = 'date';
                break;
            case 'datetime':
                $rules[] = 'date';
                break;
            case 'float':
                $rules[] = 'numeric';
                break;
            case 'decimal':
                $rules[] = 'numeric';
                break;
            case 'file':
                $rules[] = 'file';
                if (!empty($field['mimes'])) {
                    $rules[] = 'mimes:' . implode(',', $field['mimes']);
                }
                if (!empty($field['max_size'])) {
                    $rules[] = 'max:' . $field['max_size'];
                }
                break;
            case 'image':
                $rules[] = 'image';
                if (!empty($field['mimes'])) {
                    $rules[] = 'mimes:' . implode(',', $field['mimes']);
                }
                if (!empty($field['max_size'])) {
                    $rules[] = 'max:' . $field['max_size'];
                }
                break;
            case 'password':
                $rules[] = 'string';
                if (!empty($field['length'])) {
                    $rules[] = 'min:' . min(8, (int)$field['length']);
                    $rules[] = 'max:' . $field['length'];
                } else {
                    $rules[] = 'min:8';
                }
                break;
        }
        return implode('|', $rules);
    }
    /**
     * Convert string to PascalCase
     */
    private function toPascalCase(string $string): string
    {
        if ($string === null) {
            $string = '';
        }
        $string = str_replace(['-', '_'], ' ', $string);
        $string = ucwords($string);
        return str_replace(' ', '', $string);
    }
    /**
     * Create directory if it doesn't exist
     */
    private function createDirectory(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }
}
