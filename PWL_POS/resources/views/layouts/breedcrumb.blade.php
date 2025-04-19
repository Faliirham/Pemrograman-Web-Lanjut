@if(isset($breedcrumb))
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>{{ $breedcrumb->title ?? '' }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          @foreach ($breedcrumb->list ?? [] as $key => $value)
            @if ($key == count($breedcrumb->list) - 1)
              <li class="breadcrumb-item active">{{ $value }}</li>
            @else
              <li class="breadcrumb-item">{{ $value }}</li>
            @endif
          @endforeach
        </ol>
      </div>
    </div>
  </div>
</section>
@endif