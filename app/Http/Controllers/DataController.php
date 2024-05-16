<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->session()->get('data', []);
        return view('index', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $data = $request->session()->get('data', []);

        // Handle file upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('images', 'public'); // Save the file in the public storage
            $image = Storage::url($path); // Get the URL to the file
        } else {
            $image = null;
        }

        $newItem = [
            'id' => count($data) + 1,
            'name' => $request->name,
            'image' => $image,
            'address' => $request->address,
            'gender' => $request->gender,
        ];
        $data[] = $newItem;
        $request->session()->put('data', $data);
        return redirect('/');
    }

    public function update(Request $request)
    {
        $data = $request->session()->get('data', []);
        foreach ($data as &$item) {
            if ($item['id'] == $request->id) {
                $item['name'] = $request->name;
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $path = $file->store('images', 'public');
                    $item['image'] = Storage::url($path);
                }
                $item['address'] = $request->address;
                $item['gender'] = $request->gender;
                break;
            }
        }
        $request->session()->put('data', $data);
        return redirect('/');
    }

    public function destroy(Request $request)
    {
        $data = $request->session()->get('data', []);
        $data = array_filter($data, function($item) use ($request) {
            return $item['id'] != $request->id;
        });
        $request->session()->put('data', array_values($data));
        return redirect('/');
    }

    public function view(Request $request)
    {
        $data = $request->session()->get('data', []);
        $item = collect($data)->firstWhere('id', $request->id);
        return response()->json($item);
    }
}
