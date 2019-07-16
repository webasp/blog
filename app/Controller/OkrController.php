<?php
namespace app\Controller;


use app\Model\Okr;

class OkrController extends BasicController
{

    public function index($Year = null)
    {
        if(!$Year){$Year = date('Y');}

        $YearAll = (new Okr())->getYear();
        $Okr = (new Okr())->getOkr($Year);
        $YearOkr = (new Okr())->getYearOkr($Year);
        $this->assign('YearAll',$YearAll);
        $this->assign('Year',$Year);
        $this->assign('YearOkr',$YearOkr);
        $this->assign('okr',$Okr);
        $this->display('home/okr');
    }





}