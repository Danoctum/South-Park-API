@extends('index')


@section('title', 'Home')

@section('content')
    <div>
        <div>
            <h3>The complete South Park API!</h3>
            <p>All the South Park data you ever wanted.</p>
            <p>Includes: <strong>Charcaters, Episodes and Locations!</strong></p>
        </div>
        <div>
            <h2>Try it now!</h2>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text text-light bg-dark" id="basic-addon3">{{ env('API_URL') }}</span>
                </div>
                <input id="endpoint" type="text" class="form-control" placeholder="characters/1/" value="characters/1">
                <span class="input-group-btn"><button class="btn btn-info requestButton">Request</button></span>
            </div>
            <small>Need a hint? <a href="#" class="hint"><i>characters/1</i></a> or <a href="#" class="hint"><i>episodes/2</i></a> or <a href="#" class="hint"><i>locations/10</i></a></small>
            <br/><br/>
            <h4>Result:</h4>
            <div class="code-block">
                <pre class="pre-scrollable">
<samp class="response-code">{
  "data": {
    "id": 1,
    "name": "Stan",
    "full_name": "Stanley Marsh",
    "age": 10,
    "sex": "male",
    "hair_color": "Black",
    "occupation": "Student",
    "grade": "4th grade",
    "religion": "Roman Catholic",
    "voiced_by": "Trey Parker",
    "first_appearance_episode_id": 1,
    "created_at": null,
    "updated_at": null,
    "url": "{{ env('API_URL') }}characters/1",
    "first_appearance_episode_url": "{{ env('API_URL') }}episodes/1",
    "relatives": [
      {
        "url": "{{ env('API_URL') }}characters/5",
        "relation": "Son"
      }
    ],
    "episodes": [
      "{{ env('API_URL') }}episode/1",
      "{{ env('API_URL') }}episode/2",
      "{{ env('API_URL') }}episode/3"
    ]
  }
}</samp></pre>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-4">
                <h4>What is this?</h4>
                <p>This is the South Park API, it is the first API for south park characters, episodes and locations. It contains information about all of the South Park Universe conveniently in one Application Programming Interface (API). </p>
                <p>We've tried to gather every bit of information that is publicly available about the South Park Universe and made it easy for software to gather the data! We can't guarantee the correctness or completeness of the data being provided, we try to be as correct and complete as possible.</p>
            </div> 
            <div class="col-sm-12 col-md-4 col-lg-4">
                <h4>How to use this?</h4>
                <p>All the data is accessible trough the API with the HTTP protocol. We've made <a href="{{route('docs')}}">documentation</a> to clarify how the API can be used. Go there to get started!</p>
            </div> 
            <div class="col-sm-12 col-md-4 col-lg-4">
                <h4>How to contribute?</h4>
                <p>This project is open source, meaning that everyone can contribute to add new functions or information to this project. Take a look at the <a href="https://github.com/Danoctum/South-Park-API">Github Repository</a> to see how to contribute. Your contribution is very welcome!</p>
            </div> 
        </div>
    </div>
    @push('scripts')
    <script>
        $(document).ready(function(){
            $(".requestButton").click(function() {
                processGetExample($('#endpoint').val());
            });

            $('.hint').click(function() {
                $('#endpoint').val($(this).text());
                processGetExample($(this).text());
            })

            const processGetExample = (endpoint) => {
                axios.get('https://spapi.dev/api/' + endpoint)
                .then((data) => {
                    $('.response-code').text(JSON.stringify(data.data, null, 2));
                })
                .catch((err) => {
                    $('.response-code').text(JSON.stringify(err, null, 2));
                })
            }
        });
    </script>
    @endpush
@endsection