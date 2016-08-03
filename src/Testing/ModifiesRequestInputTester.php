<?php
namespace FewAgency\Reformulator\Testing;

use FewAgency\Reformulator\Support\ModifiesRequestInputTrait;
use Illuminate\Http\Request;

class ModifiesRequestInputTester
{
    use ModifiesRequestInputTrait;

    public function testSetRequestInput(Request $request, $key, $value)
    {
        return $this->setRequestInput($request, $key, $value);
    }
}