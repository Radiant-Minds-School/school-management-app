<x-app-layout>
    <x-slot name="styles">

        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    </x-slot>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 d-flex">
                    <div class="col-sm-6">
                        <h1>Users</h1>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <div class="">
                                        <h5 class="card-title pt-2">Users</h5>
                                    </div>
                                    <div>
                                        <a href="{{ route('register') }}">
                                            <button class="btn btn-sm btn-primary">Register User</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div>
                                    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
                                    <table id="example1" class="table table-sm table-hover table-bordered  text-center">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>User type</th>
                                                <th>Verified</th>
                                                <th>Status</th>
                                                <th>Last Seen</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>
                                                        <?php
                                                        echo $no;
                                                        $no++;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                    </td>
                                                    <td>
                                                        {{ $user->email }}
                                                    </td>
                                                    <td>{{ $user->user_type }}</td>
                                                    <td class="text-center">
                                                        @if ($user->isVerified())
                                                            <i class="fas fa-check-circle"></i>
                                                        @else
                                                            <i class="far fa-times-circle"></i>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (Cache::has('user-is-online-' . $user->id))
                                                            <i class="fas fa-circle text-green-700 text-sm"
                                                                title="Online"></i>
                                                        @else
                                                            <i class="fas fa-circle text-red-600 text-sm"
                                                                title="Offline"></i>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $user->last_seen }}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-sm">
                                                            @if (!$user->isVerified())
                                                                <form
                                                                    action="{{ route('user.verify', ['user' => $user]) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit"
                                                                        class="btn btn-success btn-flat"
                                                                        title="Verify User"> <i
                                                                            class="fas fa-check"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @if ($user->isVerified())
                                                                @if ($user->isHos())
                                                                    <button type="button"
                                                                        class="btn btn-success btn-flat"
                                                                        title="Current HOS">HOS</i>
                                                                    </button>
                                                                @else
                                                                    <form
                                                                        action="{{ route('user.set-hos', ['user' => $user]) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit"
                                                                            class="btn btn-default btn-flat"
                                                                            title="Set HOS">HOS</i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                            @if ($user->isAdmin())
                                                                <form
                                                                    action="{{ route('user.toggle-status', ['user' => $user]) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('PATCH')

                                                                    @if ($user->isActive())
                                                                        <button class="btn btn-success btn-flat"
                                                                            title="Deactivate User">
                                                                            active
                                                                        </button>
                                                                    @else
                                                                        <button class="btn btn-default btn-flat"
                                                                            title="Activate User">
                                                                            inactive
                                                                        </button>
                                                                    @endif
                                                                </form>
                                                            @endif
                                                            @if ($user->isAdmin())
                                                                <button type="submit" class="btn btn-danger btn-flat"
                                                                    title="Delete"
                                                                    onclick="deleteConfirmationModal('{{ route('user.destroy', ['user' => $user]) }}', {{ $user }})">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>User type</th>
                                                <th>Verified</th>
                                                <th>Status</th>
                                                <th>Last Seen</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <!-- /.content -->

        {{-- Delete confirmation modal --}}
        <div class="modal fade" id="deleteConfirmationModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Confirmation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span id="deleteItemName" class="font-bold"></span>?
                    </div>
                    <div class="modal-footer justify-content-between">
                        <form action="" method="POST" id="yesDeleteConfirmation">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger">Yes</button>
                        </form>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </div>

    <x-slot name="scripts">

        <!-- DataTables  & Plugins -->
        <script src="{{ asset('TAssets/plugins/datatables/jquery.dataTables.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.html5.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.print.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}">
        </script>
        <!-- AdminLTE App -->
        <script>
            function deleteConfirmationModal(url, data) {
                let name = data.first_name + ' ' + data.last_name

                $('#yesDeleteConfirmation').attr("action", url)
                $('#deleteItemName').html(name)
                $('#deleteConfirmationModal').modal('show')
            }

            //datatables
            $(function() {
                $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            });
        </script>
    </x-slot>
</x-app-layout>
