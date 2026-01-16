<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\IncomingItem;
use App\Models\Item; 
use Illuminate\Http\Request;   
use Illuminate\Support\Facades\Auth;

class IncomingController extends Controller
{
        public function index()
        {
            $incomingItems = IncomingItem::with('details.item', 'admin')
                ->where('status', 'pending')
                ->latest()
                ->get();

            return view('supervisor.incoming.index', compact('incomingItems'));
        }

        public function approve(IncomingItem $incoming)
        {
            // Update stock AFTER approval
            foreach ($incoming->details as $detail) {
                $item = Item::find($detail->item_id);
                $item->stok += $detail->quantity;
                $item->save();
            }

            $incoming->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            return back()->with('success', 'Incoming item approved');
        }

        public function reject(Request $request, IncomingItem $incoming)
        {
            $request->validate([
                'reject_reason' => 'required|string'
            ]);

            $incoming->update([
                'status' => 'rejected',
                'reject_reason' => $request->reject_reason,
                'approved_by' => Auth::id(),
            ]);

            return back()->with('success', 'Incoming item rejected');
        }
}
