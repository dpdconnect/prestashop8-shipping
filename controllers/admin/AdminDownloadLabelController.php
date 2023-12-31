<?php

/**
 * This file is part of the Prestashop Shipping module of DPD Nederland B.V.
 *
 * Copyright (C) 2017  DPD Nederland B.V.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

require_once(_PS_MODULE_DIR_ . 'dpdconnect' . DIRECTORY_SEPARATOR . 'dpdconnect.php');

use DpdConnect\classes\Database\LabelRepo;

class AdminDownloadLabelController extends ModuleAdminController
{
    public $dpdParcelPredict;
    public $dpdLabelGenerator;
    public $delete;
    public $return;
    public $orderIds;

    public function __construct()
    {
        parent::__construct();
        $dpdconnect = new dpdconnect();
        $this->dpdLabelGenerator = $dpdconnect->dpdLabelGenerator();
        $this->orderIds = Tools::getValue('ids_order');
        $this->delete = Tools::getValue('delete');
        $this->parcels = Tools::getValue('parcel');
        $this->return = (bool)Tools::getValue('return');
    }

    public function checkErrors()
    {
        if (!empty($this->dpdLabelGenerator->errors)) {
            foreach ($this->dpdLabelGenerator->errors as $errorMessage) {
                $this->errors[] = $this->l($errorMessage);
            }
        }
    }

    public function initContent()
    {
        parent::initContent();

        $labelId = Tools::getValue('label_id');
        $labelRepo = new LabelRepo();
        $label = $labelRepo->get($labelId);
        $this->dpdLabelGenerator->redirectToPdfDownload(
            [
                'pdf' => $label['label'],
            ]
        );
    }
}
