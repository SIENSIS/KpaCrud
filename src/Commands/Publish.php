<?php

namespace SIENSIS\KpaCrud\Commands;

use Config\Autoload;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;

class Publish extends BaseCommand
{
    /**
     * The group the command is lumped under
     * when listing commands.
     *
     * @var string
     */
    protected $group = 'KpaCrud';

    /**
     * The Command's name
     *
     * @var string
     */
    protected $name = 'kpacrud:publish';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Publish selected KpaCrud help local files (config, democontroller) into the current application.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'kpacrud:publish';

    /**
     * the Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * the Command's Options
     *
     * @var array
     */
    protected $options = [
        '-f'    => 'Force overwrite ALL existing files in destination',
    ];

    /**
     * The path to SIENSIS\KpaCrud\src directory.
     *
     * @var string
     */
    protected $sourcePath;

    /**
     * Whether the Views were published for local use.
     *
     * @var bool
     */
    protected $viewsPublished = false;

    //--------------------------------------------------------------------

    /**
     * Displays the help for the spark cli script itself.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $this->determineSourcePath();


        // Controller
        if (CLI::prompt('Publish demo Controller?', ['y', 'n']) == 'y') {
            $this->publishController();
        }

        // Views
        if (CLI::prompt('Publish Views?', ['y', 'n']) == 'y') {
            $this->publishViews();
            $this->viewsPublished = true;
        }

        // Config
        if (CLI::prompt('Publish Config file?', ['y', 'n']) == 'y') {
            $this->publishConfig();
        }

        // Language
        if (CLI::prompt('Publish Language file?', ['y', 'n']) == 'y') {
            $this->publishLanguage();
        }
    }

    protected function publishController()
    {
        $path = "{$this->sourcePath}/Controllers/KpaCrudSampleController.php";

        $content = file_get_contents($path);
        $content = $this->replaceNamespace($content, 'SIENSIS\KpaCrud\Controllers', 'Controllers');
        $content = str_replace('\SIENSIS\KpaCrud\Views\sample\sample', "kpacrud/sample", $content);

        $this->writeFile("Controllers/KpaCrudSampleController.php", $content);       
    }

    protected function publishViews()
    {
        $map = directory_map($this->sourcePath . '/Views/sample');
        $prefix = '';

        foreach ($map as $key => $view)
        {
            if (is_array($view))
            {
                $oldPrefix = $prefix;
                $prefix .= $key;

                foreach ($view as $file)
                {
                    $this->publishView($file, $prefix);
                }

                $prefix = $oldPrefix;

                continue;
            }

            $this->publishView($view, $prefix);
        }
    }

    protected function publishView($view, string $prefix = '')
    {
        $path = "{$this->sourcePath}/Views/sample/{$prefix}{$view}";
		$namespace = defined('APP_NAMESPACE') ? APP_NAMESPACE : 'App';

        $content = file_get_contents($path);

        $this->writeFile("Views/kpacrud/{$prefix}{$view}", $content);
    }

    protected function publishConfig()
    {
        $path = "{$this->sourcePath}/Config/KpaCrud.php";

        $content = file_get_contents($path);
        $content = str_replace('namespace SIENSIS\KpaCrud\Config', "namespace Config", $content);
        $content = str_replace("use CodeIgniter\Config\BaseConfig;", '', $content);
        $content = str_replace('extends BaseConfig', "extends \SIENSIS\KpaCrud\Config\KpaCrud", $content);

        $this->writeFile("Config/KpaCrud.php", $content);
    }

    protected function publishLanguage()
    {
        $path = "{$this->sourcePath}/Language/en/crud.php";

        $content = file_get_contents($path);

        $this->writeFile("Language/en/crud.php", $content);

        $path = "{$this->sourcePath}/Language/ca/crud.php";
        $content = file_get_contents($path);
        $this->writeFile("Language/ca/crud.php", $content);
    }

    //--------------------------------------------------------------------
    // Utilities
    //--------------------------------------------------------------------

    /**
     * Replaces the SIENSIS\KpaCrud namespace in the published
     * file with the applications current namespace.
     *
     * @param string $contents
     * @param string $originalNamespace
     * @param string $newNamespace
     *
     * @return string
     */
    protected function replaceNamespace(string $contents, string $originalNamespace, string $newNamespace): string
    {
        $appNamespace = APP_NAMESPACE;
        $originalNamespace = "namespace {$originalNamespace}";
        $newNamespace = "namespace {$appNamespace}\\{$newNamespace}";

        return str_replace($originalNamespace, $newNamespace, $contents);
    }

    /**
     * Determines the current source path from which all other files are located.
     */
    protected function determineSourcePath()
    {
        $this->sourcePath = realpath(__DIR__ . '/../');

        if ($this->sourcePath == '/' || empty($this->sourcePath)) {
            CLI::error('Unable to determine the correct source directory. Bailing.');
            exit();
        }
    }

    /**
     * Write a file, catching any exceptions and showing a
     * nicely formatted error.
     *
     * @param string $path
     * @param string $content
     */
    protected function writeFile(string $path, string $content)
    {
        $config = new Autoload();
        $appPath = $config->psr4[APP_NAMESPACE];

        $filename = $appPath . $path;
        $directory = dirname($filename);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if (file_exists($filename)) {
            $overwrite = (bool) CLI::getOption('f');

            if (!$overwrite && CLI::prompt("  File '{$path}' already exists in destination. Overwrite?", ['n', 'y']) === 'n') {
                CLI::error("  Skipped {$path}. If you wish to overwrite, please use the '-f' option or reply 'y' to the prompt.");
                return;
            }
        }

        if (write_file($filename, $content)) {
            CLI::write(CLI::color('  Created: ', 'green') . $path);
        } else {
            CLI::error("  Error creating {$path}.");
        }
    }
}
