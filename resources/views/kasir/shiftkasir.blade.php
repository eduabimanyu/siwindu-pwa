 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Shift')

 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Shift</h1>
            <div class="section-header-breadcrumb">
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-12">
                  <div class="card card-primary">
                    @if(Auth::user()->status=='1')
                    <div class="card-header">
                        <a href="{{ route('shiftsaatini') }}"><h4>Shift Saat Ini</h4></a>
                    </div>
                    @else
                    <div class="card-header">
                        <a href="{{ route('shiftbaru') }}"><h4>Shift Baru</h4></a>
                    </div>
                    @endif
                    <div class="card-header">
                        <a href="{{ route('historishift') }}"><h4>Histori Shift</h4></a>
                      </div>
                 </div>
              </div>
            </div>
        </div>
    </section>
</div>

@push('js')
@endpush
@endsection
