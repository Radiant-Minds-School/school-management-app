<x-app-layout>
    <x-slot name="styles">
         
        <!-- DataTables -->
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    </x-slot>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User</h1>
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">

                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle"
                                            src="{{ asset('images/user1.svg') }}" alt="user image">
                                    </div>

                                    <h3 class="profile-username text-center">
                                        {{ $user->first_name . ' ' . $user->last_name }}
                                    </h3>

                                    <p class="text-muted text-center" id="admissionNo">{{ $user->user_type }}
                                    </p>

                                    @if ($user->signature != null)
                                        <div class="d-flex justify-content-center pt-1">
                                            <img src="{{ asset($user->signature) }}" class="img-responsive"
                                                height="30" width="50" alt="user's signature">
                                        </div>
                                    @endif
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </div>
                        <!-- /.col -->
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#about"
                                                data-toggle="tab">About</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#edit" data-toggle="tab">Edit</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="#changePassword"
                                                data-toggle="tab">Change Password</a></li>
                                        @if (auth()->user()->id == $user->id)
                                            <li class="nav-item"><a class="nav-link" href="#signature"
                                                    data-toggle="tab">Signature Upload</a></li>
                                        @endif
                                    </ul>

                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="about">
                                            <strong></i>User Type</strong>
                                            <p class="text-muted" id="user_type">{{ $user->user_type }}</p>

                                            <hr>

                                            <strong></i>Email</strong>
                                            <p class="text-muted" id="user_email">{{ $user->email }}</p>

                                            <hr>

                                            <strong></i>Verified</strong>
                                            <p class="text-muted" id="verified">
                                                @if ($user->isVerified())
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </p>

                                            <hr>

                                            <strong></i>Date joined</strong>
                                            <p class="text-muted" id="created_at">{{ $user->created_at }}</p>

                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="edit">
                                            <form action="{{ route('user.update', ['user' => $user]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="form-group">
                                                    <label for="first_name">First name</label>
                                                    <input type="text" class="form-control @error('first_name')
                                                                                                                            is-invalid
                                                    @enderror" value="{{ old('first_name', $user->first_name) }}"
                                                        placeholder="Enter first name" name="first_name" required>
                                                    @error('first_name')
                                                        <div class="text-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="last_name">Last name</label>
                                                    <input type="text" class="form-control @error('last_name')
                                                                                                                        is-invalid
                                                @enderror" value="{{ old('last_name', $user->last_name) }}"
                                                        placeholder="Enter last name" name="last_name" required>
                                                    @error('last_name')
                                                        <div class="text-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control @error('first_name')
                                                                                                                        is-invalid
                                                @enderror" name="email" value="{{ old('email', $user->email) }}"
                                                        placeholder="Enter Email" required>
                                                    @error('email')
                                                        <div class="text-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->

                                        <div class="tab-pane" id="changePassword">
                                            <form action="{{ route('user.update.password', ['user' => $user]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="form-group">
                                                    <label for="old_password">Current password</label>
                                                    <input type="password" class="form-control @error('current_password')
                                                                                        is-invalid
                                                    @enderror" placeholder="Enter current password"
                                                        name="current_password" required>
                                                    @error('current_password')
                                                        <div class="text-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="new_password">New password</label>
                                                    <input type="password" class="form-control @error('new_password')
                                                                                        is-invalid
                                                    @enderror" placeholder="Enter new password" name="new_password"
                                                        required>
                                                    @error('new_password')
                                                        <div class="text-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="confirm_password">Cofirm password</label>
                                                    <input type="password" class="form-control @error('new_password_confirmation')
                                                                                        is-invalid
                                                    @enderror" placeholder="Confirm new password"
                                                        name="new_password_confirmation" required>
                                                    @error('new_password_confirmation')
                                                        <div class="text-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>

                                        @if (auth()->user()->id == $user->id)
                                            <div class="tab-pane" id="signature">
                                                <form action="{{ route('user.store.signature', ['user' => $user]) }}"
                                                    method="post" id="signatureForm" enctype="multipart/form-data">
                                                    @method('PATCH')
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="signature">File input</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" name="signature" @error('signature')
                                                                    is-invalid @enderror class="custom-file-input">
                                                                <label class="custom-file-label" for="signature">Choose
                                                                    file</label>
                                                            </div>
                                                            <div class="input-group-append">
                                                                <button type="submit">
                                                                    <span
                                                                        class="input-group-text">Upload</span></button>
                                                            </div>
                                                        </div>
                                                        @error('signature')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- /.tab-content -->
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

    <x-slot name="scripts">
         
        <!-- AdminLTE App -->
        
    </x-slot>
</x-app-layout>
