<?php


namespace App\Library;

use Phalcon\Exception;

class Common
{
    /**
     * @param $path
     * @param string $manifestDirectory
     * @return string
     * @throws Exception
     */
    public static function mix($path, $manifestDirectory = '')
    {
        static $manifests = [];

        if (! self::startsWith($path, '/')) {
            $path = "/{$path}";
        }

        if ($manifestDirectory && ! self::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        $manifestPath = BASE_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . ltrim($manifestDirectory.'/mix-manifest.json', DIRECTORY_SEPARATOR);

        if (! isset($manifests[$manifestPath])) {
            if (! file_exists($manifestPath)) {
                throw new Exception('The Mix manifest does not exist.');
            }

            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }

        $manifest = $manifests[$manifestPath];

        if (! isset($manifest[$path])) {
            throw new Exception("Unable to locate Mix file: {$path}.");

        }

        return $manifestDirectory.$manifest[$path];
    }

    /**
     * @param $haystack
     * @param $needles
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
    }
}
