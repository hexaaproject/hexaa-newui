<?php
namespace AppBundle\Model;

use GuzzleHttp\Client;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Principal
 * @package AppBundle\Model
 */
class Principal extends AbstractBaseResource
{
    protected $pathName = 'principal';

    /**
     * GET the current Principal
     *
     * @param string $verbose    One of minimal, normal or expanded
     * @param string $hexaatoken hexaa api token
     * @return array
     */
    public function getSelf(string $verbose = "normal", $hexaatoken = null)
    {
        if ($hexaatoken) {
            $this->token = $hexaatoken;
        }

        return $this->getSingular($this->pathName.'/self', $verbose);
    }


    /**
     * GET attribute values of the current Principal
     *
     * @param string $verbose  One of minimal, normal or expanded
     * @param int    $offset   paging: item to start from
     * @param int    $pageSize paging: number of items to return
     * @return array
     */
    public function getAttributeValues(string $verbose = "normal", int $offset = 0, int $pageSize = 25)
    {
        return $this->getCollection($this->pathName.'/attributevalueprincipal', $verbose, $offset, $pageSize);
    }
}
