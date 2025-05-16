<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Helpers\RequestManagement;


class Navbar extends Component
{
    public $total_count=0;
    public function redirectToDashboard()
    {
        $this->js("window.open('http://127.0.0.1:8000/ccis_erma/management')");
    }
  
    public function redirectToMyRequests()
    {
        return redirect('/my-requests');
    }
    public function redirectToMyProfile()
    {
        return redirect('/my-profile');
    }
    public function redirectToHome()
    {
        return redirect('/');
    }
    public function redirectToEquipment()
    {
        return redirect('/equipment');
    }
    public function signout()
    {
        return redirect('/signout');
    }

    public function mount(){
        $this->total_count = count(RequestManagement::getRequestListEquipmentFromCookie());
    }

    #[On('update-requests-count')]
    public function updateRequestsCount($total_count){
        $this->total_count = $total_count;
    }



    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
