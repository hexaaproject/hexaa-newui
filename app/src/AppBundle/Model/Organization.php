<?php
namespace AppBundle\Model;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Organization
 * @package AppBundle\Model
 */
class Organization extends AbstractBaseResource
{
    protected $pathName = 'organizations';

    /**
     * GET managers of Organization
     *
     * @param string $id       ID of organization
     * @param string $verbose  One of minimal, normal or expanded
     * @param int    $offset   paging: item to start from
     * @param int    $pageSize paging: number of items to return
     * @return array
     */
    public function getManagers(string $id, string $verbose = "normal", int $offset = 0, int $pageSize = 25): array
    {
        return $this->getCollection($this->pathName.'/'.$id.'/managers', $verbose, $offset, $pageSize);
    }

    /**
     * GET members of Organization
     *
     * @param string $id       ID of organization
     * @param string $verbose  One of minimal, normal or expanded
     * @param int    $offset   paging: item to start from
     * @param int    $pageSize paging: number of items to return
     * @return array
     */
    public function getMembers(string $id, string $verbose = "normal", int $offset = 0, int $pageSize = 25): array
    {
        return $this->getCollection($this->pathName.'/'.$id.'/members', $verbose, $offset, $pageSize);
    }

    /**
     * DELETE members of Organization
     *
     * @param string $id  ID of organization
     * @param string $pid Principal ID
     * @return ResponseInterface
     */
    public function deleteMember(string $id, string $pid)
    {
        $path = $this->pathName.'/'.$id.'/members/'.$pid;

        $response = $this->client->delete(
            $path,
            [
                'headers' => $this->getHeaders(),
            ]
        );

        return $response;
    }


    /**
     * GET roles of Organization
     *
     * @param string $id       ID of organization
     * @param string $verbose  One of minimal, normal or expanded
     * @param int    $offset   paging: item to start from
     * @param int    $pageSize paging: number of items to return
     * @return array
     */
    public function getRoles(string $id, string $verbose = "normal", int $offset = 0, int $pageSize = 25): array
    {
        return $this->getCollection($this->pathName.'/'.$id.'/roles', $verbose, $offset, $pageSize);
    }

    /**
     * GET entitlements of Organization
     *
     * @param string $id       ID of organization
     * @param string $verbose  One of minimal, normal or expanded
     * @param int    $offset   paging: item to start from
     * @param int    $pageSize paging: number of items to return
     * @return array
     */
    public function getEntitlements(string $id, string $verbose = "normal", int $offset = 0, int $pageSize = 25): array
    {
        return $this->getCollection($this->pathName.'/'.$id.'/entitlements', $verbose, $offset, $pageSize);
    }


    /**
     * GET entitlement packs of Organization
     *
     * @param string $id       ID of organization
     * @param string $verbose  One of minimal, normal or expanded
     * @param int    $offset   paging: item to start from
     * @param int    $pageSize paging: number of items to return
     * @return array
     */
    public function getEntitlementPacks(string $id, string $verbose = "normal", int $offset = 0, int $pageSize = 25): array
    {
        return $this->getCollection($this->pathName.'/'.$id.'/entitlementpacks', $verbose, $offset, $pageSize);
    }

    public function create(string $name, string $description = null)
    {
        $organizationData = array();
        $organizationData['name'] = $name;
        if ($description) {
            $organizationData['description'] = $description;
        }

        $response = $this->post($organizationData);
        $locations = $response->getHeader('Location');
        $location = $locations[0];
        $organizationId = preg_replace('#.*/#', '', $location);

        return $this->get($organizationId, "expanded");
    }


    /**
     * Create new role
     *
     * @param string $id
     * @param string $name
     * @return ResponseInterface
     */
    public function createRole(string $id, string $name){
        $response = $this->postCall($this->pathName.'/'.$id.'/roles', array("name" => $name));
        $locations = $response->getHeader('Location');
        $location = $locations[0];
        $id = preg_replace('#.*/#', '', $location);

        return $this->get($id);
    }

}
