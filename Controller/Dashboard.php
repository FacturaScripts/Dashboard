<?php
/**
 * This file is part of Dashboard plugin for FacturaScripts
 * Copyright (C) 2018 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\Dashboard\Controller;

use FacturaScripts\Core\Base;
use FacturaScripts\Dinamic\Lib;
use FacturaScripts\Dinamic\Model;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dashboard that contains some Cards with data to the end user.
 *
 * @author Carlos García Gómez  <carlos@facturascripts.com>
 * @author Artex Trading sa     <jcuello@artextrading.com>
 */
class Dashboard extends Base\Controller
{

    /**
     * List of components of dashboard.
     *
     * @var Lib\Dashboard\BaseComponent[]
     */
    public $components = [];

    /**
     * Returns basic page attributes
     *
     * @return array
     */
    public function getPageData()
    {
        $pageData = parent::getPageData();
        $pageData['title'] = 'dashboard';
        $pageData['menu'] = 'reports';
        $pageData['icon'] = 'fas fa-tachometer-alt';

        return $pageData;
    }

    /**
     * Runs the controller's private logic.
     *
     * @param Response                   $response
     * @param Model\User                 $user
     * @param Base\ControllerPermissions $permissions
     */
    public function privateCore(&$response, $user, $permissions)
    {
        parent::privateCore($response, $user, $permissions);
        $this->getListComponents($user->nick);
        $this->loadDataComponents();
    }

    /**
     * Get the list of components to this user.
     *
     * @param $userNick
     */
    private function getListComponents($userNick)
    {
        $dashboardModel = new Model\Dashboard();
        $rows = $dashboardModel->all();
        foreach ($rows as $data) {
            $componentName = Lib\Dashboard\BaseComponent::DIR_COMPONENTS
                . $data->component
                . Lib\Dashboard\BaseComponent::SUFIX_COMPONENTS;

            $this->components[$data->component] = new $componentName($data, $userNick);
        }
    }

    /**
     * Load the needed data of components.
     */
    private function loadDataComponents()
    {
        foreach ($this->components as $component) {
            $component->loadData();
        }
    }
}
