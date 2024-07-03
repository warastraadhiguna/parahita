@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Benefit</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('benefits.index') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" name="benefitForm" id="benefitForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Nama</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nama" value="{{ $benefit->name }}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon">Icon <a target="_blank" href="https://fontawesome.com/v4/icons/">(Check Icon)</a></label>
                                <input type="text" name="icon" id="icon" class="form-control" placeholder="Icon" value="{{ $benefit->icon }}">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('benefits.index') }}" class="btn btn-outline-dark ml-3">Batal</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

<!-- /.content-wrapper -->
@endsection

@section('customJs')
<script>
    $("#benefitForm").submit(function(event) {
        event.preventDefault();

        var element = $("#benefitForm");
        $("button[type=submit").prop('disabled', true);

        $.ajax({
            url: '{{ route("benefits.update",$benefit->id) }}',
            type: 'put',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {

                $("button[type=submit").prop('disabled', false);

                if (response["status"] == true) {

                    window.location.href = "{{ route('benefits.index') }}";


                    $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                    $("#icon").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                    $("#category").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                } else {

                    if (response['notFound'] == true) {
                        window.location.href = "{{ route('benefits.index') }}";
                        return false;
                    }

                    var errors = response['errors'];
                    if (errors['name']) {
                        $("#name").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['name']);
                    } else {
                        $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }

                    if (errors['icon']) {
                        $("#icon").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['icon']);
                    } else {
                        $("#icon").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }

                    if (errors['category']) {
                        $("#category").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['category']);
                    } else {
                        $("#category").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }
                }
            },
            error: function(jqXHR, exception) {
                console.log("Ada yang tidak beres");
            }
        })
    });
</script>
@endsection