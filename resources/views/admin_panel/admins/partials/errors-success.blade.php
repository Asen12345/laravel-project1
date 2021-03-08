@if ($errors->any())
    <div class="col-12" id="error">
        <div class="alert alert-danger alert-dismissible fade show mb-2 rounded-0" role="alert">
            <ul class="m-auto">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" onclick="$('#error').css('display', 'none')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif
@if (session()->has('success'))
    <div class="col-12" id="success">
        <div class="alert alert-success alert-dismissible fade show mb-2 rounded-0" role="alert">
            <ul class="m-auto">
                <li>{{session()->get('success')}}</li>
            </ul>
            <button type="button" class="close" onclick="$('#success').css('display', 'none')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif