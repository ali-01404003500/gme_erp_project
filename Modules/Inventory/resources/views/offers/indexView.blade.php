@extends('pdf-templates.basic-template', [
    'title' => 'Offer List',
    'subtitles' => 'Offer List - ' . \Carbon\Carbon::today()->format('Y-m-d'),
])

@push('table')
    <table style="width:100%">
        <thead>
            <tr>
                <th class="text-center" style="width: 8%">Sl</th>
                <th class="text-center">Title</th>
                <th class="text-center">Start Date</th>
                <th class="text-center">End Date</th>
                <th class="text-center">Offer Type</th>
            </tr>
        </thead>
        <tbody>
            @csrf
            @foreach ($offers as $key => $offer)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="text-center">
                        <a href="{{ route('inv.offers.show', $offer->id) }}">{{ $offer->title }}</a>
                    </td>
                    <td class="text-center">{{ $offer->applied_date }}</td>
                    <td class="text-center">{{ $offer->stop_date }}</td>
                    <td class="text-center">{{ $offer->offer_type }}</td>

                </tr>
            @endforeach

        </tbody>
    </table>
@endpush
