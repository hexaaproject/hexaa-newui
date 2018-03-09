<?php
/**
 * Created by PhpStorm.
 * User: gyufi
 * Date: 2018. 03. 09.
 * Time: 9:09
 */

namespace AppBundle\Tools\Warning;


interface WarningableInterface
{
    /**
     * @param       $id
     * @param array $resources
     *
     * @return mixed
     */
    public function getWarnings($id, array $resources);
}