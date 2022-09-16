<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>asdasd</h1>
    <div class="table-responsive">
        <table class="table table-bordered" id="subjectTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Units</th>
                    <th>Tuition</th>
                    <th>Load</th>
                    <th>Lec</th>
                    <th>Lab</th>
                    <th>Hours</th>
                    <th>isProf</th>
                    <th>isLab</th>
                    <th>Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (count($subjects) > 0)
                    @foreach ($subjects as $subject)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subject->code }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ $subject->units }}</td>
                            <td>{{ $subject->tfunits }}</td>
                            <td>{{ $subject->loadunits }}</td>
                            <td>{{ $subject->lecunits }}</td>
                            <td>{{ $subject->labunits }}</td>
                            <td>{{ $subject->hours }}</td>
                            <td>{{ ($subject->professional == 1) ? 'YES' : 'NO' }}</td>
                            <td>{{ ($subject->laboratory == 1) ? 'YES' : 'NO' }}</td>
                            <td>{{ $subject->educlevel->code }}</td>
                            <td class="">
                                <a href="{{ route('subjects.edit', ['subject' => $subject->id ]) }}" class="btn btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span class="text">Edit</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="13" class="mid">No records to be displayed!</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    
</body>
</html>