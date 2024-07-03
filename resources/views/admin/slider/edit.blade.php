@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Slider</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('sliders.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="sliderForm" name="sliderForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control"
                                 placeholder="Title" value="{{ $slider->title }}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <input type="text" name="description" id="description" class="form-control"
                                 placeholder="Description" value="{{ $slider->description }}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="hidden" name="image_id" id="image_id" value="">
                                <label for="image">Image</label>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                            @if(!empty($slider->image))
                            <div>
                                <img width="250" src="{{ asset('front-assets/images/'.$slider->image) }}" alt="">
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option {{ ($slider->status == 1) ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ ($slider->status == 0) ? 'selected' : '' }} value="0">Block</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Tampilkan di Home</label>
                                <select name="showHome" id="showHome" class="form-control">
                                    <option {{ ($slider->showHome == 'Yes') ? 'selected' : '' }} value="Yes">Yes</option>
                                    <option {{ ($slider->showHome == 'No') ? 'selected' : '' }} value="No">No</option>
                                </select>
                            </div>
                        </div>
                </div>
            </div>
            <div class="pb-5 pt-3 me-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('sliders.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
    $("#sliderForm").submit(function(event) {
        event.preventDefault();
        var element = $(this);
        $("button[type=submit").prop('disabled', true);
        $.ajax({
            url: '{{ route("sliders.update",$slider->id) }}',
            type: 'put',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {

                $("button[type=submit").prop('disabled', false);

                if (response["status"] == true) {

                    window.location.href = "{{ route('sliders.index') }}";


                    $("#title").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                    $("#description").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                } else {

                    if (response['notFound'] == true) {
                        window.location.href = "{{ route('sliders.index') }}";
                    }

                    var errors = response['errors'];
                    if (errors['title']) {
                        $("#title").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['title']);
                    } else {
                        $("#title").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }

                    if (errors['description']) {
                        $("#description").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['description']);
                    } else {
                        $("#description").removeClass('is-invalid')
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

    Dropzone.autoDiscover = false;
    const dropzone = $("#image").dropzone({
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });
        },
        url: "{{ route('temp-images.create') }}",
        maxFiles: 1,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            $("#image_id").val(response.image_id);
            console.log(response)
        }, error: function (errors){
            console(errors.status + ": ukuran gambar terlalu besar");
        }
    });
</script>
@endsection