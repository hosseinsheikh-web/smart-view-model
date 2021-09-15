<?php

namespace HosseinSheikh\ViewModel\Traits;

use Illuminate\Http\Request;

trait RequestTrait
{

    public $request;

    /**
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @param array $items --> [key1 => value1 , key2=> value2 , ...]
     * @param Request|null $request
     * @return $this
     */
    public function setItemsRequest(array $items = [], Request $request = null)
    {
        if (!$this->request && !$request) {
            return $this;
        }

        if ($request) {
            $this->request = $request;
        }

        if (empty(array_keys($items))) {
            return $this;
        }

        $this->request->request->add($items);

        return $this;
    }

}
