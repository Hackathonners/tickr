@extends('layouts.master')

@section('content')
<div id="tickr-app"></div>
@endsection

@section('scripts')
<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>
</script>
<script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
@endsection
