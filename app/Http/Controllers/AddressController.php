<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses;
        return view('buyer.address.index', compact('addresses'));
    }

    public function create()
    {
        return view('buyer.address.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'c_name'=> 'required|string',
            'province' => 'required',
            'p_name' =>'required|string',
            'kecamatan' => 'required',
            'k_name' => 'required|string',
            'postal_code' => 'required',
            'is_default' => 'nullable',
        ]);

        $data['user_id'] = auth()->id();
        $data['is_default'] = $request->has('is_default');

        if ($data['is_default']) {
            Address::where('user_id', auth()->id())
                ->update(['is_default' => false]);
        }

        Address::create($data);

        return redirect()->route('buyer.addresses.index')
            ->with('success', 'Alamat berhasil ditambahkan');
    }

    public function edit(Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);
        return view('buyer.address.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'receiver_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'kecamatan' => 'required',
            'postal_code' => 'required',
            'is_default' => 'nullable',
        ]);

        $data['is_default'] = $request->has('is_default');

        if ($data['is_default']) {
            Address::where('user_id', auth()->id())
                ->update(['is_default' => false]);
        }

        $address->update($data);

        return back()->with('success', 'Alamat diperbarui');
    }

    public function destroy(Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);

        $address->delete();

        return back()->with('success', 'Alamat dihapus');
    }
}
