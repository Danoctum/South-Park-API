@extends('index')


@section('title', 'About')

@section('content')
    <div>
        <h4>What is this?</h4>
        <p>This is the South Park API, it is the first API for south park characters, episodes and locations. It contains information about all of the South Park Universe conveniently in one Application Programming Interface (API). </p>
        <p>We've tried to gather every bit of information that is publicly available about the South Park Universe and made it easy for software to gather the data! We can't guarantee the correctness or completeness of the data being provided, we try to be as correct and complete as possible.</p>
        <p>Check out the <a href="{{route('docs')}}">documentation</a> to get started consuming the South Park API.</p>
    </div>
    <div>
        <h4>Statistics</h4>
        <p>Amount of characters: {{ $characterCount }}<br/>
        Amount of episodes: {{ $episodeCount }}<br/>
        Amount of locations: {{ $locationCount }}<br/>
        Amount of families: {{ $familyCount }}</p>
    </div>
    <div>
        <h4>What can you use this for?</h4>
        <p>You can use this API to compare data from South Park in any way you like. For example serving data about South Park or creating data-driven applications.</p>
    </div>
    <div>
        <h4>What are the features?</h4>
        <p>We're using the Laravel framework to serve a RESTish API. All data is formatted in JSON, and schema's can be requested for each endpoint. Currently the South Park API supports querying for Episodes, Characters and Locations. You can also search based on certain fields of resources, which is covered in the <a href="{{route('docs')}}">documentation</a></p>
    </div>
    <div>
        <h4>Rate limiting & downtime?</h4>
        <p>The API currently does not have any rate limiting. If you are making heavy use of the API, please consider caching the results to limit server load.</p>
        <p>Downtime or delayed responses may happen once in a while. The server is hosted on DigitalOcean, who usually have update windows between 21:00 UTC until 01:00 UTC. In which the server will either be down, or have delayed response times. </p>
    </div>
    <div>
        <h4>Who are you?</h4>
        <p>I am Ivo Bot, a PHP programmer from The Netherlands. I made this to practice with serving an API through Laravel, feedback is welcome through Github as I aspire to learn from this project.</p>
    </div>
    <div>
        <h4>Copyright and stuff?</h4>
        <p>As of now this project is Open Source. You have permission to use this service, copy the code, edit the code and host it yourself. The data is gathered from the <a href="https://www.southparkstudios.com/wiki/List_of_Episodes">South Park Wiki</a> and the <a href="https://southpark.fandom.com/wiki/South_Park_Archives">South Park Archives</a>. </p>
    </div>
@endsection
