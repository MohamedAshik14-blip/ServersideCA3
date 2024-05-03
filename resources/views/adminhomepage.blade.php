@extends('layouts.app')

@section('content')
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Your Website</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Explore More</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Destinations</h1>

        <a href="{{ route('homepage.createDestination') }}">Add Destination</a>

        <div class="destination-list">
    @foreach ($destinations as $destination)
        <div class="destination-item">
            @if ($destination->images->isNotEmpty())
                <!-- Check if there are images associated with the destination -->
                <img src="{{ asset($destination->images->first()->image_path) }}" alt="Image for {{ $destination->name }}">
            @else
                <p>No images available</p>
            @endif
            <div class="destination-info">
                <h2>{{ $destination->name }}</h2>
                <p>{{ $destination->description }}</p>
                <div class="destination-actions">
                    <a href="{{ route('homepage.showDestination', ['id' => $destination->id]) }}">View</a>
                    <a href="{{ route('homepage.editDestination', ['id' => $destination->id]) }}">Edit</a>
                    <form action="{{ route('destination.delete', ['id' => $destination->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


        </div>
    </div>

    <style>
        body {
            background-image: url('your-background-image.jpg');
            background-size: cover;
            background-position: center;
            color: #fff;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .container {
            padding: 20px;
            color:black;
        }

        .destination-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; 
            justify-content: space-between;
            margin-top: 20px;
        }

        .destination-item {
            width: 46%;
            margin-bottom: 20px;
            margin-left:40px;
            background-color: rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 10px;
            align-items: center;
            
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .destination-item img {
            width: 80%;
            float: center;
            margin-left: 70px;
        }

        .destination-info {
            padding: 20px; /* Add some space around the text */
    text-align: center; 
        }

        .destination-info h2 {
            font-size: 24px;
            color:black;
            margin-top: 0;
        }

        .destination-info p {
            font-size: 16px;
            color:black;
        }

        .destination-actions {
    margin-top: 10px;
    text-align: center; /* Center actions horizontally */
}

        .destination-actions a,
        .destination-actions button {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 10px;
        }

        .destination-actions a:hover,
        .destination-actions button:hover {
            background-color: #0056b3;
        }
    </style>
@endsection
