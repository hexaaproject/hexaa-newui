<?php
/**
 * Created by PhpStorm.
 * User: gyufi
 * Date: 2017. 12. 12.
 * Time: 13:40
 */

namespace AppBundle\Controller;

use AppBundle\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BaseController
 *
 * @package AppBundle\Controller
 */
class BaseController extends Controller
{
    /**
     * @param $entity
     *
     * @return bool
     * @throws \Exception
     *
     *  TODO
     */
    protected function amIManagerOfThis($entity)
    {
        if (!$entity) {
            throw new Exception("TODO");
        }

        return true;
    }

    protected function getEntityShowPath($entity)
    {
        if ($this instanceof OrganizationController) {
            return $this->generateUrl("app_organization_show", array("id" => $entity["id"]));
        }

        return "#";
    }
}