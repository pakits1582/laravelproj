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
        <table class="table table-bordered" id="programTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Years</th>
                    <th>Level</th>
                    <th>College</th>
                    <th>Head</th>
                    <th>Active</th>
                </tr>
            </thead>
            <tbody>
                @if (count($programs) > 0)
                    @foreach ($programs as $program)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $program->code }}</td>
                            <td>{{ $program->name }}</td>
                            <td>{{ $program->years }}</td>
                            <td>{{ $program->level->level }}</td>
                            <td>{{ $program->collegeinfo->code }}</td>
                            <td>{{ $program->headName }}</td>
                            <td>{{ ($program->active === 1) ? 'YES' : 'NO' }}</td>
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