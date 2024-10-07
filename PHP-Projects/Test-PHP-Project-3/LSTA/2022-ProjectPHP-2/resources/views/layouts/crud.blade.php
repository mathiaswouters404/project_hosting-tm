@extends("layouts.template")

@section("main")
    <H1 class="mt-4 mb-4 p-3 page-title text-secondary">Titel</H1>

    <div class="table-container p-3">
        <div class="d-flex">
            <form class="flex-grow-1 mr-3">
                <input class="form-control" type="text" placeholder="Search">
            </form>
            <a class="btn btn-dark mb-3" href="#" role="button">Nieuw</a>
        </div>
        <table class="table m-0 table-hover">
            <thead class="bg-dark text-white">
            <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th scope="col">Handle</th>
                <th scope="col" class="text-center">Edit</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
                <td class="text-center">
                    <form action="#" method="post">
                        @method('delete')
                        @csrf
                        <div class="btn-group btn-group-sm">
                            <a href="#" class="btn btn-outline-success"
                               data-toggle="tooltip"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="submit" class="btn btn-outline-danger"
                                    data-toggle="tooltip"
                                    title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </form>
                </td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td>@fat</td>
                <td class="text-center">
                    <form action="#" method="post">
                        @method('delete')
                        @csrf
                        <div class="btn-group btn-group-sm">
                            <a href="#" class="btn btn-outline-success"
                               data-toggle="tooltip"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="submit" class="btn btn-outline-danger"
                                    data-toggle="tooltip"
                                    title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </form>
                </td>
            </tr>
            <tr>
                <th scope="row">3</th>
                <td>Larry</td>
                <td>the Bird</td>
                <td>@twitter</td>
                <td class="text-center">
                    <form action="#" method="post">
                        @method('delete')
                        @csrf
                        <div class="btn-group btn-group-sm">
                            <a href="#" class="btn btn-outline-success"
                               data-toggle="tooltip"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="submit" class="btn btn-outline-danger"
                                    data-toggle="tooltip"
                                    title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
