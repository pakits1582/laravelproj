<!DOCTYPE html>
<html lang="en">
    @include('partials.header')

<body class="bg-gradient-primary">
    @include('partials.navbar',['dropdown' => false])
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="text-800 text-primary mb-4">Login Account</h4>
                                    </div>
                                    @if(Session::has('message'))
                                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                    @endif
                                    <form class="user" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>ID Number</label>
                                            <input type="text" class="form-control form-control-user" name="idno"
                                                id="idno" aria-describedby="idno"  placeholder="12345678" value="{{ old('idno') }}">
                                            @error('idno')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control form-control-user" name="password"
                                                id="password" placeholder="*********" value="{{ old('password') }}">
                                            @error('password')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <input type="submit" name="" id="" class="btn btn-primary btn-user btn-block" value="Login Account">
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="card shadow m-4 border-left-primary">
                                    <div class="card-header py-3">
                                        <h4 class="text-800 text-primary mb-0">Online Application</h4>
                                    </div>
                                    <div class="card-body">
                                        <h1 class="h3 text-900 text-primary mb-4 text-center">{{ $configuration->applicationperiod->name }}</h1>

                                        @if ($configuration->status == 1)
                                            <h4 class="text-900 text-danger mb-4 text-center">Application is closed!</h4>
                                        @else
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ url('/applications/onlineapplication') }}" target="_blank" class="btn btn-success btn-icon-split" id="">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    <span class="text">Click here to apply</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.after_footer')
</body>

</html>