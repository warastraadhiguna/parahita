@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Company</h1>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
        @include('admin.message')    
    <!-- Default box -->
    <form action="" method="post" name="companyForm" id="companyForm">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $company->name }}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="address">Address</label>
                                        <textarea name="address" id="address" cols="30" rows="3" class="form-control" placeholder="Address">{{ $company->address }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone" class="form-control" placeholder="phone" value="{{ $company->phone }}">
                                        <p class="error"></p>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Logo</h2>
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">
                                    <br>Drop files here or click to upload.<br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="company-gallery">
                        @if ($company->image)
                        <div class="col-md-3" id="default-image-row">
                            <div class="card">
                                <img src="{{ asset('uploads/company/small/'.$company->image) }}" class="card-img-top" alt="">
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>

            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    $("#companyForm").submit(function(event) {
        event.preventDefault();
        var formArray = $(this).serializeArray();
        $("#button[type='submit']").prop('disabled', true);

        $.ajax({
            url: '{{ route("company.update",$company->id) }}',
            type: 'put',
            data: formArray,
            dataType: 'json',
            success: function(response) {
                $("#button[type='submit']").prop('disabled', false);

                if (response['status'] == true) {
                    $(".error").removeClass('invalid-feedback').html('');
                    $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

                    window.location.href = "{{ route('company.index') }}";
                } else {
                    var errors = response['errors'];

                    $(".error").removeClass('invalid-feedback').html('');
                    $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

                    $.each(errors, function(key, value) {
                        $(`#${key}`).addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(value);
                    });

                }
            },
            error: function(e) {
                console.log(e);
            }
        });
    });

    Dropzone.autoDiscover = false;
    const dropzone = $("#image").dropzone({
        url: "{{ route('company.image.update') }}",
        maxFiles: 10,
        paramName: 'image',
        params: {'company_id': '{{ $company->id }}'},
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            $("#image-row").remove();

            $("#default-image-row").remove();
            //console.log(response)

            var html = `<div class="col-md-3" id="image-row"><div class="card">
                <img src="${response.ImagePath}" class="card-img-top" alt="">
            </div></div>`;

            $("#company-gallery").append(html);
        },
        complete: function(file) {
            this.removeFile(file);
        }
    });
</script>
@endsection