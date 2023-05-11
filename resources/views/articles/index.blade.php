<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Artikel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body style="background: lightgray">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('logout') }}" class="btn btn-md btn-danger mb-3">Logout</a>
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <a href="{{ route('articles.create') }}" class="btn btn-md btn-primary mb-3">Tambah Artikel</a>
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">Gambar</th>
                                <th scope="col">JuduL</th>
                                <th scope="col">Konten</th>
                                <th scope="col">Pencipta</th>
                                <th scope="col">Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              @forelse ($articles as $article)
                                <tr>
                                    <td class="text-center">
                                        <img src="{{ Storage::url('public/images/').$article->image }}" class="rounded" style="width: 150px">
                                    </td>
                                    <td>{{ $article->title }}</td>
                                    <td>{!! $article->content !!}</td>
                                    <td>{{ $article->name }}</td>
                                    <td class="text-center">
                                        <form onsubmit="return confirm('Anda yakin akan menghapus artikel ini?');" action="{{ route('articles.destroy', $article->id) }}" method="POST">
                                            <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                              @empty
                                  <div class="alert alert-danger">
                                      Data artikel belum tersedia.
                                  </div>
                              @endforelse
                            </tbody>
                          </table>
                          {{ $articles->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        //message with toastr
        @if(session()->has('success'))
            toastr.success('{{ session('success') }}', 'Berhasil!');
        @elseif(session()->has('error'))
            toastr.error('{{ session('error') }}', 'Gagal!');
        @endif
    </script>

</body>
</html>
