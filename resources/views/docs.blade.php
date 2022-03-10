@extends('index')


@section('title', 'About')

@section('content')
    @php
        $episodesUrl = "episodes/1";
        $locationsUrl = "locations/2";
        $charactersUrl = "characters/1";
    @endphp
    <div class="row">
        <div class="col-md-3">
            <h5><a href="#general">General</a></h5>
            <ul class="list-group">
                <li class="list-group-item"><a href="#introduction">Introdcution</a></li>
                <li class="list-group-item"><a href="#gettingStarted">Getting started</a></li>
                <li class="list-group-item"><a href="#baseUrl">Base URL</a></li>
                <li class="list-group-item"><a href="#rateLimiting">Rate limiting</a></li>
                <li class="list-group-item"><a href="#authentication">Authentication</a></li>
                <li class="list-group-item"><a href="#searching">Searching</a></li>
                <li class="list-group-item"><a href="#encoding">Encoding</a></li>
            </ul>
            <h5><a href="#resources">Resources</a></h5>
            <ul class="list-group">
                <li class="list-group-item"><a href="#characters">Characters</a></li>
                <li class="list-group-item"><a href="#episodes">Episodes</a></li>
                <li class="list-group-item"><a href="#locations">Locations</a></li>
            </ul>
        </div>
        <div class="col-md-9">
            <h1>Documentation</h1>
            <hr/>
            <h2 id="general">General</h2>
            <h4 id="introduction">Introduction</h4>
            <p>Welcome to the South Park API! This documentation should help you understand how to utilize the South Park API and become more familiair with it. If you want to know more about this project read the <a href="{{route('about')}}">about</a> page. If you have any improvements to the documentation or code, open up a pull request on <a href="https://github.com/Danoctum/South-Park-API">Github</a>.</p>
            <h4 id="gettingStarted">Getting started</h4>
            <p>Lets make our first request to the South Park API!</p>
            <p>To get started making use of the API, use a tool that allows you to make an API request, like curl, Insomnia or the browser. In the below example we're trying to get the first episode with curl:</p>
            <pre><samp>curl {{ env('API_URL') }}{{$episodesUrl}}</samp></pre>
            <p>Here's the response we get:</p>
            <pre><samp class="episodeResponse"></samp></pre>
            <p>That's it! You've done an API call and you can parse the returned data with whatever language you prefer. Your response might look different. Don't worry as there might have been added more code to the API after this is written.</p>

            <h4 id="baseUrl">Base URL</h4>
            <p>The Base URL is the root URL for all of the API. Always make sure that your API requests start with this URL. If you ever get a 404 not found, check if the Base URL is right first.</p>
            <p>The Base URL for the South Park API is:</p>
            <pre><samp>{{ env('API_URL') }}</samp></pre>
            <p>The documentation below assumes you are prepending the base URL to your API requests.</p>

            <h4 id="rateLimiting">Rate limiting</h4>
            <p>The API currently does not have any rate limiting. If you are making heavy use of the API, please consider caching the results to limit server load. This might be introduced in the future if there are any signs of abuse.</p>

            <h4 id="authentication">Authentication</h4>
            <p>The South Park API is a completely open API. This means that no authentication is required to make API calls and get data.</p>

            <h4 id="searching">Searching</h4>
            <p>All resources support a <code>search</code> parameter that filters the resource returned. This allows you to make queries like: <pre><samp>{{ env('API_URL') }}characters?search=eric</samp></pre></p>
            <p>All searches will do partial matches with the field(s) that search is enabled on. The individual resource documentation shows which field(s) can be searched for. </p>

            <h4 id="encoding">Encoding</h4>
            <p>All data returned will be a JSON formatted response. This format is not changeable for now.</p>


            <h2>Resources</h2>
            <hr/>
            {{-- CHARACTERS --}}
            <h4 id="characters">Characters</h4>
            <p>Represents a character in the South Park Universe.</p>
            <p><strong>Endpoints:</strong></p>
            <ul>
                <li><code>/characters</code> - get all the character resources</li>
                <li><code>/characters/{id}</code> - get a specific character resource</li>
            </ul>
            <p><strong>Example request:</strong></p>
            <pre>curl {{ env('API_URL') }}{{$charactersUrl}}</pre>
            <p><strong>Example response:</strong></p>
            <pre class="characterResponse"></pre>
            <p><strong>Attributes:</strong></p>
            <ul>
                <li><code>id</code> - The id of this character</li>
                <li><code>name</code> - The name this character is known as</li>
                <li><code>age</code> - The age of this character in years</li>
                <li><code>sex</code> - The sex of this character (if known). Will be null if it's not known.</li>
                <li><code>hair_color</code> - The hair color of this character</li>
                <li><code>occupation</code> - The occupation of this character</li>
                <li><code>grade</code> - The grade this character is in (if in school)</li>
                <li><code>religion</code> - The main religion of this character (temporary changes of religion not tracked)</li>
                <li><code>voiced_by</code> - The voice actor name of this character</li>
                <li><code>created_at</code> - The ISO 8601 datetime format of the time that this resource was created</li>
                <li><code>updated_at</code> - The ISO 8601 datetime format of the time that this resource was updated</li>
                <li><code>url</code> - The url of this resource</li>
                <li><code>relatives</code> - An array of relatives with the relative url and relation for this character (from the viewpoint of the queried character)</li>
                <li><code>episodes</code> - An array of urls of episodes this character has appeared in</li>
            </ul>
            <p><strong>Search fields:</strong></p>
            <ul>
                <li><code>name</code></li>
            </ul>
            <hr/>

            {{-- EPISODES --}}
            <h4 id="episodes">Episodes</h4>
            <p>An episode of the South Park series.</p>
            <p><strong>Endpoints:</strong></p>
            <ul>
                <li><code>/episodes</code> - get all the episode resources</li>
                <li><code>/episodes/{id}</code> - get a specific episode resource</li>
            </ul>
            <p><strong>Example request:</strong></p>
            <pre>curl {{ env('API_URL') }}{{$episodesUrl}}</pre>
            <p><strong>Example response:</strong></p>
            <pre class="episodeResponse"></pre>
            <p><strong>Attributes:</strong></p>
            <ul>
                <li><code>id</code> - The id of this episode</li>
                <li><code>name</code> - The title of this episode</li>
                <li><code>season</code> - The season this episode is a part of</li>
                <li><code>episode</code> - The episode number in the season</li>
                <li><code>air_date</code> - ISO 8601 date format of the date this episode aired</li>
                <li><code>description</code> - The description of this episode</li>
                <li><code>thumbnail_url</code> - Thumnail URL that references the wiki this API scrapes. These images are quite high resolution, so keep in mind that loading these thumbnails requires quite some bandwidth.</li>
                <li><code>wiki_url</code> - URL to the wiki for this episode.</li>
                <li><code>created_at</code> - The ISO 8601 datetime format of the time that this resource was created</li>
                <li><code>updated_at</code> - The ISO 8601 datetime format of the time that this resource was updated</li>
                <li><code>characters</code> - An array of characters that made an appearance this episode</li>
                <li><code>locations</code> - An array of locations that made an appearance this episode</li>
            </ul>
            <p><strong>Search fields:</strong></p>
            <ul>
                <li><code>name</code></li>
            </ul>
            <hr/>

            {{-- LOCATIONS --}}
            <h4 id="locations">Locations</h4>
            <p>A location in the South Park universe.</p>
            <p><strong>Endpoints:</strong></p>
            <ul>
                <li><code>/locations</code> - get all the location resources</li>
                <li><code>/locations/{id}</code> - get a specific location resource</li>
            </ul>
            <p><strong>Example request:</strong></p>
            <pre>curl {{ env('API_URL') }}{{$locationsUrl}}</pre>
            <p><strong>Example response:</strong></p>
            <pre class="locationResponse"></pre>
            <p><strong>Attributes:</strong></p>
            <ul>
                <li><code>id</code> - The id of this location</li>
                <li><code>name</code> - The name of this location</li>
                <li><code>created_at</code> - The ISO 8601 date format of the time that this resource was created</li>
                <li><code>updated_at</code> - The ISO 8601 date format of the time that this resource was updated</li>
                <li><code>episodes</code> - An array of episodes that this location appeared in</li>
            </ul>
            <p><strong>Search fields:</strong></p>
            <ul>
                <li><code>name</code></li>
            </ul>
            <hr/>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function(){
                const processGetExample = (endpoint, elementClass) => {
                    axios.get('https://spapi.dev/api/' + endpoint)
                        .then((data) => {
                            $(`.${elementClass}`).text(JSON.stringify(data.data, null, 2));
                        })
                        .catch((err) => {
                            $(`.${elementClass}`).text(JSON.stringify(err, null, 2));
                        })
                }

                processGetExample("{{ $locationsUrl }}", "locationResponse");
                processGetExample("{{ $episodesUrl }}", "episodeResponse");
                processGetExample("{{ $charactersUrl }}", "characterResponse");
            });
        </script>
    @endpush
@endsection
