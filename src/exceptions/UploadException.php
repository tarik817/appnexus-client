<?php

namespace Audiens\AppnexusClient\exceptions;

use Audiens\AppnexusClient\repository\RepositoryResponse;

/**
 * Class UploadException
 */
class UploadException extends \Exception
{

    /**
     * @param RepositoryResponse $repositoryResponse
     *
     * @return self
     */
    public static function failed(RepositoryResponse $repositoryResponse)
    {
        return new self('Failed call: '.$repositoryResponse->getError()->getError());
    }

    /**+
     * @param $missingIndex
     *
     * @return self
     */
    public static function missingIndex($missingIndex)
    {
        return new self('Invalid reposnse missing: '.$missingIndex);
    }

    /**
     * @return self
     */
    public static function emptyFile()
    {
        return new self('The content of the file you are about to upload is empty');
    }
}
