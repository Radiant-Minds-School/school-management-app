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
                        <h1>{{ $teacher->name }}</h1>
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
                                        <img class="profile-user-img img-fluid img-circle" src="
                                            @if ($teacher->image)
                                    {{ asset($teacher->image) }} @else
                                        {{ asset('images/user1.svg') }} @endif"
                                        alt="teacher image">
                                    </div>

                                    <h3 class="profile-username text-center">
                                        {{ $teacher->first_name . ' ' . $teacher->last_name }}
                                    </h3>

                                    <p class="text-muted text-center" id="admissionNo">{{ $teacher->admission_no }}
                                    </p>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('teacher.edit', ['teacher' => $teacher]) }}">
                                            <button class="btn btn-primary" data-toggle="modal"
                                                data-target="#editModal">Edit</button>
                                        </a>
                                    </div>

                                    @if ($teacher->signature != null)
                                        <div class="d-flex justify-content-center pt-4">
                                            <img src="{{ asset($teacher->signature) }}" class="img-responsive"
                                                height="30" width="50" alt="teacher'signature">
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
                                        @auth('web')
                                            <li class="nav-item"><a class="nav-link" href="#status"
                                                    data-toggle="tab">Status</a></li>
                                        @endauth
                                        @auth('teacher')
                                            <li class="nav-item"><a class="nav-link" href="#signature"
                                                    data-toggle="tab">Signature Upload</a></li>
                                        @endauth
                                        @auth('teacher')
                                            <li class="nav-item"><a class="nav-link" href="#changePassword"
                                                    data-toggle="tab">Change Password</a></li>
                                        @endauth

                                    </ul>

                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="about">
                                            <strong></i>First name</strong>

                                            <p class="text-muted" id="first_name">{{ $teacher->first_name }}</p>

                                            <hr>

                                            <strong></i>Last name</strong>

                                            <p class="text-muted" id="last_name">{{ $teacher->last_name }}</p>

                                            <hr>

                                            <strong></i>Sex</strong>

                                            <p class="text-muted" id="sex">{{ $teacher->sex }}</p>

                                            <hr>

                                            <strong></i>Phone number</strong>

                                            <p class="text-muted" id="phone_number">{{ $teacher->phone }}</p>

                                            <hr>

                                            <strong></i>Email</strong>

                                            <p class="text-muted" id="email">{{ $teacher->email }}</p>

                                            <hr>
                                            <strong></i>Class</strong>
                                            @if ($teacher->branchClassroom)
                                                <a
                                                    href="{{ route('classroom.show.branch', ['classroom' => $teacher->branchClassroom->classroom, 'branch' => $teacher->branchClassroom->branch]) }}">
                                                    <p class="text-info" id="classroom">
                                                        {{ $teacher->branchClassroom->classroom->name }}
                                                        ({{ $teacher->branchClassroom->branch->name }})
                                                    </p>
                                                </a>
                                            @else
                                                null
                                            @endif

                                            <hr>

                                            <strong></i>Date of birth</strong>

                                            <p class="text-muted" id="dob">{{ $teacher->date_of_birth }}</p>

                                        </div>

                                        <!-- /.tab-pane -->

                                        @auth('web')
                                            <div class="tab-pane" id="status">
                                                <div class="form-group row">
                                                    {{-- Status --}}
                                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                                    <div class="col-sm-10">
                                                        <div class="btn-group">
                                                            <form
                                                                action="{{ route('teacher.activate', ['teacher' => $teacher]) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="btn @if ($teacher->isActive()) btn-primary
                                                                disabled @else btn-default @endif
                                                                    btn-flat"
                                                                    @if ($teacher->isActive()) disabled
                                                                    @endif>
                                                                    Activate
                                                                </button>
                                                            </form>
                                                            <form
                                                                action="{{ route('teacher.deactivate', ['teacher' => $teacher]) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="btn @if (!$teacher->isActive()) btn-primary
                                                                disabled @else btn-default @endif
                                                                    btn-flat"
                                                                    @if (!$teacher->isActive()) disabled
                                                                    @endif>
                                                                    Deactivate
                                                                </button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                    {{-- /Status --}}
                                                </div>
                                            </div>
                                        @endauth
                                        <!-- /.tab-pane -->
                                        @auth('teacher')
                                            <div class="tab-pane" id="signature">
                                                <form
                                                    action="{{ route('teacher.store.signature', ['teacher' => $teacher]) }}"
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
                                                                    <span class="input-group-text">Upload</span></button>
                                                            </div>
                                                        </div>
                                                        @error('signature')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane" id="changePassword">
                                                <form
                                                    action="{{ route('teacher.update.password', ['teacher' => $teacher]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="form-group">
                                                        <label for="old_password">Current password</label>
                                                        <input type="password"
                                                            class="form-control @error('current_password') is-invalid @enderror"
                                                            placeholder="Enter current password" name="current_password"
                                                            required>
                                                        @error('current_password')
                                                            <div class="text-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="new_password">New password</label>
                                                        <input type="password"
                                                            class="form-control @error('new_password') is-invalid @enderror"
                                                            placeholder="Enter new password" name="new_password" required>
                                                        @error('new_password')
                                                            <div class="text-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="confirm_password">Cofirm password</label>
                                                        <input type="password"
                                                            class="form-control @error('new_password_confirmation') is-invalid
                                                                @enderror"
                                                            placeholder="Confirm new password"
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
                                        @endauth

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

    {{-- /edit teacher modal --}}
    <x-slot name="scripts">

        <!-- AdminLTE App -->
    </x-slot>
</x-app-layout>
