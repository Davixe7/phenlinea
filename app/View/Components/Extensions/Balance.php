<?php

namespace App\View\Components\Extensions;

use App\Extension;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Balance extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
      public Extension $extension,
      public string $startdate,
      public string $enddate,
      public Collection $invoices,
      public string $endpoint = '',
      public float $total = 0
      ) {
        
        $this->endpoint = auth()->check()
                          ? route('extensions.balance', ['extension'=>$this->extension->id])
                          : route('public.resident-invoices.balance');
      }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.extensions.balance');
    }
}
