 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Histori Shift')


 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Histori Shift</h1>
            <div class="section-header-breadcrumb">
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 ">
                              <table class="table table-striped" id="tabel">
                                    <thead>
                                        <tr>
                                             <!--<th scope="col">id</th>-->
                                            <th scope="col">Shift Selesai</th>
                                            <th scope="col">Detail</th>
                                        </tr>
                                    </thead>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


@push('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
 $(document).ready(function () {
      isi()

  })

  function isi() {
      $('#tabel').DataTable({
          responsive: true,
          processing: true,
          serverSide: true,
          autoWidth: false,
          ajax : {
              url : "{{route('shift.data')}}"
          },
          columns:[
                //  {data: 'idshift', name: 'idshift'},
                  {data: 'tanggalakhir', name: 'tanggalakhir'},
                  {data: 'aksi', name: 'aksi'}
              ],
              searching: false,
                bInfo : false,
                bSort: false,
                paginate: false
      })
  }


</script>
  @endpush
  @endsection
