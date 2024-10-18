@extends('filament::layouts.base')

@section('head')
    <style>
        .fixed-size-image img {
            width: 100%;
            max-width: 700px;
            height: auto;
            max-height: 700px;
            display: block;
            margin: 0 auto;
            cursor: zoom-in;
            transition: transform 0.3s ease;
        }

        .fixed-size-image img.zoomed {
            transform: scale(2); /* Adjust the zoom level as needed */
            cursor: zoom-out;
        }
    </style>
@endsection

@section('content')
    <div class="fixed-size-image">
        <img id="facility-image" src="{{ $infolist->getImageUrl('facility_img') }}" alt="Facility Image">
    </div>
    {!! $infolist->render() !!}
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const img = document.getElementById('facility-image');

            img.addEventListener('click', function () {
                this.classList.toggle('zoomed');
            });
        });
    </script>
@endsection
