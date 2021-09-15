<?php

namespace HosseinSheikh\ViewModel\Contracts;

use Illuminate\Http\Request;

interface SmartViewModelContract
{
    public function throughViewModel($viewModel);

    public function thenReturn();

    public function via();

    public function getMethod();

    public function addItems(array $items = [], Request $request = null);
}
