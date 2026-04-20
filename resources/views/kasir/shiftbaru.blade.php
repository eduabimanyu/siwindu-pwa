 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'shiftbaru')


 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Shift Baru</h1>
            <div class="section-header-breadcrumb">
            </div>
        </div>
        <div class="section-body">
            <form action="{{ route('shift.store') }}" method="post" id="forms">
                @csrf
            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Masukan Saldo Tunai Saat Ini</label>
                                <input type="number" placeholder="Saldo Tunai Rp.0" class="form-control" name="saldo_tunai" required>
                              </div>
                              <button class="btn btn-primary mr-1 col-12" type="submit">Mulai Shift</button>
                        </div>
                    </div>
                </div>
            </div>
        </form >
        </div>
    </section>
</div>


      @push('js')
      <script src="{{ asset('template') }}/plugins/jquery/jquery.min.js"></script>
      <!-- jQuery UI 1.11.4 -->
      <script src="{{ asset('template') }}/plugins/jquery-ui/jquery-ui.min.js"></script>


  @endpush
  @endsection
