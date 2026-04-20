 <!-- Main Content -->

 @extends('page.user')
 @section('tittle', 'Roll')
 @section('user')


<div class="section-body">           
  <div class="row">
    <div class="col-12 ">
      <div class="card">
          <div class="card-body">
             <table class="table table-striped" id="tabel" >
              <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
                <th scope="col">Aksi</th>
              </tr> 
            </thead>
           </table>
        </div>
       </div>
      </div>
    </div>
  </div>
</div>
  @endsection

