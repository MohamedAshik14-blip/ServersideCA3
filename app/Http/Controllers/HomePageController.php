<?php

namespace App\Http\Controllers;
use App\Models\Destination;
use App\Models\Hotels;
use App\Models\Image;
use Illuminate\Http\Request;
use DB;

class HomePageController extends Controller{
    public function index(){
        $destinations=Destination::with('images')->get();
        return view('Homepage',['destinations'=>$destinations]);

    }
    public function showAdminPage(){
        $destinations=Destination::with('images')->get();
        return view('adminhomepage', ['destinations'=>$destinations]);
    }




    public function createDestination()
    {
        return view('homepage.create_destination');
    }

   public function storeDestination(Request $request)
{
    $request->validate([
        'name' => 'required',
        'description' => 'nullable|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'hotels.*.hotel_name' => 'required|string',
        'hotels.*.stars' => 'required|integer|between:1,5',
        'hotels.*.hotel_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Store destination image
    $imageName = $request->image->getClientOriginalName();  
    $request->image->move(public_path('images'), $imageName);

    // Create destination
    $destination = Destination::create([
        'name' => $request->name,
        'description' => $request->description,
    ]);

    // Store destination image record
    $image = Image::create([
        'image_path' => 'images/'.$imageName,
        'destination_id' => $destination->id,
    ]);

    // Store hotels
    foreach ($request->hotels as $hotelData) {
        $hotelImageName = $hotelData['hotel_image']->getClientOriginalName();  
        $hotelData['hotel_image']->move(public_path('images'), $hotelImageName);

        $hotel = new Hotels();
        $hotel->name = $hotelData['hotel_name'];
        $hotel->stars = $hotelData['stars'];
        $hotel->image_url = 'images/'.$hotelImageName;
        $destination->hotels()->save($hotel);
    }

    return redirect()->route('adminhomepage')->with('success', 'Destination and Hotels created successfully.');
}


public function editDestination($destination_id)
{
    $destination = Destination::findOrFail($destination_id);
    $hotels = $destination->hotels;
    return view('homepage.edit_destination', compact('destination', 'hotels'));
}


    public function updateDestination(Request $request, $id)
{
    $destination = Destination::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'hotel_name' => 'required|string',
        'stars' => 'required|integer|between:1,5',
        'hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Update destination
    $destination->name = $request->name;
    $destination->description = $request->description;

    if ($request->hasFile('image')) {
        $imageName = time().'.'.$request->image->extension();  
        $request->image->move(public_path('images'), $imageName);

        $image = new Image();
        $image->image_path = 'images/'.$imageName;
        $image->destination_id = $destination->id;
        $image->save();
        
        // If there was an old image associated with the destination, delete it
        if ($destination->images->count() > 0) {
            $destination->images()->delete();
        }
    }

    $destination->save();

    // Update hotel
    if ($request->has('hotel_name') && $request->has('stars')) {
        $hotel = $destination->hotels()->first();

        if (!$hotel) {
            $hotel = new Hotels();
            $hotel->destination_id = $destination->id;
        }

        $hotel->name = $request->hotel_name;
        $hotel->stars = $request->stars;

        if ($request->hasFile('hotel_image')) {
            $hotelImageName = time().'.'.$request->hotel_image->extension();  
            $request->hotel_image->move(public_path('images'), $hotelImageName);
            $hotel->image_url = 'images/'.$hotelImageName;
        }

        $hotel->save();
    }

    return redirect()->route('adminhomepage')->with('success', 'Destination and Hotel updated successfully.');
}


public function destroyDestination($id)
{
    $destination = Destination::findOrFail($id);

    // Delete associated hotel
    if ($destination->hotels->isNotEmpty()) {
        foreach ($destination->hotels as $hotel) {
            $hotel->delete();
        }
    }

    // Delete destination
    $destination->delete();

    return redirect()->route('adminhomepage')->with('success', 'Destination and associated hotel deleted successfully.');
}


    public function showDestination($id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            // Handle case when destination doesn't exist
            return redirect()->route('homepage')->with('error', 'Destination not found.');
        }

        $hotels = Hotels::where('destination_id', $id)->get();

        return view('homepage.show_destination', compact('destination', 'hotels'));
    }

    public function createHotel($destination_id)
    {
        $destination = Destination::findOrFail($destination_id);
        return view('homepage.create_destination', compact('destination'));
    }
    


    

    public function editHotel($destination_id, $hotel_id)
    {
        $hotel = Hotels::findOrFail($hotel_id);
        return view('homepage.edit_destination', compact('hotel'));
    }

    

    public function deleteHotel($destination_id, $hotel_id)
    {
        $hotel = Hotels::findOrFail($hotel_id);
        $hotel->delete();
        return redirect()->route('destination.show', ['id' => $destination_id])->with('success', 'Hotel deleted successfully.');
    }
}

