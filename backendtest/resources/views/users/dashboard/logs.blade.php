<x-app-layout>


    <x-slot name="header">
        <div class="container mb-5 py-2">
            <div class="row">
                <div class="col-6">
                    <h4>My Logs</h4>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-info">Back</a>
                </div>
            </div>
        </div>
    </x-slot>


    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Subject</th>
                                <th>Old Values</th>
                                <th>New Values</th>
                                <th>Descrip.</th>
                            </tr>
                        </thead>
                        <tbody>
                          @php($sn=1)
                            @foreach ($activities as $activity)
                            <tr>
                                <td>{{ $sn++ }}</td>
                                <td>{{ $activity->subject_type }}</td>
                                <td>{{ json_encode($activity->properties['old']??[]) }}</td>
                                <td>{{ json_encode($activity->properties['attributes']??[]) }}</td>
                                <td>{{ $activity->description }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                        </div>
            </div>
        </div>
    </div>
</x-app-layout>
