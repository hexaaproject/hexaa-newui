<?php
/**
 * Copyright 2016-2018 MTA SZTAKI ugyeletes@sztaki.hu
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

/**
 * Created by PhpStorm.
 * User: gyufi
 * Date: 2018. 03. 08.
 * Time: 8:49
 */

namespace AppBundle\Tools\Warning;

/**
 * Class NoRolesWarning
 *
 * @package AppBundle\Tools\Warning
 */
class NoConnectedOrganizationWarning extends Warning
{

    /**
     * @return string
     */
    public function getClass()
    {
        return "No services";
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        return "This organization doesn't connected to any service.";
    }
}
