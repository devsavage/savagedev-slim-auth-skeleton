<?php
namespace SavageDev\Http\Controllers;

use SavageDev\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function get()
    {
        return $this->render("home");
    }
}
