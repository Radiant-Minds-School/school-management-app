<x-app-layout>
    <x-slot name="styles">
    </x-slot>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Terms</h1>
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
                                <h3 class="card-title">Edit {{ $term->name }}</h3>
                            </div>
                            <form method="POST" action="{{ route('term.update', ['term' => $term]) }}">
                                @csrf
                                @method('PATCH')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="term">Term</label>
                                        <input type="text" name="name" value="{{ old('name', $term->name) }}"
                                            class="form-control @error('name') is-invalid @enderror" id="term"
                                            placeholder="Enter term">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
        </section>
        <!-- /.content -->
    </div>
    <x-slot name="scripts"></x-slot>
</x-app-layout>
