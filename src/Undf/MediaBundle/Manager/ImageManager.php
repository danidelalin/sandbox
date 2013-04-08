<?php

namespace Undf\MediaBundle\Manager;

use Symfony\Component\HttpFoundation\File\File;
use \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

abstract class ImageManager
{

    /**
     *
     * @param type $filename
     * @return type
     */
    protected function _getFileContent(File $file)
    {
        if ($file->isFile()) {

            //Get the file content
            ob_start();
            @readfile($file->getPathname());
            $content = ob_get_contents();
            ob_end_clean();

            return $content;
        }
        throw new InvalidArgumentException(sprintf('Given file with name "%s is not a regular file."',$file->getFilename()));
    }

}
