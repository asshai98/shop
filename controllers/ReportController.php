<?php

namespace App\Controllers;

use LengthException;

class ReportController extends \App\Core\Controller {
    public function __pre() {
        $adminId = $this->getSession()->get('admin_id', null);

        if ($adminId === null){
            $this->redirect('/admin/login');
        }
    }
    public function getDetailsByYear($year = NULL){
        if(!$year){
            $year = date('Y');
        }
        
        $reportModel = new \App\Models\ReportModel($this->getDatabaseConnection());
        $report = $reportModel->viewOrderDetailsByYear($year);        
        $this->set('report', $report);
        $this->set('currentYear', $year);

        $this->set('years', $reportModel -> getDistinctYears());
        $poMesecima = [0,0,0,0,0,0,0,0,0,0,0,0];
        foreach ($report as $item){
            $poMesecima[$item->month-1]=$item->total;
        }

        $this->set("data", json_encode($poMesecima));

    }

    public function getItemDetails($dateFrom=NULL, $dateTo=NULL){
        if(!$dateFrom && !$dateTo){
            $dateFrom = date('j-m-Y');
            $dateTo = date('j-m-Y');
        }

        $reportModel = new \App\Models\ReportModel($this->getDatabaseConnection());
        $report = $reportModel->viewItemDetails($dateFrom, $dateTo); 
        $this->set("reports", $report);

        $this->set('report', $report);
        $this->set('dateFrom', $dateFrom);
        $this->set('dateTo', $dateTo);
        $this->set('dates', $reportModel->getDistinctDates());

        $labels = [];
        $data = [];

        foreach ($report as $item){
            $labels[] = str_replace('"', '\\"', $item->name) . ' (' . $item->count . ')';
            $data[]   = $item->total;

        }

        $this->set("labels", json_encode($labels));
        $this->set("data", json_encode($data));

    }

    public function getBuyerDetails($year=NULL){
        if(!$year){
            $year = date('Y');
        }

        $reportModel = new \App\Models\ReportModel($this->getDatabaseConnection());
        $report = $reportModel->viewBuyerDetails($year); 
        $this->set("reports", $report);
        $this->set('currYear', $year);

        $this->set('years', $reportModel->getDistinctYears());

    }

    public function exportBuyerDetails($year){
        $this->getBuyerDetails($year);
        $this->sendJSON("Po godinama " . $year, "reports");
    }

    public function exportByYearDetails($year){
        $this->getDetailsByYear($year);
        $this->sendJSON("Po godinama " . $year, "report");
    }

    public function exportByDateDetails($dateFrom, $dateTo){
        $this->getItemDetails($dateFrom, $dateTo);
        $this->sendJSON("Po datumima " . $dateFrom . '-' . $dateTo, "report");
    }

    public function getReports(){}

}