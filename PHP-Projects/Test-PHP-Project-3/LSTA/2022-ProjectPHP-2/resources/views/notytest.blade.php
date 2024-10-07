<html>
<head>
<link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
<script src="{{ mix('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script>
    PhpProject.toast();
    PhpProject.toast({type: 'error'});
    PhpProject.toast({type: 'info', text: 'test tekst'});
    PhpProject.toast({type: 'warning', text: 'test tekst2'});
</script>
</body>
</html>
