<?php

namespace Undf\MediaBundle\Controller;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Gaufrette\File;

class ImagineController
{

    /**
     * @var DataManager
     */
    protected $dataManager;

    /**
     * @var FilterManager
     */
    protected $filterManager;

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * Constructor.
     *
     * @param DataManager $dataManager
     * @param FilterManager $filterManager
     * @param CacheManager $cacheManager
     */
    public function __construct(DataManager $dataManager, FilterManager $filterManager, CacheManager $cacheManager)
    {
        $this->dataManager = $dataManager;
        $this->filterManager = $filterManager;
        $this->cacheManager = $cacheManager;
    }

    /**
     * This action applies a given filter to a given image, optionally saves the image and outputs it to the browser at the same time.
     *
     * @param Request $request
     * @param string $path
     * @param string $filter
     *
     * @return Response
     */
    public function filterAction(Request $request, $path, $filter)
    {
        $targetPath = $this->cacheManager->resolve($request, $path, $filter);
        if ($targetPath instanceof Response) {
            return $this->cacheResponse($targetPath, $filter);
        }
        if ($targetPath instanceof File) {
            return $this->cacheResponse($this->createResponse($targetPath->getContent(), $filter), $filter);
        }

        $image = $this->dataManager->find($filter, $path);
        $response = $this->filterManager->get($request, $filter, $image, $path);

        if ($targetPath) {
            $response = $this->cacheManager->store($response, $targetPath, $filter);
        }

        return $this->cacheResponse($response, $filter);
    }

    public function createResponse($content, $filter)
    {
        ob_start();
        try {
            $config = $this->filterManager->getFilterConfiguration()->get($filter);
            $format = $config['format'] ? : "png";

            echo $content;

            $type = 'image/' . $format;
            $length = ob_get_length();
            $content = ob_get_clean();

            // TODO: add more media headers
            $response = new Response($content, 201, array(
                'content-type' => $type,
                'content-length' => $length,
            ));
            return $response;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }

    /**
     * Add cache headers to the given response
     *
     * @param Response $response
     * @param string $filter
     *
     * @return Response
     */
    public function cacheResponse(Response $response, $filter)
    {
        $config = $this->filterManager->getFilterConfiguration()->get($filter);
        $cacheType = false;
        $cacheExpires = '1 day';
        foreach ($config['filters'] as $filter) {
            $cacheType = isset($filter["cache_type"]) ? $filter["cache_type"] : $cacheType;
            $cacheExpires = isset($filter["cache_type"]) ? $filter["cache_expires"] : $cacheExpires;
        }
        if (false == $cacheType) {
            return $response;
        }

        ($cacheType === "public") ? $response->setPublic() : $response->setPrivate();

        $expirationDate = new \DateTime("+" . $cacheExpires);
        $maxAge = $expirationDate->format("U") - time();
        if ($maxAge < 0) {
            throw new \InvalidArgumentException("Invalid cache expiration date");
        }

        $response->setExpires($expirationDate);
        $response->setMaxAge($maxAge);

        return $response;
    }

}
