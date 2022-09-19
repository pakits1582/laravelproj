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
    <div class="table-responsive" id="table_data">
        <table class="table table-bordered" id="instructorTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>College</th>
                    <th>Educ Level</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if (count($instructors) > 0)
                    @foreach ($instructors as $instructor)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $instructor->user->idno }}</td>
                            <td>{{ $instructor->name }}</td>
                            <td>{{ $instructor->collegeinfo->code }}</td>
                            <td>{{ $instructor->educlevel->level }}</td>
                            <td>{{ $instructor->deptcode  }}</td>
                            <td>{{ Helpers::getDesignation($instructor->designation) }}</td>
                            <td>{{ ($instructor->user->is_active == 1) ? 'Active' : 'Inactive'  }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="9" class="mid">No records to be displayed!</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    
</body>
</html>