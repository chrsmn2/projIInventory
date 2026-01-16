<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\OutgoingItem;
use App\Models\Loan;

class SupervisorController extends Controller
{
     public function dashboard(){
        $pendingOutgoing = OutgoingItem::where('status','pending')->count();
        $pendingLoan = Loan::where('status','pending')->count();
        return view('dashboard.supervisor', compact('pendingOutgoing','pendingLoan'));
    }

    public function items(){
        $items = Item::all();
        return view('items.index', compact('items'));
    }
}
