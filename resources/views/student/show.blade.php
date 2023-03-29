<x-app-layout>
    <x-slot name="styles">

        <!-- DataTables -->
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    </x-slot>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $student->name }}</h1>
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
                                            src="{{ asset($student->image) ?? 'https://img.icons8.com/external-smashingstocks-hand-drawn-black-smashing-stocks/99/000000/external-student-education-smashingstocks-hand-drawn-black-smashing-stocks.png' }}"
                                            alt="student image">
                                    </div>

                                    <h3 class="profile-username text-center">
                                        {{ $student->first_name . ' ' . $student->last_name }}
                                    </h3>

                                    <p class="text-muted text-center" id="admissionNo">{{ $student->admission_no }}
                                    </p>
                                    @auth('web')
                                        <div class="d-flex justify-content-center"><button class="btn btn-primary"
                                                data-toggle="modal" data-target="#editModal">Edit</button></div>
                                    @endauth
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
                                        <li class="nav-item"><a class="nav-link" href="#guardianInfo"
                                                data-toggle="tab">Guardian</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#results"
                                                data-toggle="tab">Results</a></li>
                                        @auth('web')
                                            <li class="nav-item"><a class="nav-link" href="#imageUpload"
                                                    data-toggle="tab">Image Upload</a></li>
                                        @endauth
                                    </ul>

                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="about">
                                            <strong>Class</strong>

                                            @if (auth('web')->user() || auth('teacher')->user())
                                                <a
                                                    href="{{ route('classroom.show', ['classroom' => $student->classroom]) }}">
                                                    <p class="text-info" id="classroom">
                                                        {{ $student->classroom->name }}
                                                    </p>
                                                </a>
                                            @else
                                                <p id="classroom">
                                                    {{ $student->classroom->name }}
                                                </p>
                                            @endif

                                            <hr>

                                            <strong>Local
                                                government</strong>

                                            <p class="text-muted" id="lg"> {{ $student->lg }}</p>

                                            <hr>

                                            <strong>State</strong>

                                            <p class="text-muted" id="state"> {{ $student->state }}</p>
                                            <hr>

                                            <strong>Country</strong>

                                            <p class="text-muted" id="country">{{ $student->country }}</p>
                                            <hr>

                                            <strong>Date of birth</strong>

                                            <p class="text-muted" id="dob">{{ $student->date_of_birth }}</p>

                                            <hr>

                                            <strong>Place of birth</strong>

                                            <p class="text-muted" id="pob">{{ $student->place_of_birth }}</p>

                                            <hr>
                                            <strong>Blood Group</strong>

                                            <p class="text-muted" id="bloodGroup">{{ $student->blood_group }}</p>

                                            <hr>
                                            <strong>status</strong>

                                            <p class="text-muted" id="status">
                                                @if ($student->isActive())
                                                    Active
                                                @else
                                                    Inactive
                                                @endif
                                            </p>

                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="guardianInfo">
                                            <strong>Full name</strong>
                                            <p class="text-muted" id="gFullname">
                                                {{ $student->guardian->title . ' ' . $student->guardian->first_name . ' ' . $student->guardian->last_name }}
                                            </p>

                                            <hr>
                                            <strong>Occupation</strong>
                                            <p class="text-muted" id="gOccupation">
                                                {{ $student->guardian->occupation }}</p>

                                            <hr>
                                            <strong>Email</strong>
                                            <p class="text-muted" id="gEmail">
                                                @if (is_null($student->guardian->email))
                                                    No email address provided
                                                @else
                                                    {{ $student->guardian->email }}
                                                @endif
                                            </p>

                                            <hr>
                                            <strong>Phone</strong>
                                            <p class="text-muted" id="gPhone">{{ $student->guardian->phone }}</p>

                                            <hr>
                                            <strong>Address</strong>
                                            <p class="text-muted" id="gAddress">{{ $student->guardian->address }}
                                            </p>
                                            @auth('web')
                                                <hr>
                                                <strong>Change guardian</strong>
                                                <p>
                                                <form action="{{ route('guardian.change', ['student' => $student]) }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="row">
                                                        <select class="form-control col-lg-9" name="guardian"
                                                            id="change-guardian">
                                                            @foreach ($guardians as $guardian)
                                                                <option value="{{ $guardian->email }}"
                                                                    @if ($guardian->id == $student->guardian->id) selected @endif>
                                                                    {{ "$guardian->title $guardian->first_name $guardian->last_name ($guardian->email)" }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="ml-2">
                                                            <button class="btn btn-primary" type="submit">Change</button>
                                                        </span>
                                                    </div>
                                                </form>
                                                </p>
                                            @endauth
                                        </div>
                                        <!-- /.tab-pane -->

                                        <div class="tab-pane" id="results">
                                            <h3>Result Type</h3>
                                            <div>
                                                <div class="btn-group">
                                                    <button type="button" id="showSessionalResultButton"
                                                        class="btn btn-info" data-toggle="modal"
                                                        data-target="#sessionalResultModal">Sessional</button>
                                                    <button type="button" class="btn btn-warning"
                                                        data-toggle="modal"
                                                        data-target="#termResultModal">Term</button>
                                                </div>
                                                @if (auth('web')->user() || auth('teacher')->user())
                                                    <span class="ml-3" title="Add new result">
                                                        <a
                                                            href="{{ route('result.create', ['student' => $student]) }}">
                                                            <button type="button" id="addNewResultButton"
                                                                class="btn btn-success">Create Result</button>
                                                        </a>
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                        @auth('web')
                                            <div class="tab-pane" id="imageUpload">
                                                <form
                                                    action="{{ route('student.upload.image', ['student' => $student]) }}"
                                                    method="post" id="imageUploadForm" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="imageUpload">File input</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" name="image"
                                                                    @error('image') is-invalid @enderror
                                                                    class="custom-file-input">
                                                                <label class="custom-file-label" for="imageUpload">Choose
                                                                    file</label>
                                                            </div>
                                                            <div class="input-group-append">
                                                                <button type="submit">
                                                                    <span class="input-group-text">Upload</span></button>
                                                            </div>
                                                        </div>
                                                        @error('image')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </form>
                                            </div>
                                        @endauth
                                        <!-- /.tab-pane -->
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

        {{-- sessional result modal --}}
        <div class="modal fade" id="sessionalResultModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Choose Session</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if (!$academicSessions->isEmpty())
                            <div class="form-group">
                                <label>Academic Session</label>
                                <select class="form-control select2" id="academicSession" style="width: 100%;">
                                    @foreach ($academicSessions as $academicSession)
                                        <option
                                            value="{{ route('student.get.sessional.results', ['student' => $student, 'academicSessionName' => $academicSession->name]) }}"
                                            @if (old('academicSession') == $academicSession) SELECTED @endif>
                                            {{ $academicSession->name }} @if ($activePeriod->academicSession->id == $academicSession->id)
                                                *
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" onclick="getSessionalResult()"
                                    class="btn btn-primary">Submit</button>
                            </div>
                        @else
                            <h5 class="text-center">No result is available 😥</h5>
                        @endif
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        {{-- /sessional result modal --}}

        {{-- Term result modal --}}
        <div class="modal fade" id="termResultModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Choose Term</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if (!$academicSessions->isEmpty())
                            <div class="form-group">
                                <label>Term</label>
                                <select class="form-control select2" id="termResultAcademicSession"
                                    style="width: 100%;">
                                    @auth('web')
                                        <option
                                            value="{{ route('student.get.term.results', ['student' => $student, 'academicSessionName' => $activePeriod->academicSession->name, 'termSlug' => $activePeriod->term->slug]) }}">
                                            {{ $activePeriod->academicSession->name }} {{ $activePeriod->term->name }}
                                            (Current Term)
                                        </option>
                                        @foreach ($academicSessions as $academicSession)
                                            @foreach ($terms as $term)
                                                @if ($activePeriod->academicSession->id == $academicSession->id && $activePeriod->term->id == $term->id)
                                                    @continue
                                                @endif
                                                <option
                                                    value="{{ route('student.get.term.results', ['student' => $student, 'academicSessionName' => $academicSession->name, 'termSlug' => $term->slug]) }}">
                                                    {{ $academicSession->name }} {{ $term->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    @endauth

                                    @auth('teacher')
                                        <option
                                            value="{{ route('student.get.term.results', ['student' => $student, 'academicSessionName' => $activePeriod->academicSession->name, 'termSlug' => $activePeriod->term->slug]) }}">
                                            {{ $activePeriod->academicSession->name }} {{ $activePeriod->term->name }}
                                            (Current Term)
                                        </option>
                                        @foreach ($academicSessions as $academicSession)
                                            @foreach ($terms as $term)
                                                @if ($activePeriod->academicSession->id == $academicSession->id && $activePeriod->term->id == $term->id)
                                                    @continue
                                                @endif
                                                <option
                                                    value="{{ route('student.get.term.results', ['student' => $student, 'academicSessionName' => $academicSession->name, 'termSlug' => $term->slug]) }}">
                                                    {{ $academicSession->name }} {{ $term->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    @endauth

                                    {{-- If the current user is a guardian show only published results --}}
                                    @auth('guardian')
                                        @foreach (App\Models\Period::publishedResultsPeriods() as $period)
                                            @if ($activePeriod->academicSession->id == $period->academicSession->id && $activePeriod->term->id == $period->term->id)
                                                <option
                                                    value="{{ route('student.get.term.results', ['student' => $student, 'academicSessionName' => $activePeriod->academicSession->name, 'termSlug' => $activePeriod->term->slug]) }}">
                                                    {{ $activePeriod->academicSession->name }}
                                                    {{ $activePeriod->term->name }}
                                                    (Current Term)
                                                </option>
                                            @else
                                                <option
                                                    value="{{ route('student.get.term.results', ['student' => $student, 'academicSessionName' => $period->academicSession->name, 'termSlug' => $period->term->slug]) }}">
                                                    {{ $period->academicSession->name }} {{ $period->term->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endauth
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" onclick="getTermResult({{ $student }})"
                                    class="btn btn-primary">Submit</button>
                            </div>
                        @else
                            <h5 class="text-center">No result is available 😥</h5>
                        @endif
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        {{-- /.Term result modal --}}
    </div>
    @auth('web')
        {{-- edit student modal --}}
        <div class="modal fade" id="editModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Student</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="{{ route('student.edit', ['student' => $student->admission_no]) }}"
                            id="editStudentButton">
                            <button type="button" class="btn btn-success" title="Edit Student">Student</button>
                        </a>
                        <span class="px-3"></span>
                        <a href="{{ route('guardian.edit', ['guardian' => $student->guardian]) }}"
                            id="editGuardianButton">
                            <button type="button" class="btn btn-info" title="Edit Guardian">Guardian</button>
                        </a>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        {{-- /edit student modal --}}
    @endauth
    <x-slot name="scripts">

        <!-- AdminLTE App -->
        <script>
            function getSessionalResult() {
                let selected = $('#sessionalResultModal #academicSession').val()
                window.location.href = selected
            }

            function getTermResult(student) {
                let selected = $('#termResultModal #termResultAcademicSession').val()
                window.location.href = selected
            }
        </script>
    </x-slot>
</x-app-layout>
