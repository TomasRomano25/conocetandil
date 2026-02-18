<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelContact;
use Illuminate\Http\Request;

class HotelContactController extends Controller
{
    public function index(Request $request)
    {
        $query = HotelContact::with('hotel')->latest();

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        $contacts = $query->paginate(25)->withQueryString();
        $hotels   = Hotel::orderBy('name')->get(['id', 'name']);

        return view('admin.hotel-contactos.index', compact('contacts', 'hotels'));
    }
}
