<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        @foreach($images as $image)
        <li data-target="#carouselExampleIndicators" data-slide-to="{{$loop->index}}" class="{{($loop->index == 1)?'active':''}}"></li>
        @endforeach
    </ol>
    <div class="carousel-inner">
        @foreach($images as $image)
            @php
                $imagePath = url('storage/linkedin/'.$uuid.'/'.$image->getFilename());
            @endphp
            <div class="carousel-item {{($loop->index == 1)?'active':''}}">
                <img class="d-block w-100" src="{{$imagePath}}" alt="Screenshot">
          </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
</div>
