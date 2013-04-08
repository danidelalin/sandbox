<?php

namespace Undf\MediaBundle\Imagine\Cache\Resolver;

use Gaufrette\Filesystem;
use Liip\ImagineBundle\Imagine\Cache\Resolver\ResolverInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GaufretteResolver implements ResolverInterface
{

    /**
     * @var Gaufrette\Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $basePath = '';

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * Constructs a filesystem based cache resolver.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Set the base path to
     *
     * @param $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param CacheManager $cacheManager
     */
    public function setCacheManager(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * Resolves filtered path for rendering in the browser.
     *
     * @param Request $request The request made against a _imagine_* filter route.
     * @param string $path The path where the resolved file is expected.
     * @param string $filter The name of the imagine filter in effect.
     *
     * @return string|Response The target path to be used in other methods of this Resolver,
     *                         a Response may be returned to avoid calling store upon resolution.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException In case the path can not be resolved.
     */
    public function resolve(Request $request, $path, $filter)
    {
        $browserPath = $this->decodeBrowserPath($this->getBrowserPath($path, $filter));
        $this->basePath = $request->getBaseUrl();
        $targetPath = $this->getFilePath($path, $filter);

        $filename = pathinfo($targetPath, PATHINFO_BASENAME);
        $path = $filter . DIRECTORY_SEPARATOR . $filename;

        // if the file has already been cached, we're probably not rewriting
        // correctly, hence make a 301 to proper location, so browser remembers
        if ($this->filesystem->has($path)) {
            return $this->filesystem->get($path);
        }

        return $targetPath;
    }

    /**
     * Stores the content of the given Response.
     *
     * @param Response $response The response provided by the _imagine_* filter route.
     * @param string $targetPath The target path provided by the resolve method.
     * @param string $filter The name of the imagine filter in effect.
     *
     * @return Response The (modified) response to be sent to the browser.
     */
    function store(Response $response, $targetPath, $filter)
    {
        $filename = pathinfo($targetPath, PATHINFO_BASENAME);
        $path = $filter . DIRECTORY_SEPARATOR . $filename;
        try {
            $this->filesystem->write($path, $response->getContent(), true);
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Could not create directory "%s" inside the cache directory', $filter), 0, $e);
        }
        $response->setStatusCode(201);

        return $response;
    }

    /**
     * Returns a web accessible URL.
     *
     * @param string $path The path where the resolved file is expected.
     * @param string $filter The name of the imagine filter in effect.
     * @param bool $absolute Whether to generate an absolute URL or a relative path is accepted.
     *                       In case the resolver does not support relative paths, it may ignore this flag.
     *
     * @return string
     */
    function getBrowserPath($targetPath, $filter, $absolute = false)
    {
        return $this->cacheManager->generateUrl($targetPath, $filter, $absolute);
    }

    /**
     * Removes a stored image resource.
     *
     * @param string $targetPath The target path provided by the resolve method.
     * @param string $filter The name of the imagine filter in effect.
     *
     * @return bool Whether the file has been removed successfully.
     */
    function remove($targetPath, $filter)
    {
        $filename = pathinfo($targetPath, PATHINFO_BASENAME);
        $path = $filter . DIRECTORY_SEPARATOR . $filename;
        $this->filesystem->delete($path);
        return !$this->filesystem->has($path);
    }

    /**
     * Clear the CacheResolver cache.
     *
     * @param string $cachePrefix The cache prefix as defined in the configuration.
     *
     * @return void
     */
    public function clear($cachePrefix)
    {
        foreach ($this->filesystem->keys() as $key) {
            if (false !== strpos($key, DIRECTORY_SEPARATOR)) {
                $this->filesystem->delete($key);
            }
        }
        foreach ($this->filesystem->keys() as $key) {
            $this->filesystem->delete($key);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function getFilePath($path, $filter)
    {
        $browserPath = $this->decodeBrowserPath($this->getBrowserPath($path, $filter));

        if (!empty($this->basePath) && 0 === strpos($browserPath, $this->basePath)) {
            $browserPath = substr($browserPath, strlen($this->basePath));
        }

        return $this->cacheManager->getWebRoot() . $browserPath;
    }

    /**
     * Decodes the URL encoded browser path.
     *
     * @param string $browserPath
     *
     * @return string
     */
    protected function decodeBrowserPath($browserPath)
    {
        //TODO: find out why I need double urldecode to get a valid path
        return urldecode(urldecode($browserPath));
    }

}
