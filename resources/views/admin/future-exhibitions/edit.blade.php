@include('admin.header')

<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Edit Future Exhibition</h1>
            </div>
            <div class="row">
                <div class="col-md-8">
                    @include('admin.future-exhibitions.form', ['exhibition' => $exhibition])
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.footer')