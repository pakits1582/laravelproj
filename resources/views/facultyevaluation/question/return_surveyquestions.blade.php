@if ($questions !== null && count($questions) > 0)
    @foreach ($questions as $question)
        <h4 class="text-primary">{{ $question['category'] }}</h4>
        @foreach ($question['subcategory'] as $subcategory)
            <h5 class="text-success font-italic">{{ $subcategory['subcategory'] }}</h5>
            @foreach ($subcategory['group'] as $group)
                <h6 class="text-black font-weight-bolder pl-5"><u>{{ $group['group'] }}</u></h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped">
                        @foreach ($group['questions'] as $question)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $question['question'] }}</td>
                                <td class="mid w100">
                                    <a href="#" class="btn btn-primary btn-circle btn-sm edit_question" id="{{ $question['id'] }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-circle btn-sm delete_question" id="{{ $question['id'] }}" data-action="deactivate" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endforeach
        @endforeach
    @endforeach
@endif