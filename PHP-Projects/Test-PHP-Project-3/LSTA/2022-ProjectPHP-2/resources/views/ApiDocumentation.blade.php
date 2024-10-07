@extends('layouts.template')

@section('main')
 <div>
   <h1> API service documentation</h1>
 </div>
 <div>
     <h2>Post request</h2>
     <p>Vergeet zeker niet de crfs token mee te geven. <br>
     Token opvragen:   "&#123;&#123;csrf_token&#40;&#41; &#125;&#125;" <span></span></p>
     <p>Id is optional!</p>
     <code>
         const pars = $(this).serialize(); <br>
         const id = 1; <br>
         const data = ApiService.post("/PostMail", pars,  "{{ csrf_token() }}", id)
     </code>
 </div>
 <div>
     <div>
         <h3>Demo:</h3>
     </div>
     <form action="" method="post" id="form" >
         @method('')
         @csrf
         <div class="form-group">
             <label for="name">Name</label>
             <input type="text" name="name" id="name"
                    class="form-control"
                    placeholder="Name"
                    minlength="3"
                    required
                    value="">
         </div>
         <button type="submit" class="btn btn-success">Save genre</button>
     </form>
 </div>


 <div class="mt-5">

     <div>
         <h2>Get and Delete request</h2>
     </div>
     <p>Vergeet zeker niet de crfs token mee te geven. <br>
         Token opvragen:   "&#123;&#123;csrf_token&#40;&#41; &#125;&#125;" <span></span></p>
     <p>Delete and get working is equivelant.</p>
     <p>Id is optional by get!</p>
     <p>Id is mandatory by del!</p>
     <code>
         const id = 1 <br>
         const data = ApiService.del("/PostMail","{{ csrf_token() }},id")
     </code>
 </div>


@endsection
@section('script_after')
    <script>


        $('#form').submit(function (e) {
            e.preventDefault();
            const pars = $(this).serialize();

            const data = ApiService.post("/PostMail", pars, "{{ csrf_token() }}");
        })

    </script>
@endsection
