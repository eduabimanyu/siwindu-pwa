<div class="modal fade" id="modal-item" tabindex="-1" role="dialog" aria-labelledby="modal-item">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Pilih Item</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-item">
                    <thead>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($item as $item)
                        @if ($item->wisata == Auth::user()->wisata)
                            <tr>
                                <td><span class="label label-success">{{ $item->kode_item }}</span></td>
                                <td>{{ $item->nama_item }}</td>
                                <td>{{ $item->harga }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="pilihItem('{{ $item->id_item }}', '{{ $item->kode_item }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
