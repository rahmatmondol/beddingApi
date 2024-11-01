<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;

class UserServiceController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('user.service.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'skills' => 'required',
            'category' => 'required',
            'price' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'price_type' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $service = new Service();
        $service->name = $request->name;
        $service->description = $request->description;
        $service->skills = $request->skills;
        $service->price = $request->price;
        $service->price_type = $request->price_type;
        $service->currency = $request->currency;
        $service->location = $request->location;
        $service->latitude = $request->latitude;
        $service->longitude = $request->longitude;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/service/', $filename);
            $service->image = url('/').'/uploads/service/' . $filename;
        }

        $service->category()->associate($request->category);
        $service->user()->associate(auth()->user());
        
        $service->save();
        if ($errors = $request->session()->get('errors')) {
            return redirect()->back()->withErrors($errors);
        }

        return redirect()->back()->with('success', 'Service created successfully');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id); // Retrieve the service by its ID
        $categories = Category::all();
        return view('user.service.edit', compact('service', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'skills' => 'required',
            'category' => 'required',
            'price' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'price_type' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]); 

        $service = Service::findOrFail($id);
        $service->name = $request->name;
        $service->description = $request->description;
        $service->skills = $request->skills;
        $service->price = $request->price;
        $service->price_type = $request->price_type;
        $service->currency = $request->currency;
        $service->location = $request->location;
        $service->latitude = $request->latitude;
        $service->longitude = $request->longitude;

        // Update the image if a new image is uploaded
        if ($request->hasFile('image')) {
            if ($service->image) {
                $imagePath = public_path('uploads/service/' . $service->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/service/', $filename);
            $service->image = url('/').'/uploads/service/' . $filename;
        }

        $service->category()->associate($request->category);
        $service->user()->associate(auth()->user());
        $service->save();
        if ($errors = $request->session()->get('errors')) {
            return redirect()->back()->withErrors($errors);
        }
        return redirect()->back()->with('success', 'Service updated successfully');
    }
}

