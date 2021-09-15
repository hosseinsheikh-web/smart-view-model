<?php

namespace HosseinSheikh\ViewModel;

use HosseinSheikh\ViewModel\Contracts\BaseViewModelContract;

class BaseViewModel implements BaseViewModelContract
{
    public $request;

    /**
     * BaseViewModel constructor.
     */
    public function __construct()
    {
        $this->request = \request();
    }


    /**
     * @param Request $request
     * @return $this
     */
    /*public function setRequest(Request $request = null)
    {
        $this->request = $request ?: \request();

        return $this;
    }*/

    public function request()
    {
        return $this->request;
    }

}
